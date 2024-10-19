<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JivoWebhookController extends Controller
{
    public function webhook(Request $request)
    {

        return response()
            ->json([
                'ok' => true
            ]);
    } 
}
