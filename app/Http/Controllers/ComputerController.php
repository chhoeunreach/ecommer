<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Computer;

class ComputerController extends Controller
{
    public function index(Request $request)
    {
        $computers = Computer::with(['brand', 'computer_variants', 'stocks'])
            ->where('status', 1)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('frontend.computers.index', compact('computers'));
    }

    public function show($id)
    {
        $computer = Computer::with(['stocks', 'computer_variants', 'brand', 'warranty'])->findOrFail($id);

        return view('frontend.computers.show', compact('computer'));
    }

    public function variantPrice(Request $request)
    {
        $computer = Computer::with(['stocks', 'computer_variants'])->find($request->id);

        if (!$computer) {
            return response()->json(['in_stock' => 0], 404);
        }

        $computer_variants = $computer->computer_variants;

        if ($computer_variants->isNotEmpty()) {
            $changedField = $request->input('changed_field');
            $query = clone $computer_variants;

            if ($changedField && $request->filled($changedField)) {
                $val = $request->input($changedField);
                $matchedChanged = $query->where($changedField, $val);
                if ($matchedChanged->isNotEmpty()) {
                    $query = $matchedChanged;
                }
            }

            if ($request->filled('color')) {
                $colorInput = $request->input('color');
                $matchedColor = $query->filter(function ($v) use ($colorInput) {
                    return strcasecmp($v->color, $colorInput) === 0;
                });
                if ($matchedColor->isNotEmpty()) {
                    $query = $matchedColor;
                }
            }

            foreach (['storage', 'display', 'ram', 'cpu', 'chip'] as $field) {
                if ($field !== $changedField && $request->filled($field)) {
                    $val = $request->input($field);
                    $matchedField = $query->where($field, $val);
                    if ($matchedField->isNotEmpty()) {
                        $query = $matchedField;
                    }
                }
            }

            $variant = $query->first() ?: $computer_variants->first();

            $rawPrice = $variant->price > 0 ? $variant->price : $computer->price;
            $price = \App\Utility\CartUtility::discount_calculation($computer, $rawPrice);
            $quantity = (int)$variant->stock;
            $in_stock = $quantity >= 1 ? 1 : 0;

            $variation = implode(' / ', array_filter([$variant->color, $variant->storage, $variant->ram, $variant->chip]));

            return response()->json([
                'price' => single_price($price * max((int) $request->quantity, 1)),
                'quantity' => $quantity,
                'variation' => $variation ?: $computer->name,
                'max_limit' => $quantity,
                'in_stock' => $in_stock,
                'sku' => $computer->sku ?? 'N/A',
                'image' => uploaded_asset($computer->thumbnail_img),
                'color' => $variant->color ?? '',
                'storage' => $variant->storage ?? '',
                'display' => $variant->display ?? '',
                'ram' => $variant->ram ?? '',
                'cpu' => $variant->cpu ?? '',
                'chip' => $variant->chip ?? '',
            ]);
        }

        // Fallback for legacy computer stocks
        $str = '';
        if ($request->filled('color')) {
            $str = $request->input('color');
        }

        $facetFields = ['storage', 'display', 'ram', 'cpu', 'chip'];
        $product_stock = $computer->stocks->where('variant', $str)->first();

        if ($request->hasAny($facetFields) || $request->filled('color')) {
            $changedField = $request->input('changed_field', 'color');
            $order = array_unique(array_merge([$changedField], ['color'], $facetFields));

            $candidates = $computer->stocks->filter(
                fn ($stock) => $stock->variant === $str || str_starts_with((string) $stock->variant, $str . '-')
            );
            if ($candidates->isEmpty()) {
                $candidates = $computer->stocks;
            }

            foreach ($order as $field) {
                $value = $request->input($field);
                if (!$value) {
                    continue;
                }
                $narrowed = $field === 'color'
                    ? $candidates->filter(fn ($stock) => $stock->variant === $value || str_starts_with((string) $stock->variant, $value . '-'))
                    : $candidates->where($field, $value);
                if ($narrowed->isNotEmpty()) {
                    $candidates = $narrowed;
                }
            }

            if ($candidates->isNotEmpty()) {
                $product_stock = $candidates->first();
                $str = $product_stock->variant;
            }
        }

        if (!$product_stock) {
            $basePrice = \App\Utility\CartUtility::discount_calculation($computer, $computer->price);
            return response()->json([
                'price' => single_price($basePrice * max((int) $request->quantity, 1)),
                'quantity' => $computer->stock,
                'variation' => $computer->name,
                'max_limit' => $computer->stock,
                'in_stock' => $computer->stock >= 1 ? 1 : 0,
                'sku' => $computer->sku ?? 'N/A',
                'image' => uploaded_asset($computer->thumbnail_img),
                'storage' => '', 'display' => '', 'ram' => '', 'cpu' => '', 'chip' => '',
            ]);
        }

        $price = \App\Utility\CartUtility::discount_calculation($computer, $product_stock->price);
        $quantity = $product_stock->qty;
        $in_stock = $quantity >= 1 ? 1 : 0;

        $resolvedColor = collect(json_decode($computer->colors ?? '[]', true) ?: [])
            ->map(fn ($color) => get_single_color_name($color))
            ->first(fn ($color) => $product_stock->variant === $color || str_starts_with((string) $product_stock->variant, $color . '-'));

        return response()->json([
            'price' => single_price($price * max((int) $request->quantity, 1)),
            'quantity' => $quantity,
            'variation' => $str,
            'max_limit' => $quantity,
            'in_stock' => $in_stock,
            'sku' => $product_stock->sku ?? 'N/A',
            'image' => $product_stock->image ? uploaded_asset($product_stock->image) : '',
            'color' => $resolvedColor ?? '',
            'storage' => $product_stock->storage ?? '',
            'display' => $product_stock->display ?? '',
            'ram' => $product_stock->ram ?? '',
            'cpu' => $product_stock->cpu ?? '',
            'chip' => $product_stock->chip ?? '',
        ]);
    }
}
