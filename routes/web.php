<?php

use Illuminate\Support\Facades\Auth;
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

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('/murugo-login', 'Login\MurugoLoginController@redirectToMurugo')->name('murugo.login');
Route::get('/murugo-callback', 'Login\MurugoLoginController@murugoCallback');

Route::get('/home', 'HomeController@index')->name('home');


Route::middleware(['auth'])->group(function () {

    Route::get('/assign-role', function () {

        $user = request()->user();

        $user->attachRole('superadministrator');

        dd($user->hasRole('superadministrator'));
    });
});

Route::middleware(['auth', 'role:superadministrator'])->prefix('superadmin')->group(function () {

    Route::get('/dashboard', function () {

        $user = request()->user();
        $user->detachRole('superadministrator');
        dd('Role assigned');
    });
});
