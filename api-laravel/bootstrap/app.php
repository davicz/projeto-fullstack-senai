<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // Importa a classe Request
use Illuminate\Auth\AuthenticationException; // Importa a classe de exceção de autenticação

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Registo do apelido para o nosso middleware de verificação de perfil
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // --- INÍCIO DA CORREÇÃO ---
        // Este é o método correto para lidar com exceções específicas em APIs
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            
            // Se a requisição foi para uma rota de API (começa com api/)...
            if ($request->is('api/*')) {
                
                // ...em vez de redirecionar, retorne uma resposta JSON de "Não Autenticado" com status 401.
                return response()->json([
                    'message' => 'Não autenticado. Por favor, faça o login para obter um token.'
                ], 401);
            }
        });
        // --- FIM DA CORREÇÃO ---

    })->create();
