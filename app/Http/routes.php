<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/



Route::get('/login', 'Auth\AuthController@showLoginForm');
Route::post('/login', 'Auth\AuthController@login');
Route::get('/logout', 'Auth\AuthController@logout');

Route::get('/password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('/password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('/password/reset', 'Auth\PasswordController@reset');

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@index');

    /*
     * Band Routes
     */
    Route::get('/bands', 'BandController@index');
    Route::get('/bands/{id}', 'BandController@bandPage');
    Route::get('/bands/{id}/delete', 'BandController@bandDelete');

    Route::post('/bands/{id}/edit', 'BandController@bandEdit');
    Route::post('/bands/create', 'BandController@store');

    /*
     * Gigs Routes
     */
    Route::get('/gigs', 'GigsController@index');
    Route::get('/gigs/{id}', 'GigsController@gigPage');
    Route::get('/gigs/{id}/delete', 'GigsController@gigDelete');

    Route::post('/gigs/{id}/edit', 'GigsController@gigEdit');
    Route::post('/gigs/create', 'GigsController@store');

    /*
     * Venue Routes
     */
    Route::get('/venues', 'VenueController@index');
    Route::post('/venues/create', 'VenueController@store');

    /*
     * User Routes
     */
    Route::get('/users', 'UserController@index');
    Route::post('/users', 'UserController@store');

});


Route::group(['middleware' => 'cors', 'prefix' => 'api', 'namespace' => 'Api'], function () {
    Route::get('gigs', 'ApiController@getAllGigs');
});
