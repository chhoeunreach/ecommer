<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ipad;

class IpadController extends Controller
{
    public function index(Request $request)
    {
        $ipads = Ipad::where('status', 1)->orderBy('created_at', 'desc')->paginate(12);
        
        return view('frontend.ipads.index', compact('ipads'));
    }

    public function show($id)
    {
        $ipad = Ipad::findOrFail($id);
        
        return view('frontend.ipads.show', compact('ipad'));
    }
}
