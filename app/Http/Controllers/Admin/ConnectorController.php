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
        $data = $this->validated($request);
        $connector->update($data);
        return redirect()->route('admin.connectors.index')->with('success', 'Connector updated.');
    }

    public function destroy(Connector $connector)
    {
        $connector->delete();
        return redirect()->route('admin.connectors.index')->with('success', 'Connector deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'slug'        => 'required|string|max:100',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'icon'        => 'nullable|string|max:10',
            'website_url' => 'nullable|url|max:500',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]) + ['is_active' => false];
    }
}
