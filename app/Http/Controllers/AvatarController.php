<?php

namespace App\Http\Controllers;

use App\Models\AvatarLead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AvatarController extends Controller
{
    public function index()
    {
        return view('digital-avatars');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255',
            'company'  => 'nullable|string|max:255',
            'role'     => 'nullable|string|max:100',
            'use_case' => 'nullable|string|max:100',
        ]);

        $data['ip_address'] = $request->ip();

        AvatarLead::create($data);

        $this->notifyNewLead($data);

        return redirect('/digital-avatars#get-started')
            ->with('success', "Thanks {$data['name']}! We'll be in touch within 1 business day.");
    }

    private function notifyNewLead(array $data): void
    {
        $to      = env('AVATAR_LEAD_NOTIFY_EMAIL', config('mail.from.address'));
        $name    = $data['name'];
        $email   = $data['email'];
        $company = $data['company'] ?? '—';
        $role    = $data['role'] ?? '—';
        $useCase = $data['use_case'] ?? '—';

        $html = "
            <p><strong>New Digital Avatar Lead</strong></p>
            <table cellpadding='6' style='border-collapse:collapse;font-family:sans-serif;font-size:14px;'>
              <tr><td style='color:#666;'>Name</td><td><strong>{$name}</strong></td></tr>
              <tr><td style='color:#666;'>Email</td><td><a href='mailto:{$email}'>{$email}</a></td></tr>
              <tr><td style='color:#666;'>Business</td><td>{$company}</td></tr>
              <tr><td style='color:#666;'>Profession</td><td>{$role}</td></tr>
              <tr><td style='color:#666;'>Challenge</td><td>{$useCase}</td></tr>
            </table>
        ";

        try {
            Mail::html($html, function ($message) use ($to, $name, $email) {
                $message->to($to)
                        ->replyTo($email, $name)
                        ->subject("New Avatar Lead: {$name}");
            });
        } catch (\Exception) {
            // don't break form submission if mail is misconfigured
        }
    }
}
