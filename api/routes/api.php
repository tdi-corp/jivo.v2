<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JivoWebhookController;

Route::post('/jivo-webhook', [JivoWebhookController::class, 'webhook']);
// Route::get('/jivo-webhook-test', [JivoWebhookController::class, 'webhook']);
