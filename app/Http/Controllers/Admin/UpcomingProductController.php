<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\UpcomingProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UpcomingProductController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = $request->search;
        $type = $request->type;

        $upcoming_products = UpcomingProduct::withCount([
                'requests',
                'requests as pending_requests_count' => fn ($q) => $q->where('status', 'pending'),
            ])
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc');

        if ($sort_search) {
            $upcoming_products->where('name', 'like', '%' . $sort_search . '%');
        }
        if (in_array($type, [UpcomingProduct::TYPE_PRE_ORDER, UpcomingProduct::TYPE_COMING_SOON])) {
            $upcoming_products->where('type', $type);
        }

        $upcoming_products = $upcoming_products->paginate(15);

        return view('backend.upcoming_products.index', compact('upcoming_products', 'sort_search', 'type'));
    }

    public function create()
    {
        $brands = Brand::all();
        return view('backend.upcoming_products.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        $upcoming_product = new UpcomingProduct;
        $upcoming_product->slug = Str::slug($request->name) . '-' . Str::random(5);
        $this->fill($upcoming_product, $request);
        $upcoming_product->save();

        Cache::forget('home_upcoming_products');

        return response()->json([
            'success' => true,
            'message' => translate('Product has been inserted successfully'),
            'redirect' => route('admin.upcoming-products.index'),
        ]);
    }

    public function edit($id)
    {
        $upcoming_product = UpcomingProduct::findOrFail($id);
        $brands = Brand::all();
        return view('backend.upcoming_products.edit', compact('upcoming_product', 'brands'));
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request);

        $upcoming_product = UpcomingProduct::findOrFail($id);
        $this->fill($upcoming_product, $request);
        $upcoming_product->save();

        Cache::forget('home_upcoming_products');

        return response()->json([
            'success' => true,
            'message' => translate('Product has been updated successfully'),
            'redirect' => route('admin.upcoming-products.index'),
        ]);
    }

    public function destroy($id)
    {
        $upcoming_product = UpcomingProduct::findOrFail($id);
        $upcoming_product->requests()->delete();
        $upcoming_product->delete();

        Cache::forget('home_upcoming_products');
        flash(translate('Product has been deleted successfully'))->success();

        return redirect()->route('admin.upcoming-products.index');
    }

    public function update_status(Request $request)
    {
        $upcoming_product = UpcomingProduct::findOrFail($request->id);
        $upcoming_product->status = $request->status;
        $upcoming_product->save();

        Cache::forget('home_upcoming_products');

        return 1;
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'type' => 'required|in:pre_order,coming_soon',
            'name' => 'required|string|max:255',
            'thumbnail_img' => 'required',
            'short_description' => 'nullable|string|max:500',
            'price' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'release_date' => 'nullable|date',
            'preorder_end_date' => 'nullable|date',
            'badge_text' => 'nullable|string|max:50',
            'external_link' => 'nullable|url|max:255',
            'sort_order' => 'nullable|integer',
        ]);
    }

    private function fill(UpcomingProduct $upcoming_product, Request $request)
    {
        $upcoming_product->type = $request->type;
        $upcoming_product->name = $request->name;
        $upcoming_product->short_description = $request->short_description;
        $upcoming_product->description = $request->description;
        $upcoming_product->thumbnail_img = $request->thumbnail_img;
        $upcoming_product->gallery = $request->gallery;
        $upcoming_product->brand_id = $request->brand_id ?: null;
        $upcoming_product->price = $request->price;
        $upcoming_product->deposit_amount = $request->type === UpcomingProduct::TYPE_PRE_ORDER ? $request->deposit_amount : null;
        $upcoming_product->release_date = $request->release_date ?: null;
        $upcoming_product->preorder_end_date = $request->type === UpcomingProduct::TYPE_PRE_ORDER && $request->preorder_end_date ? $request->preorder_end_date : null;
        $upcoming_product->badge_text = $request->badge_text;
        $upcoming_product->external_link = $request->external_link;
        $upcoming_product->sort_order = $request->sort_order ?? 0;
        $upcoming_product->status = $request->has('published') ? 1 : 0;
    }
}
