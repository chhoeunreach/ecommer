<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusinessSetting;
use App\Support\PromoVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VideoGridController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:edit_website_page']);
    }

    public function edit()
    {
        $videos = json_decode(get_setting('home_video_grid_videos', '[]'), true);
        $videos = is_array($videos) ? $videos : [];

        return view('backend.website_settings.video_grid', [
            'videos' => $videos,
            'enabled' => (int) get_setting('home_video_grid_enabled', 1) === 1,
            'title' => get_setting('home_video_grid_title', ''),
            'subtitle' => get_setting('home_video_grid_subtitle', ''),
        ]);
    }

    public function preview(Request $request)
    {
        $request->validate(['url' => ['required', 'url', 'max:1000']]);

        $parsed = PromoVideo::parse($request->input('url'));
        if (!$parsed) {
            return response()->json([
                'ok' => false,
                'message' => translate('Use a link to a Facebook, YouTube or TikTok video.'),
            ], 422);
        }

        return response()->json([
            'ok' => true,
            'platform' => $parsed['platform'],
            'vertical' => $parsed['vertical'],
            'embed_url' => PromoVideo::embedUrl($parsed, false),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => ['nullable', 'boolean'],
            'title' => ['nullable', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:200'],
            'videos' => ['nullable', 'array', 'max:12'],
            'videos.*.title' => ['nullable', 'string', 'max:120'],
            'videos.*.url' => ['required', 'url', 'max:1000'],
            'videos.*.active' => ['nullable', 'boolean'],
        ], [
            'videos.*.url.required' => translate('Paste a video link in every row, or remove the empty row.'),
            'videos.*.url.url' => translate('Enter a complete link starting with https://'),
        ]);

        $videos = [];
        $errors = [];
        foreach (array_values($validated['videos'] ?? []) as $i => $row) {
            $parsed = PromoVideo::parse($row['url']);
            if (!$parsed) {
                $errors["videos.$i.url"] = translate('Use a link to a Facebook, YouTube or TikTok video.');
                continue;
            }

            $videos[] = [
                'title' => trim($row['title'] ?? ''),
                'url' => trim($row['url']),
                'platform' => $parsed['platform'],
                'embed_id' => $parsed['embed_id'],
                'vertical' => $parsed['vertical'],
                'active' => (bool) ($row['active'] ?? false),
            ];
        }

        if ($errors) {
            throw ValidationException::withMessages($errors);
        }

        $settings = [
            'home_video_grid_enabled' => !empty($validated['enabled']) ? 1 : 0,
            'home_video_grid_title' => trim($validated['title'] ?? ''),
            'home_video_grid_subtitle' => trim($validated['subtitle'] ?? ''),
            'home_video_grid_videos' => json_encode($videos, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ];

        DB::transaction(function () use ($settings) {
            foreach ($settings as $type => $value) {
                BusinessSetting::updateOrCreate(['type' => $type], ['value' => $value]);
            }
        });

        flash(translate('Video grid updated successfully.'))->success();

        return back();
    }
}
