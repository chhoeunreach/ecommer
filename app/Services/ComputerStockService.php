<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\ComputerStock;
use App\Utility\ProductUtility;

class ComputerStockService
{
    public function store(array $data, $computer)
    {
        $collection = collect($data);

        $options = ProductUtility::get_attribute_options($collection);

        $combinations = (new CombinationService())->generate_combination($options);
        $manualKeys = array_filter((array) request()->input('manual_variant_keys', []));

        if (count($combinations) > 0 || count($manualKeys) > 0) {
            $computer->is_variant = 1;
            $computer->save();

            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);
                $fieldKey = md5($str);

                // If the user removed this variant row from the UI, its inputs won't be in the request.
                if (!request()->has('price_' . $fieldKey)) {
                    continue;
                }

                $stock = new ComputerStock();
                $stock->computer_id = $computer->id;
                $stock->variant = $str;
                $stock->price = request()->input('price_' . $fieldKey, $collection['price']);
                $stock->sku = request()->input('sku_' . $fieldKey, $str);
                $stock->qty = request()->input('qty_' . $fieldKey, 0);
                $stock->image = request()->input('img_' . $fieldKey);
                $stock->storage = request()->input('storage_' . $fieldKey) ?: null;
                $stock->display = request()->input('display_' . $fieldKey) ?: null;
                $stock->ram = request()->input('ram_' . $fieldKey) ?: null;
                $stock->cpu = request()->input('cpu_' . $fieldKey) ?: null;
                $stock->chip = request()->input('chip_' . $fieldKey) ?: null;
                $stock->save();
            }

            // Rows added directly in the table via "+ Add Variant Row", not derived from any
            // color/attribute combination.
            foreach ($manualKeys as $fieldKey) {
                if (!request()->has('price_' . $fieldKey)) {
                    continue;
                }

                $storage = request()->input('storage_' . $fieldKey) ?: null;
                $display = request()->input('display_' . $fieldKey) ?: null;
                $ram = request()->input('ram_' . $fieldKey) ?: null;
                $cpu = request()->input('cpu_' . $fieldKey) ?: null;
                $chip = request()->input('chip_' . $fieldKey) ?: null;

                $variant = implode('-', array_filter([$storage, $display, $ram, $cpu, $chip]));
                $sku = request()->input('sku_' . $fieldKey) ?: null;

                $stock = new ComputerStock();
                $stock->computer_id = $computer->id;
                $stock->variant = $variant !== '' ? $variant : ($sku ?: 'variant');
                $stock->price = request()->input('price_' . $fieldKey, $collection['price']);
                $stock->sku = $sku ?: $stock->variant;
                $stock->qty = request()->input('qty_' . $fieldKey, 0);
                $stock->image = request()->input('img_' . $fieldKey);
                $stock->storage = $storage;
                $stock->display = $display;
                $stock->ram = $ram;
                $stock->cpu = $cpu;
                $stock->chip = $chip;
                $stock->is_manual = 1;
                $stock->save();
            }

            $computer->price = $computer->stocks()->min('price') ?? $computer->price;
            $computer->save();
        } else {
            $computer->is_variant = 0;
            $computer->save();

            $stock = new ComputerStock();
            $stock->computer_id = $computer->id;
            $stock->variant = null;
            $stock->sku = $collection['sku'] ?? null;
            $stock->price = $collection['price'];
            $stock->qty = $collection['current_stock'] ?? 0;
            $stock->save();
        }
    }
}
