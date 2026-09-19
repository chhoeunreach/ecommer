<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class ProductCardStyleController extends Controller
{
    public const STYLES = ['default', 'minimalism', 'maximalism', 'flat', 'neumorphism', 'claymorphism'];

    public function __construct()
    {
        $this->middleware(['permission:website_appearance']);
    }

    public function edit()
    {
        $activeStyle = get_setting('product_card_style', 'default');

        return view('backend.website_settings.product_card_style', compact('activeStyle'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_card_style' => ['required', 'in:' . implode(',', self::STYLES)],
        ]);

        BusinessSetting::updateOrCreate(
            ['type' => 'product_card_style'],
            ['value' => $validated['product_card_style']]
        );

        flash(translate('Product card style updated successfully.'))->success();

        return back();
    }
}
