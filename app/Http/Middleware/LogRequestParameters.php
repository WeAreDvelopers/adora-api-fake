<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestParameters
{
    public function handle(Request $request, Closure $next): Response
    {
        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'route_params' => $request->route()?->parameters() ?? [],
            'query_params' => $request->query(),
            'body_params' => $request->all(),
            'headers' => $request->headers->all(),
            'ip' => $request->ip(),
        ];

        Log::channel('api_requests')->info('API Request', $logData);

        return $next($request);
    }
}
