<?php

namespace App\Services;

use AizPackages\CombinationGenerate\Services\CombinationService;
use App\Models\ProductStock;
use App\Utility\ProductUtility;
use Illuminate\Support\Facades\Log;

class ProductStockService
{
    public function store(array $data, $product)
    {
        //Log::info('Product Stock Request:', $data);
        $collection = collect($data);

        $options = ProductUtility::get_attribute_options($collection);
        
        //Generates the combinations of customer choice options
        $combinations = (new CombinationService())->generate_combination($options);
        
        $variant = '';
        if (count($combinations) > 0) {
            $product->variant_product = 1;
            $product->save();
            foreach ($combinations as $key => $combination) {
                $str = ProductUtility::get_combination_string($combination, $collection);
                $fieldKey = md5($str);
                
                // If the user removed this variant row from the UI, its inputs won't be in the request.
                if (!request()->has('price_' . $fieldKey) && !request()->has('storage_' . $fieldKey)) {
                    continue;
                }

                $storageInput = request()->input('storage_' . $fieldKey, []);
                $storageValues = is_array($storageInput)
                    ? $storageInput
                    : preg_split('/[,\r\n]+/', (string) $storageInput);
                $storageValues = array_values(array_unique(array_filter(array_map('trim', $storageValues))));
                $storageValues = count($storageValues) > 0 ? $storageValues : [null];
                $quantityInput = request()->input('qty_' . $fieldKey, 0);
                $priceInput = request()->input('price_' . $fieldKey, $collection['unit_price']);

                foreach ($storageValues as $storage) {
                    $storageKey = $storage ? str_replace(' ', '', $storage) : null;
                    $quantity = is_array($quantityInput) ? ($quantityInput[$storage] ?? 0) : $quantityInput;
                    $price = is_array($priceInput) ? ($priceInput[$storage] ?? $collection['unit_price']) : $priceInput;

                    $product_stock = new ProductStock();
                    $product_stock->product_id = $product->id;
                    $product_stock->variant = $storageKey ? $str . '-' . $storageKey : $str;
                    $product_stock->price = $price;
                    $product_stock->sku = request()->input('sku_' . $fieldKey, $str) . ($storageKey ? '-' . $storageKey : '');
                    $product_stock->code = request()->input('code_' . $fieldKey);
                    $product_stock->country = request()->input('country_' . $fieldKey);
                    $product_stock->condition = request()->input('condition_' . $fieldKey);
                    $product_stock->storage = $storage;
                    $product_stock->qty = $quantity;
                    $product_stock->image = request()->input('img_' . $fieldKey);
                    $product_stock->save();
                }
            }

            $product->unit_price = $product->stocks()->min('price') ?? 0;
            $product->save();
        } else {
            $product->variant_product = 0;
            $product->save();
            unset($collection['colors_active'], $collection['colors'], $collection['choice_no']);
            $qty = $collection['current_stock'];
            $price = $collection['unit_price'];
            unset($collection['current_stock']);

            $data = $collection->merge(compact('variant', 'qty', 'price'))->toArray();
            
            ProductStock::create($data);
        }
    }

    public function product_duplicate_store($product_stocks , $product_new)
    {
        foreach ($product_stocks as $key => $stock) {
            $product_stock              = new ProductStock;
            $product_stock->product_id  = $product_new->id;
            $product_stock->variant     = $stock->variant;
            $product_stock->price       = $stock->price;
            $product_stock->sku         = null;
            $product_stock->qty         = $stock->qty;
            $product_stock->save();
        }
    }
}
