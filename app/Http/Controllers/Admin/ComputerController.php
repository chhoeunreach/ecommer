<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Computer;
use App\Models\ComputerVariant;
use App\Models\Brand;
use App\Models\Warranty;
use App\Services\ComputerStockService;
use AizPackages\CombinationGenerate\Services\CombinationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ComputerController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = null;
        $computers = Computer::orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $computers = $computers->where('name', 'like', '%' . $sort_search . '%');
        }

        $computers = $computers->paginate(15);

        return view('backend.computers.index', compact('computers', 'sort_search'));
    }

    public function create()
    {
        $brands = Brand::all();
        $warranties = Warranty::all();
        $attributes = Attribute::all();
        return view('backend.computers.create', compact('brands', 'warranties', 'attributes'));
    }

    protected function variantFields(Request $request)
    {
        $colors = json_encode([]);
        if ($request->has('colors') && is_array($request->colors) && count($request->colors) > 0) {
            $colors = json_encode(array_values($request->colors));
        } elseif ($request->has('variants') && is_array($request->variants)) {
            $variantColors = array_filter(array_unique(array_column($request->variants, 'color')));
            if (!empty($variantColors)) {
                $colorCodes = \App\Models\Color::whereIn('name', $variantColors)->pluck('code')->toArray();
                if (!empty($colorCodes)) {
                    $colors = json_encode(array_values($colorCodes));
                }
            }
        }

        $choice_options = [];
        if ($request->has('choice_no') && $request->choice_no) {
            foreach ($request->choice_no as $no) {
                $item = ['attribute_id' => $no, 'values' => []];
                $key = 'choice_options_' . $no;
                if ($request->has($key)) {
                    foreach ($request->$key as $eachValue) {
                        $item['values'][] = $eachValue;
                    }
                }
                $choice_options[] = $item;
            }
        }
        $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

        $attributes = ($request->has('choice_no') && $request->choice_no)
            ? json_encode(array_values($request->choice_no))
            : json_encode([]);

        return compact('colors', 'choice_options', 'attributes');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:computers,sku',
            'variants' => 'required|array|min:1',
            'variants.*.storage' => 'required|string|max:255',
            'variants.*.display' => 'required|string|max:255',
            'variants.*.ram' => 'required|string|max:255',
            'variants.*.cpu' => 'required|string|max:255',
            'variants.*.chip' => 'required|string|max:255',
            'variants.*.color' => 'nullable|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $storages = array_map(function ($v) {
            return strtolower(trim($v['storage'] ?? ''));
        }, $request->variants);

        if (count($storages) !== count(array_unique($storages))) {
            return back()->withInput()->withErrors(['variants' => translate('Duplicate Storage variant detected for the same computer.')]);
        }

        DB::beginTransaction();
        try {
            $totalStock = array_sum(array_map(function ($v) {
                return (int)($v['stock'] ?? 0);
            }, $request->variants));

            $computer = new Computer;
            $computer->name = $request->name;
            $computer->sku = $request->sku;
            $computer->stock = $totalStock;
            $computer->slug = Str::slug($request->name) . '-' . Str::random(5);
            $computer->description = $request->description;

            $firstVariantPrice = isset($request->variants[0]['price']) ? $request->variants[0]['price'] : ($request->price ?? 0);
            $computer->price = $firstVariantPrice;

            $computer->thumbnail_img = $request->thumbnail_img;
            $computer->gallery = $request->gallery;
            $computer->status = $request->has('published') ? 1 : 0;

            $computer->brand_id = $request->brand_id;
            $computer->has_warranty = $request->has('has_warranty') ? 1 : 0;
            if ($computer->has_warranty) {
                $computer->warranty_id = $request->warranty_id;
            }

            $computer->discount = $request->discount;
            $computer->discount_type = $request->discount_type;

            if ($request->date_range != null) {
                $date_var = explode(" to ", $request->date_range);
                $computer->discount_start_date = strtotime($date_var[0]);
                $computer->discount_end_date = strtotime($date_var[1]);
            }

            $computer->tags = json_encode(array_filter(explode(',', $request->tags ?? '')));
            $computer->meta_title = $request->meta_title;
            $computer->meta_description = $request->meta_description;
            $computer->meta_img = $request->meta_img;

            $variant = $this->variantFields($request);
            $computer->colors = $variant['colors'];
            $computer->choice_options = $variant['choice_options'];
            $computer->attributes = $variant['attributes'];
            $computer->is_variant = count($request->variants) > 0 ? 1 : 0;

            $computer->save();

            foreach ($request->variants as $vData) {
                $computer->computer_variants()->create([
                    'storage' => $vData['storage'],
                    'display' => $vData['display'],
                    'ram' => $vData['ram'],
                    'cpu' => $vData['cpu'],
                    'chip' => $vData['chip'],
                    'color' => $vData['color'] ?? null,
                    'price' => $vData['price'],
                    'stock' => $vData['stock'] ?? 0,
                ]);
            }

            (new ComputerStockService())->store($request->all(), $computer);

            DB::commit();

            flash(translate('Computer has been inserted successfully'))->success();

            Artisan::call('cache:clear');

            return redirect()->route('admin.computers.index');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $computer = Computer::with('computer_variants')->findOrFail($id);
        $brands = Brand::all();
        $warranties = Warranty::all();
        $attributes = Attribute::all();
        $tags = json_decode($computer->tags);
        return view('backend.computers.edit', compact('computer', 'brands', 'warranties', 'attributes', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:computers,sku,' . $id,
            'variants' => 'required|array|min:1',
            'variants.*.storage' => 'required|string|max:255',
            'variants.*.display' => 'required|string|max:255',
            'variants.*.ram' => 'required|string|max:255',
            'variants.*.cpu' => 'required|string|max:255',
            'variants.*.chip' => 'required|string|max:255',
            'variants.*.color' => 'nullable|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
        ]);

        $storages = array_map(function ($v) {
            return strtolower(trim($v['storage'] ?? ''));
        }, $request->variants);

        if (count($storages) !== count(array_unique($storages))) {
            return back()->withInput()->withErrors(['variants' => translate('Duplicate Storage variant detected for the same computer.')]);
        }

        DB::beginTransaction();
        try {
            $totalStock = array_sum(array_map(function ($v) {
                return (int)($v['stock'] ?? 0);
            }, $request->variants));

            $computer = Computer::findOrFail($id);
            $computer->name = $request->name;
            $computer->sku = $request->sku;
            $computer->stock = $totalStock;
            $computer->description = $request->description;

            $firstVariantPrice = isset($request->variants[0]['price']) ? $request->variants[0]['price'] : ($request->price ?? 0);
            $computer->price = $firstVariantPrice;

            $computer->thumbnail_img = $request->thumbnail_img;
            $computer->gallery = $request->gallery;
            $computer->status = $request->has('published') ? 1 : 0;

            $computer->brand_id = $request->brand_id;
            $computer->has_warranty = $request->has('has_warranty') ? 1 : 0;
            if ($computer->has_warranty) {
                $computer->warranty_id = $request->warranty_id;
            } else {
                $computer->warranty_id = null;
            }

            $computer->discount = $request->discount;
            $computer->discount_type = $request->discount_type;

            if ($request->date_range != null) {
                $date_var = explode(" to ", $request->date_range);
                $computer->discount_start_date = strtotime($date_var[0]);
                $computer->discount_end_date = strtotime($date_var[1]);
            } else {
                $computer->discount_start_date = null;
                $computer->discount_end_date = null;
            }

            if (is_array($request->tags)) {
                $computer->tags = json_encode($request->tags);
            } else {
                $computer->tags = json_encode(array_filter(explode(',', $request->tags ?? '')));
            }

            $computer->meta_title = $request->meta_title;
            $computer->meta_description = $request->meta_description;
            $computer->meta_img = $request->meta_img;

            $variant = $this->variantFields($request);
            $computer->colors = $variant['colors'];
            $computer->choice_options = $variant['choice_options'];
            $computer->attributes = $variant['attributes'];
            $computer->is_variant = count($request->variants) > 0 ? 1 : 0;

            $computer->save();

            // Sync variants in computer_variants table
            $computer->computer_variants()->delete();
            foreach ($request->variants as $vData) {
                $computer->computer_variants()->create([
                    'storage' => $vData['storage'],
                    'display' => $vData['display'],
                    'ram' => $vData['ram'],
                    'cpu' => $vData['cpu'],
                    'chip' => $vData['chip'],
                    'color' => $vData['color'] ?? null,
                    'price' => $vData['price'],
                    'stock' => $vData['stock'] ?? 0,
                ]);
            }

            $computer->stocks()->delete();
            (new ComputerStockService())->store($request->all(), $computer);

            DB::commit();

            flash(translate('Computer has been updated successfully'))->success();

            Artisan::call('cache:clear');

            return redirect()->route('admin.computers.index');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $computer = Computer::findOrFail($id);
        $computer->stocks()->delete();
        $computer->delete();

        flash(translate('Computer has been deleted successfully'))->success();

        Artisan::call('cache:clear');

        return redirect()->route('admin.computers.index');
    }

    public function update_status(Request $request)
    {
        $computer = Computer::findOrFail($request->id);
        $computer->status = $request->status;
        $computer->save();

        Artisan::call('cache:clear');

        return 1;
    }

    protected function buildCombinationOptions(Request $request)
    {
        $options = array();
        $optionLabels = array();
        $colors_active = 0;
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
            array_push($optionLabels, 'Color');
        }

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                if (isset($request[$name])) {
                    $data = array();
                    foreach ($request[$name] as $key => $item) {
                        array_push($data, $item);
                    }
                    array_push($options, $data);
                    $attribute = Attribute::find($no);
                    array_push($optionLabels, $attribute ? $attribute->name : 'Attribute');
                }
            }
        }

        return [$options, $colors_active, $optionLabels];
    }

    protected function attributeValueOptions()
    {
        $labels = ['Storage', 'Display', 'RAM', 'CPU', 'Chip'];
        $options = [];
        $attributeIdsByLabel = [];
        foreach ($labels as $label) {
            $attribute = Attribute::where('name', $label)->first();
            $options[$label] = $attribute
                ? AttributeValue::where('attribute_id', $attribute->id)->pluck('value')
                : collect();
            $attributeIdsByLabel[$label] = $attribute ? $attribute->id : null;
        }
        return [$options, $attributeIdsByLabel];
    }

    public function sku_combination(Request $request)
    {
        [$options, $colors_active, $optionLabels] = $this->buildCombinationOptions($request);

        $unit_price = $request->price;
        $product_name = $request->name;
        [$attributeValueOptions, $attributeIdsByLabel] = $this->attributeValueOptions();

        $combinations = (new CombinationService())->generate_combination($options);
        return view('backend.computers.sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'optionLabels', 'attributeValueOptions', 'attributeIdsByLabel'));
    }

    public function sku_combination_edit(Request $request)
    {
        $computer = Computer::findOrFail($request->id);

        [$options, $colors_active, $optionLabels] = $this->buildCombinationOptions($request);

        $unit_price = $request->price;
        $product_name = $request->name;
        [$attributeValueOptions, $attributeIdsByLabel] = $this->attributeValueOptions();

        $combinations = (new CombinationService())->generate_combination($options);
        $manualStocks = $computer->stocks()->where('is_manual', 1)->get();

        return view('backend.computers.sku_combinations_edit', compact('combinations', 'unit_price', 'colors_active', 'product_name', 'computer', 'optionLabels', 'attributeValueOptions', 'attributeIdsByLabel', 'manualStocks'));
    }
}
