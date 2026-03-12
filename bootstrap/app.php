<?php

use App\Http\Middleware\AuthSessionAdmin;
use App\Http\Middleware\CheckAuth;
use App\Http\Middleware\CheckAuthAdmin;
use App\Http\Middleware\PrevenirDuplicidade;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\VerifyWebhookToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->appendToGroup('auth', [CheckAuth::class]);
        $middleware->appendToGroup('csrf', [\App\Http\Middleware\VerifyCsrfToken::class]);

        // 1. REMOVER CSRF DA ROTA DO WEBHOOK
        $middleware->validateCsrfTokens(except: [
            'api/*'
        ]);

        $middleware->alias([
            'checkweb' => CheckAuth::class,
            'checkDuplicidade' => PrevenirDuplicidade::class,
            'webhookVerify' => VerifyWebhookToken::class
        ]);

        $middleware->redirectGuestsTo('login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
