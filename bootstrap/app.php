<?php

use App\Exceptions\ApplicationException;
use App\Exceptions\ConflictException;
use App\Exceptions\NotFoundException;
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
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ApplicationException $e) {
            \Log::error($e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        });

        $exceptions->render(function (ConflictException $e) {
            return response()->json([
                'messge' => $e->getMessage()
            ], 409);
        });

        $exceptions->render(function (NotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        });
    })->create();
