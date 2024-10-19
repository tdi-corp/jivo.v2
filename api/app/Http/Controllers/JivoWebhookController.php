<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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



        return response()
            ->json([
                'result' => 'ok',
                'ok' => true,
                'request_test' => [
                    'event_name' => $request->event_name
                ]
            ]);
    } 
}
