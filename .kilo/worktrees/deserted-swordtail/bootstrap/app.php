<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:       __DIR__.'/../routes/web.php',
        api:       __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        commands:  __DIR__.'/../routes/console.php',
        health:    '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Sanctum — allows browser sessions to authenticate API routes
        $middleware->statefulApi();

        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role.or.permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'active'             => \App\Http\Middleware\EnsureUserIsActive::class,
            'has_shop'           => \App\Http\Middleware\EnsureUserHasShop::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();