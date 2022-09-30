<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


//Route::get('/create-user-stripe-customer-account', function() {
//    Artisan::call('stripe:create-user-stripe-customer-account');
//});

/* Test */
Route::get('test/emailSchedule/{id}','Api\TestController@emailTestSchedule');
Route::get('test/email/{id}','Api\TestController@emailTest');
//Route::post('twilio-sms-webhook', 'Api\TwilioController@msgReceivedAtTwilio');
//Route::get('send-sms', 'Api\TwilioController@msgSendAtTwilio');

Route::namespace('Api')->group(function () {
    Route::post('twilio-sms-webhook', 'TwilioController@msgReceivedAtTwilio');
});

//Route::group(['namespace' => 'Api'], function () {
//    Route::post('twilio-sms-webhook', 'TwilioController@msgReceivedAtTwilio');
//});


Route::get('/bill-expiration', function() {
    Artisan::call('expiration:bill-warning');
});