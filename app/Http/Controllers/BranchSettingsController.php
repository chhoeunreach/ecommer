<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:website_appearance']);
    }

    public function edit()
    {
        $branches = json_decode(get_setting('anlyn_branches', '[]'), true);
        $branches = is_array($branches) ? $branches : [];

        return view('backend.website_settings.branches', compact('branches'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_image' => ['nullable', 'integer'],
            'hero_eyebrow' => ['nullable', 'string', 'max:120'],
            'hero_title' => ['nullable', 'string', 'max:120'],
            'hero_description' => ['nullable', 'string', 'max:500'],
            'section_description' => ['nullable', 'string', 'max:500'],
            'hero_eyebrow_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'hero_eyebrow_size' => ['required', 'integer', 'min:8', 'max:30'],
            'hero_title_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'hero_title_accent_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'hero_title_size' => ['required', 'integer', 'min:36', 'max:140'],
            'hero_description_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'hero_description_size' => ['required', 'integer', 'min:10', 'max:30'],
            'section_title_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'section_title_size' => ['required', 'integer', 'min:30', 'max:100'],
            'section_description_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'section_description_size' => ['required', 'integer', 'min:10', 'max:26'],
            'hero_title_font' => ['required', 'in:public-sans,georgia,times,arial'],
            'hero_body_font' => ['required', 'in:public-sans,georgia,times,arial'],
            'section_title_font' => ['required', 'in:public-sans,georgia,times,arial'],
            'section_body_font' => ['required', 'in:public-sans,georgia,times,arial'],
            'branches' => ['required', 'array', 'min:1', 'max:12'],
            'branches.*.brand' => ['required', 'in:ANLYN POP,ANLYN BLOOM'],
            'branches.*.name' => ['required', 'string', 'max:120'],
            'branches.*.city' => ['nullable', 'string', 'max:100'],
            'branches.*.image' => ['nullable', 'integer'],
            'branches.*.address' => ['required', 'string', 'max:500'],
            'branches.*.hours' => ['required', 'string', 'max:250'],
            'branches.*.phone' => ['required', 'string', 'max:80'],
            'branches.*.map' => ['nullable', 'url', 'max:1000'],
            'branches.*.facebook' => ['nullable', 'url', 'max:1000'],
            'branches.*.instagram' => ['nullable', 'url', 'max:1000'],
            'branches.*.active' => ['nullable', 'boolean'],
        ]);

        $branches = collect($validated['branches'])
            ->map(function (array $branch) {
                return [
                    'brand' => $branch['brand'],
                    'name' => trim($branch['name']),
                    'city' => trim($branch['city'] ?? ''),
                    'image' => $branch['image'] ?? null,
                    'address' => trim($branch['address']),
                    'hours' => trim($branch['hours']),
                    'phone' => trim($branch['phone']),
                    'map' => trim($branch['map'] ?? ''),
                    'facebook' => trim($branch['facebook'] ?? ''),
                    'instagram' => trim($branch['instagram'] ?? ''),
                    'active' => (bool) ($branch['active'] ?? false),
                ];
            })
            ->values()
            ->all();

        $settings = [
            'anlyn_branches_hero_image' => $validated['hero_image'] ?? null,
            'anlyn_branches_hero_eyebrow' => trim($validated['hero_eyebrow'] ?? ''),
            'anlyn_branches_hero_title' => trim($validated['hero_title'] ?? ''),
            'anlyn_branches_hero_description' => trim($validated['hero_description'] ?? ''),
            'anlyn_branches_section_description' => trim($validated['section_description'] ?? ''),
            'anlyn_branches_hero_eyebrow_color' => $validated['hero_eyebrow_color'],
            'anlyn_branches_hero_eyebrow_size' => $validated['hero_eyebrow_size'],
            'anlyn_branches_hero_title_color' => $validated['hero_title_color'],
            'anlyn_branches_hero_title_accent_color' => $validated['hero_title_accent_color'],
            'anlyn_branches_hero_title_size' => $validated['hero_title_size'],
            'anlyn_branches_hero_description_color' => $validated['hero_description_color'],
            'anlyn_branches_hero_description_size' => $validated['hero_description_size'],
            'anlyn_branches_section_title_color' => $validated['section_title_color'],
            'anlyn_branches_section_title_size' => $validated['section_title_size'],
            'anlyn_branches_section_description_color' => $validated['section_description_color'],
            'anlyn_branches_section_description_size' => $validated['section_description_size'],
            'anlyn_branches_hero_title_font' => $validated['hero_title_font'],
            'anlyn_branches_hero_body_font' => $validated['hero_body_font'],
            'anlyn_branches_section_title_font' => $validated['section_title_font'],
            'anlyn_branches_section_body_font' => $validated['section_body_font'],
            'anlyn_branches' => json_encode($branches, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ];

        DB::transaction(function () use ($settings) {
            foreach ($settings as $type => $value) {
                BusinessSetting::updateOrCreate(['type' => $type], ['value' => $value]);
            }
        });

        flash(translate('Branch page settings updated successfully.'))->success();

        return back();
    }
}
