<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PartnerController extends Controller
{
    public function show()
    {
        return view('partners');
    }

    public function store(Request $request)
    {
        if ($request->filled('website_url')) {
            return back()->with('success', "Thanks! We'll review your application and be in touch within 2 business days.");
        }

        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'company'      => 'required|string|max:255',
            'partner_type' => 'required|string|max:100',
            'message'      => 'nullable|string|max:2000',
        ]);

        $this->notifyPartnerApplication($data);

        return back()->with('success', "Thanks {$data['name']}! We'll review your application and be in touch within 2 business days.");
    }

    private function notifyPartnerApplication(array $data): void
    {
        $to   = env('AVATAR_LEAD_NOTIFY_EMAIL', config('mail.from.address'));
        $name = $data['name'];
        $email = $data['email'];
        $company = $data['company'];
        $type = $data['partner_type'];
        $message = $data['message'] ?? '—';

        $html = "
            <p><strong>New Partner Application</strong></p>
            <table cellpadding='6' style='border-collapse:collapse;font-family:sans-serif;font-size:14px;'>
              <tr><td style='color:#666;'>Name</td><td><strong>{$name}</strong></td></tr>
              <tr><td style='color:#666;'>Email</td><td><a href='mailto:{$email}'>{$email}</a></td></tr>
              <tr><td style='color:#666;'>Company</td><td>{$company}</td></tr>
              <tr><td style='color:#666;'>Partner Type</td><td>{$type}</td></tr>
              <tr><td style='color:#666;'>Message</td><td>" . nl2br(e($message)) . "</td></tr>
            </table>
        ";

        try {
            Mail::html($html, function ($message) use ($to, $name, $email) {
                $message->to($to)
                        ->replyTo($email, $name)
                        ->subject("New Partner Application: {$name}");
            });
        } catch (\Exception) {
            // don't break submission if mail is misconfigured
        }
    }
}
