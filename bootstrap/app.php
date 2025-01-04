<?php

use App\Exceptions\IncorrectCredentialException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (ValidationException $e, Request $request) {
            $response = config('rc.invalid_data');
            unset($response['data']);
            $response['error'] = $e->errors();

            return response()->json($response, 400);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            $response = config('rc.unauthenticated');
            unset($response['data']);
            return response()->json($response, 401);
        });

        $exceptions->render(function (JWTException $e, Request $request) {
            $response = config('rc.unauthenticated');
            unset($response['data']);
            return response()->json($response, 401);
        });

        $exceptions->render(function (TokenBlacklistedException $e, Request $request) {
            $response = config('rc.unauthenticated');
            unset($response['data']);
            return response()->json($response, 401);
        });

        $exceptions->render(function (TokenExpiredException $e, Request $request) {
            $response = config('rc.unauthenticated');
            unset($response['data']);
            return response()->json($response, 401);
        });

        $exceptions->render(function (UnauthorizedException $e, Request $request) {
            $response = config('rc.forbidden');
            unset($response['data']);
            return response()->json($response, 403);
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            $response = config('rc.forbidden');
            unset($response['data']);
            return response()->json($response, 403);
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            $response = config('rc.forbidden');
            unset($response['data']);
            return response()->json($response, 403);
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            $error = str_starts_with($e->getMessage(), 'The route')
                ? config('rc.url_not_reachable')
                : config('rc.record_not_found');
            unset($error['data']);
            return response()->json($error, 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            $response = config('rc.method_not_allowed');
            unset($response['data']);
            return response()->json($response, 405);
        });

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            $response = config('rc.too_many_request');
            unset($response['data']);
            return response()->json($response, 429);
        });

        $exceptions->render(function (QueryException $e, Request $request) {
            $response = config('rc.internal_server_error');
            unset($response['data']);
            return response()->json($response, 500);
        });

        $exceptions->render(function (ErrorException $e, Request $request) {
            $response = config('rc.internal_server_error');
            unset($response['data']);
            return response()->json($response, 500);
        });

        $exceptions->render(function (Error $e, Request $request) {
            $response = config('rc.internal_server_error');
            unset($response['data']);
            return response()->json($response, 500);
        });

        $exceptions->render(function (TypeError $e, Request $request) {
            $response = config('rc.invalid_data');
            unset($response['data']);
            return response()->json($response, 500);
        });

        $exceptions->render(function (HttpException $e) {
            return match ($e->getStatusCode()) {
                401 => response()->json(config('rc.unauthenticated'), 401),
                403 => response()->json(config('rc.forbidden'), 403),
                404 => response()->json(config('rc.record_not_found'), 404),
                405 => response()->json(config('rc.method_not_allowed'), 405),
                default => response()->json(config('rc.internal_server_error'), 500)
            };
        });

        $exceptions->render(function (Exception $e, Request $request) {
            $response = config('rc.internal_server_error');
            unset($response['data']);
            return response()->json($response, 500);
        });
    })->create();
