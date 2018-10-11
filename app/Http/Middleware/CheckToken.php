<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Token;
use Illuminate\Support\Facades\DB;

/**
 * Class CheckToken
 * @package App\Http\Middleware
 */
class CheckToken
{
    private $tokenModel;

    public function __construct(Token $token)
    {
        $this->tokenModel = $token;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->checkToken($request)) {
            return response()->json('unauthorized', 401);
        }

        return $next($request);
    }

    private function checkToken(Request $request) : bool
    {
        $requestToken = $this->retrieveTokenFromRequest($request);
        if (!$requestToken) {
            return false;
        }
        $token = DB::table('movebox_access_token')
                    ->where('token', $requestToken)
                    ->orderBy('expires_at', 'desc')
                    ->first();

        if (!$token || $token->expires_at < time()){
            return false;
        }
        return true;
    }

    private function retrieveTokenFromRequest(Request $request) : ?string
    {
        return $request->bearerToken();
    }
}