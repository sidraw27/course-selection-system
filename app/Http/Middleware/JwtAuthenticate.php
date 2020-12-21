<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

/**
 * 這邊只用來做 token verify 不做簽發
 *
 * @package App\Http\Middleware
 */
class JwtAuthenticate
{
    // 允許通過的 sub
    private const ALLOW_SUB = [
        'client'
    ];

    public function handle(Request $request, Closure $next)
    {
        $authorization = $request->header('Authorization');

        if (null === $authorization) {
            throw new UnauthorizedException();
        }

        $authorization = explode(' ', $authorization)[1];

        $decode = JWT::decode($authorization, env('JWT_SECRET'), ['HS256']);

        if (in_array($decode->sub ?? null, self::ALLOW_SUB)) {
            return $next($request);
        }

        throw new UnauthorizedException();
    }
}
