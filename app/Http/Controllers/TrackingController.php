<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Registro Tracking
     * POST /{customerId}/access/register
     */
    public function register(Request $request, $customerId)
    {
        return response('ok', 200)->header('Content-Type', 'text/plain;charset=UTF-8');
    }
}
