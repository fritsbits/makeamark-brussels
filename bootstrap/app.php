<?php

use App\Support\ApplicationForm;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
        $exceptions->render(function (HttpException $exception, Request $request) {
            if (! $request->isMethod('POST') || ! $request->is('makers/apply', 'organisations/apply') || $request->expectsJson() || ! $request->hasSession()) {
                return null;
            }

            $audience = $request->is('makers/apply') ? 'makers' : 'organisations';

            if ($exception->getStatusCode() === 419) {
                return ApplicationForm::recover($request, $audience, 'Your session has expired. Your application has not been sent. Please review your answers and send it again.');
            }

            if ($exception->getStatusCode() === 429) {
                $seconds = max(1, (int) ($exception->getHeaders()['Retry-After'] ?? 60));

                return ApplicationForm::recover($request, $audience, "Too many attempts. Please wait {$seconds} seconds before sending again. Your answers are still here.")
                    ->header('Retry-After', (string) $seconds);
            }

            return null;
        });

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
