<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Connector;
use Illuminate\Http\Request;

class ConnectorController extends Controller
{
    public function index()
    {
        $connectors = Connector::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.connectors.index', compact('connectors'));
    }

    public function create()
    {
        return view('admin.connectors.form');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Connector::create($data);
        return redirect()->route('admin.connectors.index')->with('success', 'Connector created.');
    }

    public function edit(Connector $connector)
    {
        return view('admin.connectors.form', compact('connector'));
    }

    public function update(Request $request, Connector $connector)
    {
        $data = $this->validated($request, $connector);
        $connector->update($data);
        return redirect()->route('admin.connectors.index')->with('success', 'Connector updated.');
    }

    public function destroy(Connector $connector)
    {
        $connector->delete();
        return redirect()->route('admin.connectors.index')->with('success', 'Connector deleted.');
    }

    private function validated(Request $request, ?Connector $existing = null): array
    {
        $data = $request->validate([
            'slug'               => 'required|string|max:100',
            'name'               => 'required|string|max:255',
            'description'        => 'nullable|string',
            'category'           => 'nullable|string|max:100',
            'icon'               => 'nullable|string|max:10',
            'website_url'        => 'nullable|url|max:500',
            'is_active'          => 'boolean',
            'sort_order'         => 'integer|min:0',
            'version'            => 'nullable|string|max:50',
            'vendor'             => 'nullable|string|max:255',
            'owner'              => 'nullable|string|max:255',
            'status'             => 'nullable|string|in:active,deprecated,draft',
            'sla_tier'           => 'nullable|string|in:critical,high,standard,low',
            'environment'        => 'nullable|array',
            'environment.*'      => 'string|in:dev,test,prod',
            'tags'               => 'nullable|string',
            'is_oauth'           => 'boolean',
            'oauth_client_id'    => 'nullable|string|max:255',
            'oauth_client_secret'=> 'nullable|string',
            'oauth_auth_url'     => 'nullable|url|max:500',
            'oauth_token_url'    => 'nullable|url|max:500',
            'oauth_scopes'       => 'nullable|string',
            'oauth_extra_params' => 'nullable|json',
            'config_schema'      => 'nullable|json',
        ]) + ['is_active' => false, 'is_oauth' => false, 'status' => 'active'];

        // Blank secret on edit = keep existing
        if ($existing && empty($data['oauth_client_secret'])) {
            unset($data['oauth_client_secret']);
        }

        // Parse JSON fields
        foreach (['oauth_extra_params', 'config_schema'] as $field) {
            if (isset($data[$field]) && $data[$field] !== '') {
                $data[$field] = json_decode($data[$field], true);
            } else {
                $data[$field] = null;
            }
        }

        // Tags: comma-separated string → array
        if (isset($data['tags']) && $data['tags'] !== '') {
            $data['tags'] = array_values(array_filter(array_map('trim', explode(',', $data['tags']))));
        } else {
            $data['tags'] = null;
        }

        // Environment: null if empty array
        if (empty($data['environment'])) {
            $data['environment'] = null;
        }

        return $data;
    }
}
