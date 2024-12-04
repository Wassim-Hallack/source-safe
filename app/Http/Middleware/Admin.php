<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && \App\Models\Admin::where('user_id', auth()->id())->exists()) {
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'response' => 'Unauthorized. Admin access only.'
        ], 403);
    }
}
