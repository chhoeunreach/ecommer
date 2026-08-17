<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accessory;
use App\Models\Brand;
use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class AccessoryController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = null;
        $accessories = Accessory::orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $accessories = $accessories->where('name', 'like', '%' . $sort_search . '%');
        }

        $accessories = $accessories->paginate(15);
        
        return view('backend.accessories.index', compact('accessories', 'sort_search'));
    }

    public function create()
    {
        $brands = Brand::all();
        $warranties = Warranty::all();
        return view('backend.accessories.create', compact('brands', 'warranties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $accessory = new Accessory;
        $accessory->name = $request->name;
        $accessory->slug = Str::slug($request->name) . '-' . Str::random(5);
        $accessory->description = $request->description;
        $accessory->price = $request->price;
        $accessory->thumbnail_img = $request->thumbnail_img;
        $accessory->gallery = $request->gallery;
        $accessory->status = $request->has('published') ? 1 : 0;
        
        $accessory->brand_id = $request->brand_id;
        $accessory->has_warranty = $request->has('has_warranty') ? 1 : 0;
        if($accessory->has_warranty){
            $accessory->warranty_id = $request->warranty_id;
        }

        $accessory->discount = $request->discount;
        $accessory->discount_type = $request->discount_type;
        
        if ($request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
            $accessory->discount_start_date = strtotime($date_var[0]);
            $accessory->discount_end_date = strtotime($date_var[1]);
        }
        
        $accessory->tags = json_encode(array_filter(explode(',', $request->tags)));
        $accessory->meta_title = $request->meta_title;
        $accessory->meta_description = $request->meta_description;
        $accessory->meta_img = $request->meta_img;

        $accessory->save();

        flash(translate('Accessory has been inserted successfully'))->success();
        
        Artisan::call('cache:clear');

        return redirect()->route('admin.accessories.index');
    }

    public function edit($id)
    {
        $accessory = Accessory::findOrFail($id);
        $brands = Brand::all();
        $warranties = Warranty::all();
        $tags = json_decode($accessory->tags);
        return view('backend.accessories.edit', compact('accessory', 'brands', 'warranties', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $accessory = Accessory::findOrFail($id);
        $accessory->name = $request->name;
        $accessory->description = $request->description;
        $accessory->price = $request->price;
        $accessory->thumbnail_img = $request->thumbnail_img;
        $accessory->gallery = $request->gallery;
        $accessory->status = $request->has('published') ? 1 : 0;
        
        $accessory->brand_id = $request->brand_id;
        $accessory->has_warranty = $request->has('has_warranty') ? 1 : 0;
        if($accessory->has_warranty){
            $accessory->warranty_id = $request->warranty_id;
        } else {
            $accessory->warranty_id = null;
        }

        $accessory->discount = $request->discount;
        $accessory->discount_type = $request->discount_type;
        
        if ($request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
            $accessory->discount_start_date = strtotime($date_var[0]);
            $accessory->discount_end_date = strtotime($date_var[1]);
        } else {
            $accessory->discount_start_date = null;
            $accessory->discount_end_date = null;
        }
        
        // Tags might come as array if we use aiz-tag-input differently, but typically it sends as comma-separated or array
        if(is_array($request->tags)){
            $accessory->tags = json_encode($request->tags);
        } else {
            $accessory->tags = json_encode(array_filter(explode(',', $request->tags)));
        }
        
        $accessory->meta_title = $request->meta_title;
        $accessory->meta_description = $request->meta_description;
        $accessory->meta_img = $request->meta_img;

        $accessory->save();

        flash(translate('Accessory has been updated successfully'))->success();

        Artisan::call('cache:clear');

        return redirect()->route('admin.accessories.index');
    }

    public function destroy($id)
    {
        $accessory = Accessory::findOrFail($id);
        $accessory->delete();

        flash(translate('Accessory has been deleted successfully'))->success();

        Artisan::call('cache:clear');

        return redirect()->route('admin.accessories.index');
    }

    public function update_status(Request $request)
    {
        $accessory = Accessory::findOrFail($request->id);
        $accessory->status = $request->status;
        $accessory->save();

        Artisan::call('cache:clear');

        return 1;
    }
}
