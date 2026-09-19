<?php

namespace App\Http\Controllers;

use App\Models\PreOrderRequest;
use App\Models\UpcomingProduct;
use Illuminate\Http\Request;

class PreOrderRequestController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'upcoming_product_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'quantity' => 'nullable|integer|min:1|max:100',
            'note' => 'nullable|string|max:1000',
        ]);

        $upcoming_product = UpcomingProduct::active()->findOrFail($request->upcoming_product_id);

        // Pre-orders whose closing date has passed fall back to "notify me".
        $type = $upcoming_product->isPreOrderOpen() ? 'pre_order' : 'notify';

        PreOrderRequest::create([
            'upcoming_product_id' => $upcoming_product->id,
            'user_id' => auth()->id(),
            'type' => $type,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'quantity' => $type === 'pre_order' ? ($request->quantity ?? 1) : 1,
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => $type === 'pre_order'
                ? translate('Your pre-order has been received. Our team will contact you soon.')
                : translate('Thank you! We will notify you when this product is available.'),
        ]);
    }
}
