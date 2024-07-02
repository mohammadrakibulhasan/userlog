<?php

namespace Rakibul\Userlog\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Rakibul\Userlog\Models\ActivityLog;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;

class ActivityLoggerMiddleware
{
    public function handle($request, Closure $next)
    {
        $this->registerModelEventListeners();

        return $next($request);
    }

    protected function registerModelEventListeners()
    {
        $events = ['created', 'updated', 'deleted'];

        foreach ($events as $event) {
            Event::listen("eloquent.{$event}: *", function ($eventName, array $data) use ($event) {
                $this->logModelEvent($event, $data[0]);
            });
        }
    }

    protected function logModelEvent($event, Model $model)
    {
        // Ignore logging for ActivityLog model
        if ($model instanceof ActivityLog) {
            return;
        }

        $user = Auth::user();
        $logData = [
            'user_id' => $user ? $user->id : null,
            'user_name' => $user ? $user->name : null,
            'title' => "{$model->getTable()} {$event}",
            'method' => $event,
            'path' => request()->path(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'details' => json_encode([
                'attributes' => $model->getAttributes(),
                'original' => $model->getOriginal()
            ]),
        ];

        ActivityLog::create($logData);
    }
}
