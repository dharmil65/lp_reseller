<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');

        if (!$token) {
            return response()->json(['error' => 'Unauthorized. Token required.'], 401);
        }

        $user = DB::table('reseller_users')->where('remember_token', $token)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid token.'], 401);
        }

        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}