<?php

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ClasseController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\ProfesseurController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\EleveController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\AnneeController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StatController;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
}); */

Route::apiResource('classes', ClasseController::class)->parameters([ 'classes' => 'classe']);
Route::get('classes/{classe}/eleves', [ClasseController::class, 'eleves']);
Route::post('classes/{classe}/eleves', [ClasseController::class, 'updateEleves']);
Route::get('classes/{classe}/matieres', [ClasseController::class, 'matieres']);

Route::apiResource('niveaux', NiveauController::class);
Route::get('niveaux/{niveau}/eleves', [NiveauController::class, 'eleves']);

Route::apiResource('matieres', MatiereController::class);
Route::apiResource('professeurs', ProfesseurController::class);
Route::apiResource('examens', ExamenController::class);

Route::get('eleves/annulersortie/{eleve}', [EleveController::class, 'annulerSortie']);
Route::put('eleves/sortie/{eleve}', [EleveController::class, 'sortie']);
Route::post('eleves/filtre', [EleveController::class, 'filtre']);
Route::post('eleves/saveall', [EleveController::class, 'saveall']);
Route::post('eleves/filtrepaiements', [EleveController::class, 'filtreSurPaiements']);
Route::post('eleves/resultats', [EleveController::class, 'resultats']);
Route::get('eleves/paiements', [EleveController::class, 'elevesEtPaiements']);
Route::post('eleves/print', [EleveController::class, 'print']);
Route::apiResource('eleves', EleveController::class)->parameters(["eleves" => 'eleve']);
Route::get('eleves/{eleve}/paiements', [EleveController::class, 'paiements']);



// Route::apiResource('paiements', PaiementController::class);

// Annees
Route::post('annees', [AnneeController::class, 'ouverture']);
Route::put('annees', [AnneeController::class, 'fermeture']);

// Periodes
Route::get('periodes', [PeriodeController::class, 'index']);
Route::put('periodes', [PeriodeController::class, 'fermeture']);
Route::get('periodes/{periode}', [PeriodeController::class, 'ouverture']);

// Paiements
Route::apiResource('paiements', PaiementController::class);

//notes
Route::get('notes/classe/{classe}', [NoteController::class, 'classe']);
Route::apiResource('notes', NoteController::class);


//users
Route::post('users/auth', [UserController::class, 'auth']);
Route::get('users/new', [UserController::class, 'newUser']);
Route::apiResource('users', UserController::class);

// stats
Route::get('stats', [StatController::class, 'stats']);
