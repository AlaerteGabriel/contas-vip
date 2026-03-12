<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyWebhookToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // O nome do header pode variar (ex: X-Webhook-Token ou Authorization)
        $tokenEnviado = $request->header('X-Webhook-Token');
        $tokenEsperado = config('app.webhook_token', env('WEBHOOK_SECRET_TOKEN'));

        if (!$tokenEnviado || $tokenEnviado !== $tokenEsperado) {
            return response()->json(['message' => 'Não autorizado. Token inválido.'], 401);
        }

        return $next($request);
    }
}
