<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class RequestFlow
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $this->before($request);
            $response = $next($request);
            $this->after($request, $response);

            return $response;
        } catch (Throwable $e) {
            return $this->onException($request, $e);
        }
    }

    protected function before(Request $request): void
    {
        Log::channel('aop')->info('Before middleware execution.', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user' => $request->user(),
            'parameters' => [
                'query' => $request->query(),
                'body' => $request->except(['password', 'token']),
            ],
        ]);
    }

    protected function after(Request $request, Response $response): void
    {
        Log::channel('aop')->info('After middleware execution.', [
            'url' => $request->fullUrl(),
            'status' => $response->getStatusCode(),
        ]);
    }

    protected function onException(Request $request, Throwable $e): Response
    {
        Log::channel('aop')->error('Exception in middleware execution.', [
            'url' => $request->fullUrl(),
            'message' => $e->getMessage(),
            'stack' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'error' => 'An error occurred while processing your request.',
            'details' => $e->getMessage(),
        ], 500);
    }
}
