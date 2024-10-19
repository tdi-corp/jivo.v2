<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JivositeWebhook;
use App\Models\JivositeWebhookMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
                    'ok' => false,
                ], 400);
        }

        $validator = Validator::make($request->all(), [
            'event_name' => 'required',
            'widget_id' => 'required',
            'visitor' => 'required',
            'visitor.name' => 'nullable',
            'visitor.email' => 'nullable',
            'visitor.phone' => 'nullable',
            'visitor.description' => 'nullable',
            'visitor.number' => 'required',
            'session' => 'required',
            'session.geoip' => 'required',
            'session.geoip.country' => 'required',
            'session.geoip.region' => 'required',
            'session.geoip.city' => 'required',
            'session.utm_json' => 'required',
            'session.utm_json.source' => 'nullable',
            'session.utm_json.campaign' => 'nullable',
            'session.utm_json.content' => 'nullable',
            'session.utm_json.medium' => 'nullable',
            'session.utm_json.term' => 'nullable',
            'chat_id' => 'required|numeric',
            'page' => 'required',
            'page.url' => 'required',        
            'page.title' => 'nullable', 
        ]);

        if($validator->fails()) {
            return response()->json([
                'ok' => false,
                'error' => true,
                'messages' => $validator->errors()
            ], 400);
        }


        DB::transaction(function () use ($request, $request_only, $event_name) {

            $visitor = $request->visitor;
            $session = $request->session;
            $page = $request->page;

            $query = JivositeWebhook::create([
                'event_name' => $request->event_name,
                'widget_id' => $request->widget_id,
                'visitor_name' => $visitor['name'] ?? null,
                'visitor_email' => $visitor['email'] ?? null,
                'visitor_phone' => $visitor['phone'] ?? null,
                'visitor_description' => $visitor['description'] ?? null,
                'visitor_number' => $visitor['number'],
                'chat_id' => $request->chat_id,
                'session_geoip_country' => $session['geoip']['country'],
                'session_geoip_region' => $session['geoip']['region'],
                'session_geoip_city' => $session['geoip']['city'],
                'session_utm_json_source' => $session['utm_json']['source'] ?? null,
                'session_utm_json_campaign' => $session['utm_json']['campaign'] ?? null,
                'session_utm_json_content' => $session['utm_json']['content'] ?? null,
                'session_utm_json_medium' => $session['utm_json']['medium'] ?? null,
                'session_utm_json_term' => $session['utm_json']['term'] ?? null,
                'page_title' => $page['title'] ?? null,
                'page_url' => $page['url'],
            ]);

            $query->save();


            $messages = $event_name == $request_only[0] 
                ? [[
                    'message' => $request->message,
                    'type' => 'visitor',              
                ]]
                : $request->chat['messages'];


            foreach($messages as $msg) {
                JivositeWebhookMessage::create([
                    'jivosite_webhook_id' => $query->id,
                    'message' => $msg['message'],
                    'timestamp' => $msg['timestamp'] ?? now('UTC')->getTimestamp(),
                    'type' => $msg['type'],
                    'agent_id' => $msg['agent_id'] ?? null,
        
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
