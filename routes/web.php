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

Route::get('/', 'IndexController@home')->name('home');
Route::get('/about', 'IndexController@about')->name('about');
Route::get('/help', 'IndexController@help')->name('help');

Route::get('signup', 'UsersController@create')->name("signup");
Route::get('login', 'UsersController@login')->name('login');

Route::resource('users', 'UsersController');

Route::get('login', 'LoginController@create')->name('login');
Route::post('login', 'LoginController@store')->name('login');
Route::delete('logout', 'LoginController@destroy')->name('logout');