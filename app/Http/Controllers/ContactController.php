<?php

namespace App\Http\Controllers;

use App\Models\AvatarLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        AvatarLead::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'company'    => $validated['company'] ?? null,
            'use_case'   => $validated['subject'] . "\n\n" . $validated['message'],
            'ip_address' => $request->ip(),
            'source'     => 'contact',
        ]);

        $to   = env('AVATAR_LEAD_NOTIFY_EMAIL', config('mail.from.address'));
        $html = "
            <p><strong>New Contact Form Submission</strong></p>
            <table cellpadding='6' style='border-collapse:collapse;font-family:sans-serif;font-size:14px;'>
              <tr><td style='color:#666;'>Name</td><td><strong>{$validated['name']}</strong></td></tr>
              <tr><td style='color:#666;'>Email</td><td><a href='mailto:{$validated['email']}'>{$validated['email']}</a></td></tr>
              <tr><td style='color:#666;'>Company</td><td>" . ($validated['company'] ?? '—') . "</td></tr>
              <tr><td style='color:#666;'>Subject</td><td>{$validated['subject']}</td></tr>
              <tr><td style='color:#666;'>Message</td><td>" . nl2br(e($validated['message'])) . "</td></tr>
            </table>
        ";

        try {
            Mail::html($html, function ($mail) use ($to, $validated) {
                $mail->to($to)
                     ->replyTo($validated['email'], $validated['name'])
                     ->subject("Contact: {$validated['subject']}");
            });
        } catch (\Exception) {
            // don't break submission if mail is misconfigured
        }

        return back()->with('success', 'Your message has been sent. We\'ll be in touch shortly.');
    }
}
