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
    return view('home');
});


Route::resource('clients', 'ClientController');
Route::resource('bills', 'BillController');

Route::post('bills/emit/{id}', 'BillController@emit')->name('bills.emit');
Route::post('bills/paid/{id}', 'BillController@paid')->name('bills.paid');