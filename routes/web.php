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



//Auth::routes();
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () {
        return view('pages.dashboard');
    })->name('dashboard');

    Route::get('/phrase/create', 'PhraseController@create')->name('phrase.create');
    Route::post('/phrase/create', 'PhraseController@store');
    Route::get('/phrase/index', 'PhraseController@index')->name('phrase.index');
    Route::get('/phrase/show/{id}', 'PhraseController@show')->name('phrase.show');
    Route::delete('/phrase/delete/{id}', 'PhraseController@destroy')->name('phrase.delete');
    Route::get('/phrase/show/{phrase}/{city}', 'PhraseController@city')->name('phrase.city');
    Route::post('/phrase/export/{id}', 'PhraseController@export')->name('phrase.export');

    Route::get('/exclude/index', 'ExcludeController@index')->name('exclude.index');
    Route::get('/exclude/create', 'ExcludeController@create')->name('exclude.create');
    Route::post('/exclude/create', 'ExcludeController@store');
    Route::delete('/exclude/delete/{id}', 'ExcludeController@destroy')->name('exclude.delete');

    Route::get('/account/create', 'Auth\RegisterController@showRegistrationForm')->name('account.create');
    Route::post('/account/create', 'Auth\RegisterController@register');
    Route::delete('/account/delete/{id}', 'Auth\RegisterController@register');
    Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::post('/password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/account/index', 'Users\AccountController@index')->name('account.index');
    Route::get('/account/edit/{id}', 'Users\AccountController@edit')->name('account.edit');
    Route::post('/account/edit/{id}', 'Users\AccountController@store');
    Route::delete('/account/delete/{id}', 'Users\AccountController@destroy')->name('account.delete');
});


Route::get('/ajax/changeEmail/{id}/{email}', 'Ajax\UpdateEmailController@index')->name('ajax.updateEmail');

Route::get('/api/url/{token}', 'Api\UrlController@index')->name('api.url');
Route::get('/api/phrase/{token}', 'Api\PhraseController@index')->name('api.phrase');