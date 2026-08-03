<?php

namespace App\Http\Controllers;

use App\Models\ManualPaymentMethod;
use Illuminate\Http\Request;

class ManualPaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_all_manual_payment_methods'])->only('index');
        $this->middleware(['permission:add_manual_payment_method'])->only('create', 'store');
        $this->middleware(['permission:edit_manual_payment_method'])->only('edit', 'update');
        $this->middleware(['permission:delete_manual_payment_method'])->only('destroy');
    }

    /**
     * Routes in routes/offline_payment.php alias several other, unrelated
     * controllers (wallet recharge, seller/customer package payments, order
     * re-payment) to this class as a stand-in, since those controllers were
     * never delivered to this codebase. Only the manual-payment-method CRUD
     * below is actually implemented here; anything else 404s instead of
     * fatally erroring, matching how those routes behaved before this
     * controller existed.
     */
    public function __call($method, $parameters)
    {
        abort(404);
    }

    public function index()
    {
        $manual_payment_methods = ManualPaymentMethod::orderBy('created_at', 'desc')->paginate(15);
        return view('backend.setup_configurations.manual_payment_methods.index', compact('manual_payment_methods'));
    }

    public function create()
    {
        return view('backend.setup_configurations.manual_payment_methods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
        ]);

        $manual_payment_method = new ManualPaymentMethod;
        $manual_payment_method->heading = $request->heading;
        $manual_payment_method->description = $request->description;
        $manual_payment_method->photo = $request->photo;
        $manual_payment_method->bank_info = $this->bank_info_json($request);
        $manual_payment_method->save();

        flash(translate('Manual payment method has been added successfully'))->success();
        return redirect()->route('manual_payment_methods.index');
    }

    public function edit($id)
    {
        $manual_payment_method = ManualPaymentMethod::findOrFail($id);
        return view('backend.setup_configurations.manual_payment_methods.edit', compact('manual_payment_method'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'heading' => 'required|string|max:255',
        ]);

        $manual_payment_method = ManualPaymentMethod::findOrFail($id);
        $manual_payment_method->heading = $request->heading;
        $manual_payment_method->description = $request->description;
        if ($request->photo) {
            $manual_payment_method->photo = $request->photo;
        }
        $manual_payment_method->bank_info = $this->bank_info_json($request);
        $manual_payment_method->save();

        flash(translate('Manual payment method has been updated successfully'))->success();
        return redirect()->route('manual_payment_methods.index');
    }

    public function destroy($id)
    {
        $manual_payment_method = ManualPaymentMethod::findOrFail($id);
        $manual_payment_method->delete();

        flash(translate('Manual payment method has been deleted successfully'))->success();
        return redirect()->route('manual_payment_methods.index');
    }

    protected function bank_info_json(Request $request)
    {
        $bank_name = trim((string) $request->bank_name);
        $account_name = trim((string) $request->account_name);
        $account_number = trim((string) $request->account_number);
        $routing_number = trim((string) $request->routing_number);

        if ($bank_name == '' && $account_name == '' && $account_number == '' && $routing_number == '') {
            return null;
        }

        return json_encode([
            [
                'bank_name' => $bank_name,
                'account_name' => $account_name,
                'account_number' => $account_number,
                'routing_number' => $routing_number,
            ],
        ]);
    }
}
