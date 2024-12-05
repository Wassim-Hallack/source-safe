<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
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
        if(auth()->check()) {
            $id = auth()->id();
        } else {
            $conditions = [
                'email' => $request['email']
            ];
            $admin = UserRepository::findByConditions($conditions);
            $id = $admin['id'];
        }


        if (\App\Models\Admin::where('user_id', $id)->exists()) {
            $request['isAdmin'] = true;

            return $next($request);
        }

        return response()->json([
            'status' => false,
            'response' => 'Unauthorized. Admin access only.'
        ], 400);
    }
}
