<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AvatarLead;

class LeadsController extends Controller
{
    public function index()
    {
        $leads = AvatarLead::latest()->get();
        return view('admin.leads', compact('leads'));
    }

    public function destroy(AvatarLead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.leads.index')->with('success', 'Lead deleted.');
    }
}
