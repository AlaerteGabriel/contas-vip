<?php

use App\Http\Controllers\Api\WebHookController;
use Illuminate\Support\Facades\Route;

/*
 * Se faz necessário enviar o Header: "X-Webhook-Token" com o token para a rota funcionar.
 *
 * */

Route::middleware(['webhookVerify'])->group(function(){
    Route::post('/webhook/loja', [WebHookController::class, 'handle']);
});
