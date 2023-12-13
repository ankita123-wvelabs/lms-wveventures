<?php

namespace App\Http\Middleware;

use Closure;
use Auth0\SDK\JWTVerifier;

class CheckAccess
{
    public function handle($request, Closure $next)
    {
        \Log::info(env('AUTH0_AUDIENCE'));
        if (!empty(env('AUTH0_AUDIENCE')) && !empty(env('AUTH0_DOMAIN'))) {
            $verifier = new JWTVerifier([
                'valid_audiences' => ['https://dev-f59u66kg.auth0.com/'],
                'authorized_iss' => ['https://laravel-graphql-api'],
                'supported_algs' => ['RS256']
            ]);

            $token = $request->bearerToken();
            $decodedToken = $verifier->verifyAndDecode($token);
            if (!$decodedToken) {
                abort(403, 'Access denied');
            }
        }
        return $next($request);
    }
}