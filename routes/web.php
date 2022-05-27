<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PerformanceComercialController;

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

/*
Route::get('/', function () {
    return view('welcome')->name('perf');
});
*/

Route::get('/', [PerformanceComercialController::class, 'index' ])->name('comercial'); 

Route::get('/relatorio-result', [PerformanceComercialController::class, 'show' ])->name('comercial_result'); 
