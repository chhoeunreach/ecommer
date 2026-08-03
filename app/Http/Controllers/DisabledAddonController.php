<?php

namespace App\Http\Controllers;

/**
 * Stand-in for addon controllers that are referenced by routes/*.php but were
 * never actually delivered to this codebase (confirmed absent from git
 * history for every case it's used for - see the "use ... as" imports in
 * each routes/*.php file that aliases this class). Keeps every route name
 * resolvable (so `route('name')` never throws from views/controllers that
 * reference them, e.g. addon-gated admin sidebar links or data-driven
 * product-card links) while returning a clean 404 if the route is ever
 * actually visited, instead of a fatal "class not found" error.
 */
class DisabledAddonController extends Controller
{
    public function __call($method, $parameters)
    {
        abort(404);
    }

    public static function __callStatic($method, $parameters)
    {
        abort(404);
    }
}
