<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $tokenPayload = JWTAuth::parseToken()->getPayload();

            $roleId = $tokenPayload->get('role_id');

            if ($roleId !== 2) {
                return response()->json(['error' => 'Forbidden - Admins only'], 403);
            }

            $request->setUserResolver(function () use ($user) {
                return $user;
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
