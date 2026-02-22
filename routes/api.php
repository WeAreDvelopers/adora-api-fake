<?php

use App\Http\Controllers\DealController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| SMARTCOB - API Fake
|--------------------------------------------------------------------------
|
| Simulacao de todos os endpoints da API Smartcob conforme colecao Postman.
| Todos os endpoints retornam dados fake para testes de integracao.
|
*/

// --- Fluxo Negociacao ---

// 1 - Solicitar Propostas
Route::post('/v3/proposal/request', [ProposalController::class, 'request']);

// 2 - Consultar status de Solicitacao/Atualizacao de Propostas
Route::get('/v3/proposal/{proposalId}/status', [ProposalController::class, 'status']);

// 3 - Atualizacao de Propostas
Route::post('/v3/proposal/{proposalId}/update', [ProposalController::class, 'update']);

// 4 - Consultar Propostas
Route::get('/v3/proposal/{proposalId}', [ProposalController::class, 'show']);

// 5 - Confirmar Proposta
Route::post('/v3/proposal/{proposalId}/confirm', [ProposalController::class, 'confirm']);

// 6 - Consultar dados de acordo
Route::get('/v3/deals/{dealId}', [DealController::class, 'show']);

// 7 - Listar Acordos
Route::get('/v3/deals', [DealController::class, 'index']);

// --- Outros ---

// Registro Tracking
Route::post('/{customerId}/access/register', [TrackingController::class, 'register']);

// Feriados
Route::get('/holidays', [HolidayController::class, 'index']);

// Enviar dados do Acordo via SMS ou Email
Route::post('/{customerId}/deal/{dealId}/send', [EmailController::class, 'sendDeal']);

// --- Email ---

// Enviar Email
Route::post('/email/send', [EmailController::class, 'send']);
