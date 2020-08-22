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

 Route::get('test', function () {
 	     $users= App\User::where('status',0)->get();

         $event=(object)['content' => 'test','subject' => 'Test Email'];


         App\Jobs\SendEmailJob::dispatch($users,$event);

    // $user = [
    //     'name' => 'Mahedi Hasan',
    //     'info' => 'Laravel Developer'
    // ];

    // \Mail::to('developer.prakriti@gmail.com')->send(new \App\Mail\NewMail($user));

    dd("success");

});


Route::get('reset_password_without_token/{email}', ['as' => 'reset_password_without_token', 'uses' => 'LoginController@validatePasswordRequest']);
Route::get('reset_password_with_token', ['as' => 'reset_password_with_token', 'uses' => 'LoginController@resetPassword']);

Route::get('password/reset/{token}/?email={email}', ['as' => 'passwordresetlink', 'uses' => 'LoginController@resetPassword']);


 
