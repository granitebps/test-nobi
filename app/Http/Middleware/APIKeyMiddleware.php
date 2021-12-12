<?php

namespace App\Http\Middleware;

use App\Helpers;
use Closure;
use Illuminate\Http\Request;

class APIKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $api_key = config('services.api-key');
        if (!$request->hasHeader('services.api-key')) {
            return Helpers::errorResponse('Forbidden', 403);
        }
        if ($api_key !== $request->header('services.api-key')) {
            return Helpers::errorResponse('Forbidden', 403);
        }
        return $next($request);
    }
}
