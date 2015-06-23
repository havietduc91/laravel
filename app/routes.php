<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

// Route for API
Route::group(array('prefix' => 'api'), function()
{
       Route::get('test', array('as' => 'api.test', 'uses' => 'ApiController@showWelcome'));
});

// Route for API
Route::group(array('prefix' => 'api/v1'), function()
{
    Route::get('index', array('as' => 'api.v1.index', 'uses' => 'UserController@index'));
    Route::get('show/{id}', array('as' => 'api.v1.index', 'uses' => 'UserController@show'));
    Route::resource('photos', 'PhotoController');
    Route::resource('users', 'UserController');
    Route::resource('categories', 'CategoryController');
});

Route::resource('files', 'ImagesController');