<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PrevenirDuplicidade
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $key = 'submit_' . auth()->id() . '_' . md5(json_encode($request->all()));

        if (cache()->has($key)) {
            return back()->withErrors(['error' => 'Transação em processamento. Aguarde alguns segundos.']);
        }

        cache()->put($key, true, 5); // Bloqueia por 5 segundos

        return $next($request);
    }
}
