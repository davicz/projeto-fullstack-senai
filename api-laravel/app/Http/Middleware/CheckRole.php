<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
        // ...retorne um erro 403 - Acesso Proibido.
        return response()->json(['message' => 'Acesso não autorizado.'], 403);
    }

        // Se estiver tudo certo, simplesmente continue com a requisição.
        return $next($request);
    }
}
