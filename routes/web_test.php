<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::post('/test-ajax', function(Request $request) {
    $fieldKey = md5("test");
    if(request()->has('storage_'.$fieldKey)) {
        return "FOUND: " . request()->input('storage_'.$fieldKey);
    }
    return "NOT FOUND";
});
