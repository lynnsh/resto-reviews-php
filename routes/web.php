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
Route::post('/resto/create', 'RestoController@create_resto');

Route::get('/resto/search', 'RestoController@search');

Route::get('/resto/edit/{resto}', 'RestoController@edit');
Route::post('/resto/edit', 'RestoController@edit_resto');

Route::get('/resto/add-review/{resto}', 'RestoController@add_review');
Route::post('/resto/add-review/', 'RestoController@add_review_resto');
