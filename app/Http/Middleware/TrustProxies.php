<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Trusts all proxies ('*') -- the pod is only reachable through the
     * internal cluster network via nginx-ingress (itself only reachable via
     * the Cloudflare tunnel), never directly from the internet. Without
     * this, Laravel ignores X-Forwarded-Proto entirely and treats every
     * request as plain HTTP even though it arrived as HTTPS, so url()/
     * route() generate http:// links -- which browsers block as mixed
     * content on an https:// page (breaks AJAX-loaded homepage sections,
     * cart, search, etc).
     *
     * @var array|string
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
    Request::HEADER_X_FORWARDED_FOR |
    Request::HEADER_X_FORWARDED_HOST |
    Request::HEADER_X_FORWARDED_PORT |
    Request::HEADER_X_FORWARDED_PROTO |
    Request::HEADER_X_FORWARDED_AWS_ELB;
}
