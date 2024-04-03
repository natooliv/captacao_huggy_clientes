<?php
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

// Rotas protegidas por autenticação da Huggy

// Rota para gerenciar recursos de clientes
Route::apiResource('clientes', ClientController::class);
Route::delete('/clientes/{cliente}', [ClientController::class, 'destroy']);

// Rota para upload de imagem
Route::post('/upload-image', [ClientController::class, 'uploadImage']);


// Rota para autenticação com o Google
Route::post('/auth/google', [AuthController::class, 'authenticateWithGoogle']);
