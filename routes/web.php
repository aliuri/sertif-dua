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
Route::post('{name}','TestControllers@downloadSertif')->name('download.pdf');
Route::get('data-partisipan', 'TestControllers@partisipanGet')->name('data.peserta.get');
Route::get('data-partisipan-index', 'TestControllers@partisipanView')->name('data.peserta.view');
Route::delete('/peserta/{id}', 'TestControllers@deletePeserta')->name('peserta.delete');
Route::post('/peserta/bulk-delete', 'TestControllers@bulkDeletePeserta')->name('peserta.bulkDelete');