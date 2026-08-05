<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::create('/test-url', 'POST', ['storage_abcdef' => '128GB']);
$app->instance('request', $request);
echo "request()->input('storage_abcdef') is: " . request()->input('storage_abcdef') . "\n";
