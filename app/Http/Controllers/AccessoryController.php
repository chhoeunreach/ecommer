<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Accessory;

class AccessoryController extends Controller
{
    public function index(Request $request)
    {
        $accessories = Accessory::where('status', 1)->orderBy('created_at', 'desc')->paginate(12);
        
        return view('frontend.accessories.index', compact('accessories'));
    }

    public function show($id)
    {
        $accessory = Accessory::findOrFail($id);
        
        return view('frontend.accessories.show', compact('accessory'));
    }
}
