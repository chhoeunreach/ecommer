<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class DiagnosticTiming
{
    public static function received($event): void
    {
        $GLOBALS['octane_req_start'] = microtime(true);
    }

    public static function handled($event): void
    {
        $elapsed = isset($GLOBALS['octane_req_start'])
            ? round((microtime(true) - $GLOBALS['octane_req_start']) * 1000)
            : -1;
        Log::info('OCTANETIME kernel-handle ' . $elapsed . 'ms ' . $event->request->path());
    }

    public static function terminated($event): void
    {
        $elapsed = isset($GLOBALS['octane_req_start'])
            ? round((microtime(true) - $GLOBALS['octane_req_start']) * 1000)
            : -1;
        Log::info('OCTANETIME after-terminate ' . $elapsed . 'ms ' . $event->request->path());
    }
}
