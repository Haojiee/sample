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
Route::get('signup/confirm/{token}', 'UsersController@confirmEmail')->name('confirm_email');

Route::resource('users', 'UsersController');

Route::get('login', 'LoginController@create')->name('login');
Route::post('login', 'LoginController@store')->name('login');
Route::delete('logout', 'LoginController@destroy')->name('logout');

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::resource('statuses', 'StatusesController');

Route::get('users/{user}/followings', 'UsersController@getFollowings')->name('users.followings');
Route::get('users/{user}/followers', 'UsersController@getFollowers')->name('users.followers');

Route::post('users/{user}/followers', 'FollowersController@store')->name("followers.store");
Route::delete('users/{user}/followers', 'FollowersController@destroy')->name('followers.destroy');