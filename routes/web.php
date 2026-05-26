<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\KeycloakController;
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
    return view('welcome2');
});

Route::get('pdfview',array('as'=>'pdfview','uses'=>'TestControllers@pdfview'));
Route::post('cek','TestControllers@cek')->name('cek');
Route::get('abang-siomay','TestControllers@index')->name('kang.siomay');
Route::post('makan-siomay','TestControllers@store')->name('makan.siomay');
Route::delete('jual-siomay/{id}','TestControllers@destroy')->name('destroy.siomay');
Route::get('jual-siomay/{id}/edit','TestControllers@edit')->name('edit.siomay');
Route::get('data-partisipan', 'TestControllers@partisipanGet')->name('data.peserta.get');
Route::get('data-partisipan-index', 'TestControllers@partisipanView')->name('data.peserta.view');
Route::delete('/peserta/{id}', 'TestControllers@deletePeserta')->name('peserta.delete');
Route::post('/peserta/bulk-delete', 'TestControllers@bulkDeletePeserta')->name('peserta.bulkDelete');
Route::get('/sertif/all', 'TestControllers@getAllSertif')->name('sertif.all');
Route::post('/peserta/store', 'TestControllers@storePeserta')->name('peserta.store');

// User Management Routes
Route::get('users', 'UserController@index')->name('users.index');
Route::get('users/data', 'UserController@getUsersData')->name('users.data');
Route::post('users', 'UserController@store')->name('users.store');
Route::get('users/{id}/edit', 'UserController@edit');
Route::delete('users/{id}', 'UserController@destroy');

// Return 404 for /login
Route::get('/login', function() {
    abort(404);
});

Auth::routes(['register' => false, 'login' => false]);

// Custom login path: use /signin but keep the route name `login` so middleware and helpers work
Route::get('po-haryanto', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('po-haryanto', 'Auth\LoginController@login');

Route::get('/home', 'HomeController@index')->name('home');

// Wildcard download POST (moved after auth routes so it doesn't capture /register etc.)
Route::post('{name}','TestControllers@downloadSertif')->name('download.pdf');

Route::get('/auth/redirect', [KeycloakController::class, 'redirect'])->name('keycloak.login');
Route::get('/auth/callback', [KeycloakController::class, 'callback']);
Route::post('/auth/logout', [KeycloakController::class, 'logout'])->name('keycloak.logout');
