<?php

namespace Rakibul\Userlog\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Rakibul\Userlog\Models\ActivityLog;

class ActivityLoggerMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $user = Auth::user();
        ActivityLog::create([
            'user_id' => $user ? $user->id : null,
            'title' => $request->title ?? $request->name ?? null,
            'method' => $request->method(),
            'path' => $request->path(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'response_status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}
