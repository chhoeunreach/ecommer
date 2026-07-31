<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class FeaturedProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:edit_website_page']);
    }

    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => ['nullable', 'in:active,inactive'],
            'search' => ['nullable', 'string', 'max:100'],
        ]);

        $products = Product::query()
            ->with(['main_category', 'brand'])
            ->where('featured', 1)
            ->when(($validated['status'] ?? null) === 'active', fn ($query) => $query->where('published', 1))
            ->when(($validated['status'] ?? null) === 'inactive', fn ($query) => $query->where('published', 0))
            ->when($validated['search'] ?? null, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhereHas('stocks', fn ($stockQuery) => $stockQuery->where('sku', 'like', '%' . $search . '%'));
                });
            })
            ->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where('parent_id', 0)
            ->with('childrenCategories')
            ->get();

        return view('backend.promotion_and_offers.featured_products.index', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'search_key' => ['nullable', 'string', 'max:100'],
        ]);

        $products = Product::isApprovedPublished()
            ->where('auction_product', 0)
            ->when($validated['category'] ?? null, function ($query, $categoryId) {
                $query->whereHas('categories', fn ($categoryQuery) => $categoryQuery->where('categories.id', $categoryId));
            })
            ->when($validated['search_key'] ?? null, function ($query, $search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->limit(20)
            ->get();

        return view('backend.promotion_and_offers.featured_products.product_search', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_ids' => ['required', 'array', 'min:1'],
            'product_ids.*' => ['integer', 'distinct', 'exists:products,id'],
        ]);

        $newProductIds = Product::whereIn('id', $validated['product_ids'])
            ->where('featured', 0)
            ->pluck('id');

        if (Product::where('featured', 1)->count() + $newProductIds->count() > 12) {
            return response()->json([
                'message' => translate('You can select a maximum of 12 featured products.'),
            ], 422);
        }

        Product::whereIn('id', $newProductIds)->update(['featured' => 1]);
        Cache::forget('featured_products');

        return response()->json([
            'message' => translate('Featured products added successfully.'),
        ]);
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:products,id'],
        ]);

        Product::whereKey($validated['id'])->update(['featured' => 0]);
        Cache::forget('featured_products');

        return response()->json([
            'message' => translate('Product removed from Featured Products.'),
        ]);
    }

    public function bulkRemove(Request $request)
    {
        $validated = $request->validate([
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer', 'distinct', 'exists:products,id'],
        ]);

        Product::whereIn('id', $validated['ids'])->update(['featured' => 0]);
        Cache::forget('featured_products');

        return response()->json([
            'message' => translate('Selected products removed from Featured Products.'),
        ]);
    }
}
