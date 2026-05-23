<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserConnector;

class UserConnectorController extends Controller
{
    public function index()
    {
        $userConnectors = UserConnector::with(['user', 'connector'])
            ->latest('connected_at')
            ->get();

        return view('admin.user-connectors.index', compact('userConnectors'));
    }
}
