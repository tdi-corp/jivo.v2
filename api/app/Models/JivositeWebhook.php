<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JivositeWebhook extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_name',
        'widget_id',
        'visitor_name',
        'visitor_email',
        'visitor_phone',
        'visitor_description',
        'visitor_number',
        'chat_id',
        'session_geoip_country',
        'session_geoip_region',
        'session_geoip_city',

        'session_utm_json_source',
        'session_utm_json_campaign',
        'session_utm_json_content',
        'session_utm_json_medium',
        'session_utm_json_term',

        'page_title',
        'page_url',
    ];
}
