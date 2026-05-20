<?php

use App\Http\Middleware\BlockActionsDuringImpersonation;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'block.impersonation.actions' => BlockActionsDuringImpersonation::class,

            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->report(function (Throwable $e) {

            /*
             * Do not write normal auth/validation/client errors into custom error log.
             */
            if ($e instanceof AuthenticationException) {
                return;
            }

            if ($e instanceof ValidationException) {
                return;
            }

            if ($e instanceof HttpExceptionInterface && $e->getStatusCode() < 500) {
                return;
            }

            Log::channel('custom_error')->error($e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),

                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),

                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name ?? null,
                'user_email' => Auth::user()->email ?? null,

                'input' => request()->except([
                    'password',
                    'password_confirmation',
                    'current_password',
                    '_token',
                ]),

                'trace' => $e->getTraceAsString(),
            ]);
        });

        $exceptions->render(function (Throwable $e, $request) {

            /*
             * Logged out user should go to login page normally.
             */
            if ($e instanceof AuthenticationException) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Unauthenticated.',
                    ], 401);
                }

                return redirect()->guest(route('login'));
            }

            /*
             * Validation error should work normally.
             */
            if ($e instanceof ValidationException) {
                return null;
            }

            /*
             * 404, 403, 419 etc. should work normally.
             */
            if ($e instanceof HttpExceptionInterface && $e->getStatusCode() < 500) {
                return null;
            }

            /*
             * Only real server errors should show toast + redirect back.
             */
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Something went wrong.',
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                    'current_password',
                ]))
                ->with('error', 'Something went wrong. Please try again later.');
        });

    })->create();
