<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\PreorderProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PreorderProductController extends Controller
{
    public function all_preorder_products(Request $request)
    {
        $preorder_products = PreorderProduct::where('is_published', 1);
        $preorder_products = filter_preorder_product($preorder_products);

        [$preorder_products, $query, $sort_by, $is_available] = $this->applyFilters($preorder_products, $request);

        $preorder_products = $preorder_products->paginate(12)->appends($request->query());
        $categories = Category::with('childrenCategories', 'coverImage')->where('level', 0)->orderBy('order_level', 'desc')->get();

        return view('preorder.frontend.all_preproducts', compact('preorder_products', 'categories', 'query', 'sort_by', 'is_available'));
    }

    public function listingByCategory(Request $request, $category_slug)
    {
        $category = Category::with('childrenCategories')->where('slug', $category_slug)->firstOrFail();
        $category_id = $category->id;

        $preorder_products = $category->preorderProducts()->where('is_published', 1);
        $preorder_products = filter_preorder_product($preorder_products);

        [$preorder_products, $query, $sort_by, $is_available] = $this->applyFilters($preorder_products, $request);

        $preorder_products = $preorder_products->paginate(12)->appends($request->query());

        return view('preorder.frontend.all_preproducts', compact('preorder_products', 'category', 'category_id', 'query', 'sort_by', 'is_available'));
    }

    private function applyFilters($preorder_products, Request $request)
    {
        $query = $request->keyword;
        $sort_by = $request->sort_by;
        $min_price = $request->min_price;
        $max_price = $request->max_price;
        $is_available = null;

        if ($request->has('is_available') && $request->is_available !== null) {
            $availability = $request->is_available;
            $currentDate = Carbon::now()->format('Y-m-d');

            $preorder_products->where(function ($q) use ($availability, $currentDate) {
                if ($availability == 1) {
                    $q->where('is_available', 1)->orWhere('available_date', '<=', $currentDate);
                } else {
                    $q->where(function ($q2) {
                        $q2->where('is_available', '!=', 1)->orWhereNull('is_available');
                    })->where(function ($q2) use ($currentDate) {
                        $q2->whereNull('available_date')->orWhere('available_date', '>', $currentDate);
                    });
                }
            });

            $is_available = $availability;
        }

        if ($min_price != null && $max_price != null) {
            $preorder_products->where('unit_price', '>=', $min_price)->where('unit_price', '<=', $max_price);
        }

        if ($query != null) {
            $preorder_products->where(function ($q) use ($query) {
                foreach (explode(' ', trim($query)) as $word) {
                    $q->where('product_name', 'like', '%' . $word . '%')
                        ->orWhere('tags', 'like', '%' . $word . '%')
                        ->orWhereHas('preorder_product_translations', function ($q2) use ($word) {
                            $q2->where('product_name', 'like', '%' . $word . '%');
                        });
                }
            });
        }

        switch ($sort_by) {
            case 'newest':
                $preorder_products->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $preorder_products->orderBy('created_at', 'asc');
                break;
            case 'price-asc':
                $preorder_products->orderBy('unit_price', 'asc');
                break;
            case 'price-desc':
                $preorder_products->orderBy('unit_price', 'desc');
                break;
            default:
                $preorder_products->orderBy('id', 'desc');
                break;
        }

        return [$preorder_products, $query, $sort_by, $is_available];
    }
}
