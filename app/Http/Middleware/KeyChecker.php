<?php

namespace App\Http\Middleware;

use App\Models\Company;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class KeyChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $key=$request->header('minisend-key');

        if (!$key) {
            throw new AuthenticationException('API key in minisend-key header is required.');
        }

        if (! $apiKey = Company::findByKey($key)) {
            throw new AuthenticationException('Invalid API private key.');
        }

        if ($apiKey->status !== 'active') {
            throw new AuthenticationException('The api key is currently inactive.');
        }

        return $next($request);
    }
}
