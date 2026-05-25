<?php

namespace App\Http\Controllers;

use App\Models\AvatarLead;
use Illuminate\Http\Request;

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

        return back()->with('success', "Thanks {$data['name']}! We'll be in touch within 1 business day.");
    }
}
