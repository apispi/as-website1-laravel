<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscribeController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
        ]);

        Mail::raw(
            "New newsletter subscriber: {$validated['email']}",
            function ($mail) use ($validated) {
                $mail->to('admin@apispi.com')
                     ->subject('New Newsletter Subscriber');
            }
        );

        return response()->json(['success' => true]);
    }
}
