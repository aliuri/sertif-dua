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

Route::get('/', function () {
    return view('welcome');
});
Route::get('pdfview',array('as'=>'pdfview','uses'=>'TestControllers@pdfview'));
Route::post('cek','TestControllers@cek')->name('cek');
Route::get('abang-siomay','TestControllers@index')->name('kang.siomay');
Route::post('makan-siomay','TestControllers@store')->name('makan.siomay');
Route::delete('jual-siomay/{id}','TestControllers@destroy')->name('destroy.siomay');
Route::get('jual-siomay/{id}/edit','TestControllers@edit')->name('edit.siomay');
Route::post('download','TestControllers@downloadSertif')->name('download');