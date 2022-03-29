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

Route::get('/getdata', [\App\Http\Controllers\GetDataApiController::class, 'index']);
Route::get('/atualizardata/{id}/{title}/{content}/{slug}', [\App\Http\Controllers\GetDataApiController::class, 'atualizar']);
Route::get('/excluirdata/{id}', [\App\Http\Controllers\GetDataApiController::class, 'excluir']);
