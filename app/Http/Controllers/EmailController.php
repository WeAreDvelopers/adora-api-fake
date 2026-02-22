<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Enviar dados do Acordo via SMS ou Email
     * POST /{customerId}/deal/{dealId}/send
     */
    public function sendDeal(Request $request, $customerId, $dealId)
    {
        return response('ok', 200)->header('Content-Type', 'text/plain;charset=UTF-8');
    }

    /**
     * Enviar Email
     * POST /email/send
     */
    public function send(Request $request)
    {
        $externalId = $request->input('externalId', 'unknown');

        return response()->json([
            'status' => 'SENDED',
            'message' => 'Message Sended',
            'gatewayId' => rand(400000000, 500000000),
            'externalId' => $externalId,
        ]);
    }
}
