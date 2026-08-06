<?php

namespace App\Http\Controllers;

use App\Models\Upload;

class FaviconController extends Controller
{
    public function __invoke()
    {
        $siteIcon = Upload::find(get_setting('site_icon'));

        abort_if($siteIcon === null, 404);

        return redirect()->away(uploaded_asset($siteIcon->id), 302, [
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
