<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;

class EmailController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        Mail::to($request->email)->send(
            new SendEmail($request->message)
        );

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil dikirim!'
        ], 200);
    }
}