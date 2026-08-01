<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureServerGlobals
{
    public function handle(Request $request, Closure $next)
    {
        if (!isset($_SERVER['SERVER_NAME'])) {
            $_SERVER['SERVER_NAME'] = $request->getHost();
        }
        if (!isset($_SERVER['SCRIPT_NAME'])) {
            $_SERVER['SCRIPT_NAME'] = '/index.php';
        }
        if (!isset($_SERVER['PHP_SELF'])) {
            $_SERVER['PHP_SELF'] = '/index.php';
        }
        if (!isset($_SERVER['DOCUMENT_ROOT'])) {
            $_SERVER['DOCUMENT_ROOT'] = base_path();
        }
        if (!isset($_SERVER['REQUEST_TIME_FLOAT'])) {
            $_SERVER['REQUEST_TIME_FLOAT'] = microtime(true);
        }
        if (!isset($_SERVER['REQUEST_TIME'])) {
            $_SERVER['REQUEST_TIME'] = (int) $_SERVER['REQUEST_TIME_FLOAT'];
        }

        return $next($request);
    }
}
