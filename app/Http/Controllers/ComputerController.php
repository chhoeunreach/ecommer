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
        $computer = Computer::findOrFail($id);

        return view('frontend.computers.show', compact('computer'));
    }
}
