<?php

namespace App\Http\Controllers;

use App\Http\Requests\DynamicPopupRequest;
use App\Models\DynamicPopup;
use Illuminate\Http\Request;

class DynamicPopupController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_dynamic_popups'])->only('index');
        $this->middleware(['permission:add_dynamic_popups'])->only(['create', 'store']);
        $this->middleware(['permission:edit_dynamic_popups'])->only(['edit', 'update']);
        $this->middleware(['permission:delete_dynamic_popups'])->only(['destroy', 'bulk_dynamic_popup_delete']);
        $this->middleware(['permission:publish_dynamic_popups'])->only('update_status');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search = null;
        $dynamic_popups = DynamicPopup::orderBy('id', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $dynamic_popups = $dynamic_popups->where('title', 'like', '%'.$sort_search.'%');
        }
        $dynamic_popups = $dynamic_popups->paginate(15);
        return view('backend.marketing.dynamic_popup.index', compact('dynamic_popups', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.marketing.dynamic_popup.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DynamicPopupRequest $request)
    {
        DynamicPopup::create($request->validated());
        flash(translate('Dynamic Popup has been inserted successfully'))->success();
        return redirect()->route('dynamic-popups.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(DynamicPopup $dynamic_popup)
    {
        return view('backend.marketing.dynamic_popup.edit', compact('dynamic_popup'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DynamicPopupRequest $request, DynamicPopup $dynamic_popup)
    {
        $data = $request->validated();

        if ($dynamic_popup->id == 1) {
            $data['show_subscribe_form'] = $request->boolean('show_subscribe_form') ? 'on' : null;
        }

        $dynamic_popup->update($data);
        flash(translate('Dynamic Popup has been updated successfully'))->success();
        return redirect()->route('dynamic-popups.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (in_array((int) $id, [1, 100], true)) {
            flash(translate('This Dynamic Popup cannot be deleted'))->error();
            return redirect()->route('dynamic-popups.index');
        }
        DynamicPopup::destroy($id);
        flash(translate('Dynamic Popup has been deleted successfully'))->success();
        return redirect()->route('dynamic-popups.index');
    }
    
    public function bulk_dynamic_popup_delete(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'array'],
            'id.*' => ['integer', 'distinct', 'exists:dynamic_popups,id'],
        ]);

        DynamicPopup::whereIn('id', $validated['id'])
            ->whereNotIn('id', [1, 100])
            ->delete();

        return 1;
    }
    
    public function update_status(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:dynamic_popups,id'],
            'status' => ['required', 'boolean'],
        ]);

        $dynamic_popup = DynamicPopup::findOrFail($validated['id']);
        $dynamic_popup->status = (int) $validated['status'];
        if($dynamic_popup->save()){
            return 1;
        }
        return 0;
    }
}
