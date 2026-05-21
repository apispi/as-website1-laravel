<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.skills.index', compact('skills'));
    }

    public function create()
    {
        return view('admin.skills.form');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        Skill::create($data);
        return redirect()->route('admin.skills.index')->with('success', 'Skill created.');
    }

    public function edit(Skill $skill)
    {
        return view('admin.skills.form', compact('skill'));
    }

    public function update(Request $request, Skill $skill)
    {
        $data = $this->validated($request);
        $skill->update($data);
        return redirect()->route('admin.skills.index')->with('success', 'Skill updated.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();
        return redirect()->route('admin.skills.index')->with('success', 'Skill deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'slug'        => 'required|string|max:100',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category'    => 'nullable|string|max:100',
            'is_active'   => 'boolean',
            'sort_order'  => 'integer|min:0',
        ]) + ['is_active' => false];
    }
}
