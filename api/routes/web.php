<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\InstallationController;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('listeeleve', [EleveController::class, 'print']);
Route::get('listepaiement', [EleveController::class, 'printPaiement']);
Route::get('resultatsexamen', [EleveController::class, 'printResultats']);
Route::get('bulletins', [ExamenController::class, 'print']);
// Route::get('test', [StatController::class, 'stats']);
// Route::get('install', [InstallationController::class, 'init']);
