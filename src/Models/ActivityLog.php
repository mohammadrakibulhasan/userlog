<?php

namespace Rakibul\Userlog\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'title',  'method', 'path', 'ip_address', 'user_agent', 'response_status',
    ];
}
