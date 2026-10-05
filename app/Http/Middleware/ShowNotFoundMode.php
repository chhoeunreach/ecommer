<?php

namespace App\Http\Middleware;

use Closure;

class ShowNotFoundMode {
    /**
     * Paths that keep working while 404 mode is active,
     * so admins can still log in and turn it off.
     */
    protected $except = [
        'admin', 'admin/*', 'login', 'logout', 'password/*', 'aiz-uploader*', 'refresh-csrf'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (get_setting('not_found_mode') == 1 && !$request->is(...$this->except)) {
            return response()->view('errors.not_found_mode', [], 404);
        }
        return $next($request);
    }
}
