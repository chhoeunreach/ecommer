<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Computer;

class ComputerController extends Controller
{
    public function index(Request $request)
    {
        $computers = Computer::where('status', 1)->orderBy('created_at', 'desc')->paginate(12);

        return view('frontend.computers.index', compact('computers'));
    }

    public function show($id)
    {
        $computer = Computer::with('stocks')->findOrFail($id);

        return view('frontend.computers.show', compact('computer'));
    }

    // Mirrors HomeController::variant_price() but for Computer's fixed
    // Storage/Display/RAM/CPU/Chip facets instead of Product's generic
    // choice_options + Storage/Code/Country/Condition combination.
    public function variantPrice(Request $request)
    {
        $computer = Computer::with('stocks')->find($request->id);

        if (!$computer) {
            return response()->json(['in_stock' => 0], 404);
        }

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
            return response()->json([
                'price' => single_price(0),
                'quantity' => 0,
                'variation' => $str,
                'max_limit' => 0,
                'in_stock' => 0,
                'sku' => 'N/A',
                'image' => '',
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
