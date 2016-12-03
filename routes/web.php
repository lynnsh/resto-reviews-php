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

Route::get('/home', 'HomeController@index');

Route::post('/geo', 'GeoController@locate');
Route::get('/geo', 'GeoController@index');

Route::get('/', 'RestoController@index');
Route::get('/resto', 'RestoController@index');
Route::get('/resto/view/{resto}', 'RestoController@view');

Route::get('/resto/create', 'RestoController@create') -> middleware('auth');
Route::put('/resto/create', 'RestoController@create_resto');

Route::get('/resto/search', 'RestoController@search');

Route::get('/resto/edit/{resto}', 'RestoController@edit');
Route::post('/resto/edit', 'RestoController@edit_resto');

Route::delete('/resto/{resto}', 'RestoController@delete');

Route::get('/review/add/{resto}', 'ReviewController@add');
Route::put('/review/add/', 'ReviewController@add_review');

Route::get('/review/edit/{review}', 'ReviewController@edit');
Route::post('/review/edit/', 'ReviewController@edit_review');


