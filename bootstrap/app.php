<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\UnauthorizedException;
use Psr\Log\LogLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(static function (Exceptions $exceptions): void {
        $exceptions->reportable(static function (Throwable $e) {
            $level = match (true) {
                $e instanceof NotFoundHttpException,
                $e instanceof ModelNotFoundException => LogLevel::WARNING,
                $e instanceof AuthenticationException => LogLevel::NOTICE,
                default => LogLevel::ERROR,
            };

            $context = [
                'exception' => $e,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ];

            try {
                Log::log($level, $e->getMessage(), $context);
            } catch (Throwable $loggingException) {
                $timestamp = date('Y-m-d H:i:s');
                $entry = "[{$timestamp}] {$level}: {$e->getMessage()} in {$e->getFile()}:{$e->getLine()}" . PHP_EOL
                    . $e->getTraceAsString() . PHP_EOL . PHP_EOL;

                @file_put_contents(storage_path('logs/laravel.log'), $entry, FILE_APPEND);
                error_log($entry);
            }
        });

        $exceptions->render(static function (Throwable $e, Request $request) {
            $status = 500;
            $message = 'Terjadi kesalahan pada server.';
            if ($e instanceof NotFoundHttpException || $e instanceof RouteNotFoundException || $e instanceof ModelNotFoundException) {
                $status = 404;
                $message = 'Halaman tidak ditemukan.';
            } elseif ($e instanceof MethodNotAllowedHttpException) {
                $status = 405;
                $message = 'Metode tidak diperbolehkan.';
            } elseif ($e instanceof TokenMismatchException || $e instanceof AuthenticationException) {
                session()->invalidate();
                session()->regenerateToken();
                $redirect = redirect()->route('login')->with('error', $e instanceof TokenMismatchException ? 'Sesi Anda telah berakhir. Silakan login kembali.' : 'Anda harus login untuk mengakses halaman ini.');

                return $request->wantsJson() ? response()->json(['message' => $e instanceof TokenMismatchException ? 'Token mismatch.' : 'Unauthenticated.'], $e instanceof TokenMismatchException ? 419 : 401) : $redirect;
            } elseif ($e instanceof AccessDeniedHttpException || $e instanceof AuthorizationException || $e instanceof UnauthorizedException) {
                $status = 403;
                $message = 'Akses ditolak. Anda tidak memiliki izin untuk melakukan tindakan ini.';
            } elseif ($e instanceof HttpExceptionInterface) {
                $status = $e->getStatusCode();
                $message = $e->getMessage();
            }

            if (app()->isProduction() && $status === 500) {
                $message = 'Terjadi kesalahan pada server.';
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], $status);
            }

            $errorView = "errors.$status";

            if (view()->exists($errorView)) {
                return response()->view($errorView, ['message' => $message], $status);
            }

            return response($message, $status);
        });
    })
    ->create();
