<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ipad;
use App\Models\IpadStock;
use App\Models\Brand;
use App\Models\Warranty;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class IpadController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = null;
        $ipads = Ipad::orderBy('created_at', 'desc');

        if ($request->has('search')) {
            $sort_search = $request->search;
            $ipads = $ipads->where('name', 'like', '%' . $sort_search . '%');
        }

        $ipads = $ipads->paginate(15);
        
        return view('backend.ipads.index', compact('ipads', 'sort_search'));
    }

    public function create()
    {
        $brands = Brand::all();
        $warranties = Warranty::all();
        return view('backend.ipads.create', compact('brands', 'warranties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'thumbnail_img' => 'required',
            'price' => 'nullable|numeric|min:0',
        ]);

        $ipad = new Ipad;
        $ipad->name = $request->name;
        $ipad->slug = Str::slug($request->name) . '-' . Str::random(5);
        $ipad->description = $request->description;
        $ipad->price = $request->price ?? 0;
        $ipad->thumbnail_img = $request->thumbnail_img;
        $ipad->gallery = $request->gallery;
        $ipad->status = $request->has('published') ? 1 : 0;

        $ipad->brand_id = $request->brand_id;
        $ipad->has_warranty = $request->has('has_warranty') ? 1 : 0;
        if($ipad->has_warranty){
            $ipad->warranty_id = $request->warranty_id;
        }

        $ipad->discount = $request->discount;
        $ipad->discount_type = $request->discount_type;
        
        if ($request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
            $ipad->discount_start_date = strtotime($date_var[0]);
            $ipad->discount_end_date = strtotime($date_var[1]);
        }
        
        $ipad->tags = json_encode(array_filter(explode(',', $request->tags)));
        $ipad->meta_title = $request->meta_title;
        $ipad->meta_description = $request->meta_description;
        $ipad->meta_img = $request->meta_img;

        $ipad->save();
        $this->syncStock($ipad);

        Artisan::call('cache:clear');

        return response()->json([
            'success' => true,
            'message' => translate('iPad has been inserted successfully'),
            'redirect' => route('admin.ipads.index'),
        ]);
    }

    public function edit($id)
    {
        $ipad = Ipad::findOrFail($id);
        $brands = Brand::all();
        $warranties = Warranty::all();
        $tags = json_decode($ipad->tags);
        return view('backend.ipads.edit', compact('ipad', 'brands', 'warranties', 'tags'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'thumbnail_img' => 'required',
            'price' => 'nullable|numeric|min:0',
        ]);

        $ipad = Ipad::findOrFail($id);
        $ipad->name = $request->name;
        $ipad->description = $request->description;
        $ipad->price = $request->price ?? 0;
        $ipad->thumbnail_img = $request->thumbnail_img;
        $ipad->gallery = $request->gallery;
        $ipad->status = $request->has('published') ? 1 : 0;

        $ipad->brand_id = $request->brand_id;
        $ipad->has_warranty = $request->has('has_warranty') ? 1 : 0;
        if($ipad->has_warranty){
            $ipad->warranty_id = $request->warranty_id;
        } else {
            $ipad->warranty_id = null;
        }

        $ipad->discount = $request->discount;
        $ipad->discount_type = $request->discount_type;
        
        if ($request->date_range != null) {
            $date_var = explode(" to ", $request->date_range);
            $ipad->discount_start_date = strtotime($date_var[0]);
            $ipad->discount_end_date = strtotime($date_var[1]);
        } else {
            $ipad->discount_start_date = null;
            $ipad->discount_end_date = null;
        }
        
        if(is_array($request->tags)){
            $ipad->tags = json_encode($request->tags);
        } else {
            $ipad->tags = json_encode(array_filter(explode(',', $request->tags)));
        }
        
        $ipad->meta_title = $request->meta_title;
        $ipad->meta_description = $request->meta_description;
        $ipad->meta_img = $request->meta_img;

        $ipad->save();
        $this->syncStock($ipad);

        Artisan::call('cache:clear');

        return response()->json([
            'success' => true,
            'message' => translate('iPad has been updated successfully'),
            'redirect' => route('admin.ipads.index'),
        ]);
    }

    public function destroy($id)
    {
        $ipad = Ipad::findOrFail($id);
        $ipad->stocks()->delete();
        $ipad->delete();

        flash(translate('iPad has been deleted successfully'))->success();

        Artisan::call('cache:clear');

        return redirect()->route('admin.ipads.index');
    }

    public function update_status(Request $request)
    {
        $ipad = Ipad::findOrFail($request->id);
        $ipad->status = $request->status;
        $ipad->save();

        Artisan::call('cache:clear');

        return 1;
    }

    private function syncStock(Ipad $ipad)
    {
        IpadStock::updateOrCreate(
            ['ipad_id' => $ipad->id, 'variant' => null],
            [
                'price' => $ipad->price,
                'qty' => 999999,
                'image' => $ipad->thumbnail_img,
            ]
        );
    }
}
