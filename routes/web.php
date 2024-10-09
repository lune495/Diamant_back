<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CaisseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/vente/ticket-pdf-pharmacie/{id}', [CaisseController::class,'generatePDF3']);
Route::get('/vente/historique-pdf-module/{module_id}/{start?}/{end?}', [CaisseController::class,'generateHistorique']);
Route::get('/vente/situation-caisse-par-date/{start}/{end}', [CaisseController::class,'FiltreSituationParDate']);
Route::get('/test', [CaisseController::class,'Notif']);
Route::get('/vente/situation-generale-pdf', [CaisseController::class,'generatePDF2']);
Route::get('/vente/situation-filtre-pdf/{start}', [CaisseController::class,'SituationParFiltreDate']);
// Route::group(['middleware' => ['web','auth:sanctum']],function()
// {
    Route::get('/vente/ticket-pdf-service/{id}', [CaisseController::class,'generatePDF']);
// });