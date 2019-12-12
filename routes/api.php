<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'UserController@register');
Route::post('login', 'UserController@login');
Route::get('login/{check}', 'UserController@getAuthenticatedUser');

Route::get('siswa', 'SiswaController@index');
Route::get('siswa/{id}', 'SiswaController@show');
Route::post('siswa', 'SiswaController@store');
Route::put('siswa/{id}', 'SiswaController@update');
Route::delete('siswa/{id}', 'SiswaController@destroy');

Route::get('poin_siswa', 'PoinSiswaController@index');
Route::get('poin_siswa/{id}', 'PoinSiswaController@show');
Route::get('poin_siswa/siswa/{id}', 'PoinSiswaController@detail');
Route::post('poin_siswa', 'PoinSiswaController@store');
Route::put('poin_siswa/{id}', 'PoinSiswaController@update');
Route::delete('poin_siswa/{id}', 'PoinSiswaController@destroy');
Route::post('poin_siswa/find', 'PoinSiswaController@find');


Route::get('pelanggaran', 'PelanggaranController@index');
Route::get('pelanggaran/{id}', 'PelanggaranController@show');
Route::post('pelanggaran', 'PelanggaranController@store');
Route::put('pelanggaran/{id}', 'PelanggaranController@update');
Route::delete('pelanggaran/{id}', 'PelanggaranController@destroy');

Route::get('dashboard/statistik', 'DashboardController@dashboard');

Route::post('petugas', 'UserController@store');
Route::get('petugas', 'UserController@index');
Route::put('petugas/{id}', 'UserController@update');
Route::delete('petugas/{id}', 'UserController@destroy');