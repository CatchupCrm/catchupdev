<?php

// Public pages
//Route::get('/', 'HomeController@showIndex');


/*Route::group(['middleware' => 'auth', 'namespace' => 'Modules\Core\Http\Controllers'], function()
{
    Route::resource('core', 'CoreController');
    Route::post('core/bulk', 'CoreController@bulk');
    Route::get('api/core', 'CoreController@datatable');
});

Route::group(['middleware' => 'api', 'namespace' => 'Modules\Core\Http\ApiControllers', 'prefix' => 'api/v1'], function()
{
    Route::resource('core', 'CoreApiController');
});*/

/*
// Application setup
Route::get('/setup', 'AppController@showSetup');
Route::post('/setup', 'AppController@doSetup');
Route::get('/install', 'AppController@install');
Route::get('/update', 'AppController@update');

// Public pages
Route::get('/', 'HomeController@showIndex');
Route::get('/log_error', 'HomeController@logError');
Route::get('/invoice_now', 'HomeController@invoiceNow');
Route::get('/keep_alive', 'HomeController@keepAlive');
Route::post('/get_started', 'CompanyController@getStarted');


Route::get('license', 'NinjaController@show_license_payment');
Route::post('license', 'NinjaController@do_license_payment');
Route::get('claim_license', 'NinjaController@claim_license');

Route::post('signup/validate', 'CompanyController@checkEmail');
Route::post('signup/submit', 'CompanyController@submitSignup');

Route::get('/auth/{provider}', 'Auth\AuthController@authLogin');
Route::get('/auth_unlink', 'Auth\AuthController@authUnlink');
Route::match(['GET', 'POST'], '/buy_now/{gateway_type?}', 'OnlinePaymentController@handleBuyNow');

Route::post('/hook/email_bounced', 'AppController@emailBounced');
Route::post('/hook/email_opened', 'AppController@emailOpened');
Route::post('/hook/bot/{platform?}', 'BotController@handleMessage');
Route::post('/payment_hook/{companyKey}/{gatewayId}', 'OnlinePaymentController@handlePaymentWebhook');

// Laravel auth routes
Route::get('/signup', ['as' => 'signup', 'uses' => 'Auth\AuthController@getRegister']);
Route::post('/signup', ['as' => 'signup', 'uses' => 'Auth\AuthController@postRegister']);
Route::get('/login', ['as' => 'login', 'uses' => 'Auth\AuthController@getLoginWrapper']);
Route::post('/login', ['as' => 'login', 'uses' => 'Auth\AuthController@postLoginWrapper']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@getLogoutWrapper']);
Route::get('/recover_password', ['as' => 'forgot', 'uses' => 'Auth\PasswordController@getEmail']);
Route::post('/recover_password', ['as' => 'forgot', 'uses' => 'Auth\PasswordController@postEmail']);
Route::get('/password/reset/{token}', ['as' => 'forgot', 'uses' => 'Auth\PasswordController@getReset']);
Route::post('/password/reset', ['as' => 'forgot', 'uses' => 'Auth\PasswordController@postReset']);
Route::get('/user/confirm/{code}', 'UserController@confirm');

if (Utils::isNinja()) {
    Route::post('/signup/register', 'CompanyController@doRegister');
    Route::get('/news_feed/{user_type}/{version}/', 'HomeController@newsFeed');
    Route::get('/demo', 'CompanyController@demo');
}

if (Utils::isReseller()) {
    Route::post('/reseller_stats', 'AppController@stats');
}

// Redirects for legacy links
Route::get('/rocksteady', function () {
    return Redirect::to(NINJA_WEB_URL, 301);
});
Route::get('/about', function () {
    return Redirect::to(NINJA_WEB_URL, 301);
});
Route::get('/contact', function () {
    return Redirect::to(NINJA_WEB_URL.'/contact', 301);
});
Route::get('/plans', function () {
    return Redirect::to(NINJA_WEB_URL.'/pricing', 301);
});
Route::get('/faq', function () {
    return Redirect::to(NINJA_WEB_URL.'/how-it-works', 301);
});
Route::get('/features', function () {
    return Redirect::to(NINJA_WEB_URL.'/features', 301);
});
Route::get('/testimonials', function () {
    return Redirect::to(NINJA_WEB_URL, 301);
});
Route::get('/compare-online-invoicing{sites?}', function () {
    return Redirect::to(NINJA_WEB_URL, 301);
});
Route::get('/forgot', function () {
    return Redirect::to(NINJA_APP_URL.'/recover_password', 301);
});
Route::get('/feed', function () {
    return Redirect::to(NINJA_WEB_URL.'/feed', 301);
});
Route::get('/comments/feed', function () {
    return Redirect::to(NINJA_WEB_URL.'/comments/feed', 301);
});


**/

