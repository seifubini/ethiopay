<?php

Route::group(['namespace' => 'Auth'], function () {
    Route::get('/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout')->name('logout');
//    Route::get('/register', 'Admin\Auth\RegisterController@showRegistrationForm')->name('register');
//    Route::post('/register', 'Admin\Auth\RegisterController@register');
    Route::post('/password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.request');
    Route::post('/password/reset', 'ResetPasswordController@reset')->name('password.email');
    Route::get('/password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
    Route::get('/password/reset/{token}', 'ResetPasswordController@showResetForm');
});

Route::group(['middleware' => 'auth:admin'], function () {
    /* dashboard */
    Route::get('/', 'DashboardController@index');
    Route::get('dashboard', 'DashboardController@index');
    Route::get('dashboard/averageTrnsactionChartData/chartType/{chartType}', 'DashboardController@averageTrnsactionChartData')->name('dashboard.averageTrnsactionChartData');
    Route::get('dashboard/newCustomersChartData/chartType/{newCustomersGraph}', 'DashboardController@newCustomersChartData')->name('dashboard.newCustomersChartData');
    
    
    Route::get('transactionChartData/chartType/{chartType}/{id}', 'UserController@transactionChartData')->name('dashboard.earningChartData');

    /* service-types */
    Route::get('service-types/datatable', 'ServiceTypeController@getDatatable');
    Route::get('service-types/restore/{id}', 'ServiceTypeController@restore');
    Route::resource('service-types', 'ServiceTypeController');

    /* service-providers */
    Route::get('service-providers/datatable', 'ServiceProviderController@getDatatable');
    Route::get('service-providers/restore/{id}', 'ServiceProviderController@restore');
    Route::resource('service-providers', 'ServiceProviderController');

    /* users */
    Route::get('users/checkUniqueEmail', 'UserController@checkEmailUnique');
    Route::get('users/checkUniquePhoneNumber', 'UserController@checkPhoneNumberUnique');
    Route::get('users/checkUniqueEthiopiaPhoneNumber', 'UserController@checkEthiopiaPhoneNumberUnique');
    Route::get('users/datatable', 'UserController@getDatatable');
    Route::get('users/restore/{id}', 'UserController@restore');
    Route::resource('users', 'UserController');

    /* users -> transaction Tab */
    Route::get('users/{id}/transactions', 'UserController@transactionsList')->name('users.transactions.list');
    Route::get('users/{id}/transactions/datatable', 'UserController@transactionsDatatable')->name('users.transactions.datatable');
    Route::get('users/{id}/transactions/{transactionId}', 'UserController@transactionsDetail')->name('users.transactions.show');

    /* users -> Payment Method Tab */
    Route::get('users/{id}/payment-methods', 'UserController@paymentMethodsList')->name('users.payment-methods.list');
    Route::get('users/{id}/payment-methods/datatable', 'UserController@paymentMethodsDatatable')->name('users.payment-methods.datatable');
    Route::get('users/{id}/payment-methods/create', 'UserController@paymentMethodsCreate')->name('users.payment-methods.create');
    Route::post('users/{id}/payment-methods', 'UserController@paymentMethodsStore')->name('users.payment-methods.store');

    /* users -> Tickets Tab */
    Route::get('users/{id}/tickets', 'UserController@ticketsList')->name('users.tickets.list');
    Route::get('users/{id}/tickets/datatable', 'UserController@ticketsDatatable')->name('users.tickets.datatable');
    Route::get('users/{id}/tickets/{ticket_id}', 'UserController@ticketsDetail')->name('users.tickets.show');
    Route::get('users/{id}/change-ticket-status/{ticket_id}', 'UserController@changeStatus');
    Route::get('users/{id}/get-comment/{ticketid}', 'UserController@getComment');
    Route::post('users/{id}/comment', 'UserController@postComment');

    /* settings */
    Route::get('settings/datatable', 'SettingController@getDatatable');
    Route::resource('settings', 'SettingController');

    /* transaction */
    Route::get('transaction/datatable', 'TransactionController@getDatatable');
    Route::resource('transaction', 'TransactionController');
    // Route::get('transaction', 'TransactionController@index')->name('transaction');    

    /* tickets */
    Route::get('tickets', 'TicketsController@index');
    Route::get('tickets/datatable', 'TicketsController@getDatatable');
    Route::get('tickets/{ticket_id}', 'TicketsController@show');
    Route::get('change-ticket-status/{ticket_id}', 'TicketsController@changeStatus');
    Route::get('get-comment/{ticketid}', 'CommentsController@getComment');
    Route::post('comment', 'CommentsController@postComment');

    /* UID Lookup */
    // Route::get('uid-lookup', 'UidLookupController@index')->name('uid-lookup');
    Route::get('uid-lookup/datatable', 'UidLookupController@getDatatable')->name('uid-lookup.datatable');
    Route::get('uid-lookup/import-csv', 'UidLookupController@importCSV')->name('uid-lookup.import-csv');
    Route::post('uid-lookup/import-csv', 'UidLookupController@storeImportCSV')->name('uid-lookup.import-csv.post');
    Route::resource('uid-lookup', 'UidLookupController');

    /* UID Missing */
    Route::get('uid-missing', 'UidMissingController@index')->name('uid-missing');
    Route::get('uid-missing/datatable', 'UidMissingController@getDatatable')->name('uid-missing.datatable');
    
    /* sms-email-message */
    Route::get('sms-email-message/datatable', 'SmsEmailMessageController@getDatatable');    
    Route::resource('sms-email-message', 'SmsEmailMessageController');
    
    /* admin-user-message */
    Route::get('admin-user-message/datatable', 'AdminUserMessagesController@getDatatable');
    Route::get('admin-user-message/load-new-messages', 'AdminUserMessagesController@loadNewMessages');
    Route::resource('admin-user-message', 'AdminUserMessagesController');

    /* session expired */
    Route::get('session/sessionExpired', 'SessionController@sessionExpired');

    /* Activity Log */
    Route::get('activity-log/datatable', 'ActivityLogController@getDatatable');        
    Route::get('activity-log', 'ActivityLogController@index');
    Route::get('activity-log/{id}', 'ActivityLogController@show');

});
