<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */

Route::middleware(['viewShareData'])->group(function () {
    Auth::routes();
    Route::middleware(['guest'])->get('/register', 'UserController@create')->name('register');
    Route::middleware(['guest'])->post('/register', 'UserController@store');

    Route::get('/', 'WelcomeController@index')->name('welcome.index');
    Route::get('state/getStatesByCountry/{country_id}', 'StateController@getStatesByCountry')->name('state.getStatesByCountry');
    Route::get('city/getCitiesByState/{state_id}', 'CityController@getCitiesByState')->name('city.getCitiesByState');
    Route::get('checkEmailUnique', 'UserController@checkEmailUnique')->name('checkEmailUnique');
    Route::get('checkPhoneNumberUnique', 'UserController@checkPhoneNumberUnique')->name('checkPhoneNumberUnique');
    Route::get('checkEthiopiaPhoneNumberUnique', 'UserController@checkEthiopiaPhoneNumberUnique')->name('checkEthiopiaPhoneNumberUnique');
    Route::get('activateAccountByEmail/{encoded_user_id}', 'UserController@activateAccountByEmail')->name('activateAccountByEmail');
    Route::get('terms-conditions', 'WelcomeController@termsAndConditions')->name('termsAndConditions');
    Route::get('contact-us', 'WelcomeController@contactUs')->name('contactUs');
    Route::post('contact', 'WelcomeController@mailContact');
    Route::get('validateContactUsPhoneNumber', 'WelcomeController@validateContactUsPhoneNumber');

    Route::get('more-about-us', 'WelcomeController@moreAboutUs')->name('moreAboutUs');

    Route::group(['prefix' => 'user'], function () {
        Route::get('email/registration/{id}', 'EmailWebViewController@showUserRegistrationMailOnWeb');
        Route::get('email/welcome/{id}', 'EmailWebViewController@showUserWelcomeMailOnWeb');
        Route::get('email/paybill/{id}/{transactionid}', 'EmailWebViewController@showUserServicePayedMailOnWeb');                
        Route::get('email/paybillfail/{id}/{transactionid}', 'EmailWebViewController@showUserServicePayedFailMailOnWeb');                        
        Route::get('email/forgotPassword/{id}/{token}', 'EmailWebViewController@showUserForgotPasswordMailOnWeb');                  
        Route::get('email/monthlyServicesPayed/{id}/{startDate}/{endDate}', 'EmailWebViewController@showUsermonthlyServicesPayedMailOnWeb');                  
        Route::get('email/cardExpire/{id}/{paymentMethodId}', 'EmailWebViewController@showUserCardExpireWarningMailOnWeb');
        Route::get('email/uid-missing/{user_id}/{id}', 'EmailWebViewController@showUidMissingMailOnWeb');
        Route::get('email/email-message/{id}/{smsEmailMessageId}', 'EmailWebViewController@showUserEmailMessageMailOnWeb');
        Route::get('email/billExpirationWarning/{id}', 'EmailWebViewController@showbillExpirationWarningMailOnWeb');
        
    });

    Route::get('admin/email/forgotPassword/{id}/{token}', 'EmailWebViewController@showAdminForgotPasswordMailOnWeb');

    
    Route::middleware(['auth'])->group(function () {
        Route::get('home', 'UserController@index')->name('home');
        Route::get('home/transaction/{id}', 'UserController@show')->name('home.transaction.show');
        Route::resource('payment-methods', 'PaymentMethodController');

        // Route::get('my_tickets', 'TicketsController@userTickets');
        // Route::get('new_ticket', 'TicketsController@create');
        // Route::post('new_ticket', 'TicketsController@store');
        Route::get('transaction/datatable', 'TransactionController@getDatatable');
        Route::get('transaction/{id}', 'TransactionController@show')->name('transaction.show');
        Route::get('transaction', 'TransactionController@index')->name('transaction.index');
        
        Route::get('tickets/validateTransactionId', 'TicketsController@validateTransactionId');
        Route::get('tickets/datatable', 'TicketsController@getDatatable');
        Route::resource('tickets', 'TicketsController');

        Route::get('get-comment/{ticketid}', 'CommentsController@getComment');
        Route::post('comment', 'CommentsController@postComment');
        /* Pay-Bills */
        Route::get('pay-bill/check-uid-lookup', 'UidLookupController@checkUidLookup')->name('payBill.checkUidLookup');
        Route::get('get-service-provider-by-service-type/{id}', 'ServiceProviderController@getServiceProviderByServiceType')->name('get-service-provider-by-service-type');
        Route::get('checkDebtorPhoneNumber', 'UserController@checkDebtorPhoneNumber')->name('checkDebtorPhoneNumber');
        Route::get('pay-bill/service-type/{id}', 'PayBillController@index')->name('payBill.index');
        Route::get('pay-bill-success/{id}', 'PayBillController@payBillSuccess')->name('payBill.success');
        Route::get('pay-bill-failed', 'PayBillController@payBillFailed')->name('payBill.failed');
        Route::get('pay-bill', 'PayBillController@payBill2')->name('payBill.index2');
        Route::post('pay-bill', 'PayBillController@payBillStore');
        
        Route::get('profile', 'UserController@profile')->name('profile');
        Route::get('profile-edit', 'UserController@profileEdit')->name('profile-edit');
        Route::get('checkPhoneNumberUniqueProfileEdit', 'UserController@checkPhoneNumberUniqueProfileEdit')->name('checkPhoneNumberUniqueProfileEdit');
        Route::get('checkEthiopiaPhoneNumberUniqueProfileEdit', 'UserController@checkEthiopiaPhoneNumberUniqueProfileEdit')->name('checkEthiopiaPhoneNumberUniqueProfileEdit');
        Route::post('profile', 'UserController@profileUpdate');
    });
});
