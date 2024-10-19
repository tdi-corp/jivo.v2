<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JivositeWebhookMessage extends Model
{
    protected $fillable = [
        'jivosite_webhook_id',
        'message',
        'timestamp',
        'type',
        'agent_id',
    ];
}
