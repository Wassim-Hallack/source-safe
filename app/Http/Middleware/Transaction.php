<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Transaction
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        DB::beginTransaction();
        try {
            $response = $next($request);
            DB::commit();
            return $response;
        } catch (Throwable) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'The transaction failed.'
            ], 400);
        }
    }
}
