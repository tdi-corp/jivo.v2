<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JivositeWebhook;
use App\Models\JivositeWebhookMessage;
use Illuminate\Support\Facades\DB;

class JivoWebhookController extends Controller
{
    public function webhook(Request $request)
    {
        $request_only = [
            'offline_message', 
            'chat_finished'
        ];

        $event_name = $request->event_name;

        /** if wrong event_name */
        if(!in_array($event_name, $request_only)) 
        {
            return response()
                ->json([
                    'ok' => false
                ]);
        }


        DB::transaction(function () use ($request, $request_only, $event_name) {

            $visitor = $request->visitor;
            $session = $request->session;
            $page = $request->page;

            $query = JivositeWebhook::create([
                'event_name' => $request->event_name,
                'widget_id' => $request->widget_id,
                'visitor_name' => $visitor['name'],
                'visitor_email' => $visitor['email'],
                'visitor_phone' => $visitor['phone'],
                'visitor_description' => $visitor['description'],
                'visitor_number' => $visitor['number'],
                'chat_id' => $request->chat_id,
                'session_geoip_country' => $session['geoip']['country'],
                'session_geoip_region' => $session['geoip']['region'],
                'session_geoip_city' => $session['geoip']['city'],
                'session_utm_json_source' => $session['utm_json']['source'],
                'session_utm_json_campaign' => $session['utm_json']['campaign'],
                'session_utm_json_content' => array_key_exists('content', $session['utm_json']) ?? $session['utm_json']['content'],
                'session_utm_json_medium' => array_key_exists('medium', $session['utm_json']) ?? $session['utm_json']['medium'],
                'session_utm_json_term' => array_key_exists('term', $session['utm_json']) ?? $session['utm_json']['term'],
                'page_title' => $page['title'],
                'page_url' => $page['url'],
            ]);

            $query->save();


            $messages = $event_name == $request_only[0] 
                ? [[
                    'message' => $request->message,
                    'timestamp' => now('UTC')->getTimestamp(),
                    'type' => 'visitor',
                    'agent_id' => null,                
                ]]
                : $request->chat['messages'];


            foreach($messages as $msg) {
                JivositeWebhookMessage::create([
                    'jivosite_webhook_id' => $query->id,
                    'message' => $msg['message'],
                    'timestamp' => $msg['timestamp'],
                    'type' => $msg['type'],
                    'agent_id' => array_key_exists('agent_id', $msg) ? $msg['agent_id'] : null,
        
                ]);
            }

        });


        return response()
            ->json([
                'result' => 'ok',
                'ok' => true,
            ]);
    } 
}
