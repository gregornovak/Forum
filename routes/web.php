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


Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    // Route::get('/thread/create', 'ThreadController@create')->name('create_thread');
    Route::post('/thread', 'ThreadController@store');
    Route::post('/posts', 'PostController@store');
});

Route::get('/', 'ThreadController@index')->name('index');
Route::get('/thread/{id}', 'ThreadController@show')->name('show_thread');

// Route::get('/home', 'HomeController@index')->name('home');
