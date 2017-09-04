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

Route::get('/', function () {
  return redirect('home');
});



Route::group(['middleware' => 'guest'], function() {
  Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
  Route::post('login', 'Auth\LoginController@login')->name('login.post');

  // Password Reset Routes
  Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.email');
  Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email.post');

  Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset.form');
  Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset');
});


Route::group(['middleware' => 'auth'], function() {
  Route::get('/home', 'HomeController@index')->name('home');

  Route::get('logout', 'Auth\LoginController@logout')->name('logout');

  Route::resource('posts', 'PostController');
  Route::resource('users', 'UserController');
  Route::resource('roles', 'RoleController');
});


//Auth::routes();

//Route::get('/home', 'HomeController@index');

//Route::resource('posts', 'PostController');