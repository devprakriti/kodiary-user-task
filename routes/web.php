<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/', ['as' => 'login', 'uses' => 'LoginController@login']);

Route::post('login', ['as' => 'login.store', 'uses' => 'LoginController@authenticate']);


Route::get('dashboard', ['as' => 'dashboard', 'uses' => 'HomeController@home']);



Route::get('email', ['as' => 'reset_password_without_token', 'uses' => 'LoginController@sendEmail']);

Route::get('reset_password_with_token', ['as' => 'reset_password_with_token', 'uses' => 'LoginController@resetPasswordNew']);


Route::post('reset_password_with_token_store', ['as' => 'reset_password_with_token_store', 'uses' => 'LoginController@resetPassword']);


 
