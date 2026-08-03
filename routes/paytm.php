<?php

//Paytm

use App\Http\Controllers\Api\V2\PaytmController;
use App\Http\Controllers\Api\V2\MyfatoorahController;
use App\Http\Controllers\Api\V2\KhaltiController;
use App\Http\Controllers\Api\V2\PhonepeController;
// No controller anywhere implements Paytm's credentials_index()/
// update_credentials() (admin config screen), and
// App\Http\Controllers\Payment\ToyyibpayController was never implemented
// (confirmed absent from git history). Aliased to the DisabledAddonController
// stub so these route names stay resolvable (referenced from the admin
// sidebar) while actually visiting one 404s instead of fatally erroring.
use App\Http\Controllers\DisabledAddonController as PaytmAdminController;
use App\Http\Controllers\DisabledAddonController as ToyyibpayController;

Route::controller(PaytmController::class)->group(function () {
    Route::get('/paytm/index', 'pay');
    Route::post('/paytm/callback', 'callback')->name('paytm.callback');
});

//Admin
Route::group(['prefix' =>'admin', 'middleware' => ['auth', 'admin']], function(){
    Route::controller(PaytmAdminController::class)->group(function () {
        Route::get('/paytm_configuration', 'credentials_index')->name('paytm.index');
        Route::post('/paytm_configuration_update', 'update_credentials')->name('paytm.update_credentials');
    });
});

//Toyyibpay
Route::controller(ToyyibpayController::class)->group(function () {
    Route::get('toyyibpay-status', 'paymentstatus')->name( 'toyyibpay-status');
    Route::post('/toyyibpay-callback', 'callback')->name( 'toyyibpay-callback');
});

//Myfatoorah START
Route::get('/myfatoorah/callback', [MyfatoorahController::class,'callback'])->name('myfatoorah.callback');

//Khalti START
Route::any('/khalti/payment/done', [KhaltiController::class,'paymentDone'])->name('khalti.success');

// phonepe
Route::controller(PhonepeController::class)->group(function () {
    Route::any('/phonepe/pay', 'pay')->name('phonepe.pay');
    Route::any('/phonepe/redirecturl', 'phonepe_redirecturl')->name('phonepe.redirecturl');
    Route::any('/phonepe/callbackUrl', 'phonepe_callbackUrl')->name('phonepe.callbackUrl');
});
