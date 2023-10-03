<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Hash;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class SanctumBasicMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Basic auth
        if($request->hasHeader('php-auth-user') && $request->hasHeader('php-auth-pw')){
            validator([
                'name' => $request->header('php-auth-user'),
                'password' => $request->header('php-auth-pw'),
            ],[
                'name' => 'required',
                'password' => 'required',
            ]);

            $user = User::where('name', $request->header('php-auth-user'))->first();

            if (!$user || !Hash::check($request->header('php-auth-pw'), $user->password)) {
                return response('Login invalid', 503);
            }

            auth()->login($user);
        }else{
            // Sanctum auth bearer token
            $token = $request->bearerToken();
            if (!$token) {
                return response('Unauthorized.', 401);
            }
            $personalAccessToken = PersonalAccessToken::findToken($token);
            if(!$personalAccessToken) {
                return response('Unauthorized.', 401);
            }

            auth()->login($personalAccessToken->tokenable);
        }
        return $next($request);
    }
}
