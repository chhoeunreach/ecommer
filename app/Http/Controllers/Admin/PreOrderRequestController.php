<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreOrderRequest;
use App\Models\UpcomingProduct;
use Illuminate\Http\Request;

class PreOrderRequestController extends Controller
{
    public function index(Request $request)
    {
        $sort_search = $request->search;
        $status = $request->status;
        $type = $request->type;
        $product_id = $request->product_id;

        $pre_order_requests = PreOrderRequest::with('upcomingProduct')->latest();

        if ($sort_search) {
            $pre_order_requests->where(function ($q) use ($sort_search) {
                $q->where('name', 'like', '%' . $sort_search . '%')
                    ->orWhere('phone', 'like', '%' . $sort_search . '%')
                    ->orWhere('email', 'like', '%' . $sort_search . '%');
            });
        }
        if (in_array($status, PreOrderRequest::STATUSES)) {
            $pre_order_requests->where('status', $status);
        }
        if (in_array($type, ['pre_order', 'notify'])) {
            $pre_order_requests->where('type', $type);
        }
        if ($product_id) {
            $pre_order_requests->where('upcoming_product_id', $product_id);
        }

        $pre_order_requests = $pre_order_requests->paginate(20);
        $upcoming_products = UpcomingProduct::orderBy('name')->get(['id', 'name']);

        return view('backend.pre_order_requests.index', compact('pre_order_requests', 'upcoming_products', 'sort_search', 'status', 'type', 'product_id'));
    }

    public function update_status(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'status' => 'required|in:' . implode(',', PreOrderRequest::STATUSES),
        ]);

        $pre_order_request = PreOrderRequest::findOrFail($request->id);
        $pre_order_request->status = $request->status;
        $pre_order_request->save();

        return 1;
    }

    public function destroy($id)
    {
        PreOrderRequest::findOrFail($id)->delete();

        flash(translate('Request has been deleted successfully'))->success();

        return back();
    }
}
