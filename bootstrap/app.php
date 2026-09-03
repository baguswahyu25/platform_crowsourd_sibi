<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (TokenMismatchException $e, $request) {
            return redirect()->back()
                ->withInput($request->except('_token', 'password', 'password_confirmation'))
                ->with('error', 'Sesi halaman Anda telah kedaluwarsa. Halaman telah diperbarui, silakan coba masuk kembali.');
        });
    })->create();
