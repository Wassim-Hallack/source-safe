<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class Transaction
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        DB::statement('SET TRANSACTION ISOLATION LEVEL SERIALIZABLE');

        return DB::transaction(function () use ($next, $request) {
            return $next($request);
        });
    }
}