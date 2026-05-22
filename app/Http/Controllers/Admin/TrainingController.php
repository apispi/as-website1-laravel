<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingController extends Controller
{
    public function index()
    {
        $trainings = Training::orderBy('sort_order')->orderBy('title')->get();
        return view('admin.trainings.index', compact('trainings'));
    }

    public function create()
    {
        return view('admin.trainings.form');
    }

    public function store(Request $request)
    {
        Training::create($this->validated($request));
        return redirect()->route('admin.trainings.index')->with('success', 'Training created.');
    }

    public function edit(Training $training)
    {
        return view('admin.trainings.form', compact('training'));
    }

    public function update(Request $request, Training $training)
    {
        $training->update($this->validated($request));
        return redirect()->route('admin.trainings.index')->with('success', 'Training updated.');
    }

    public function destroy(Training $training)
    {
        $training->delete();
        return redirect()->route('admin.trainings.index')->with('success', 'Training deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'slug'            => 'required|string|max:100',
            'title'           => 'required|string|max:255',
            'description'     => 'required|string',
            'category'        => 'nullable|string|max:100',
            'format'          => 'nullable|string|max:100',
            'duration'        => 'nullable|string|max:100',
            'level'           => 'nullable|string|max:100',
            'modules_count'   => 'nullable|integer|min:0',
            'price'           => 'nullable|string|max:50',
            'price_unit'      => 'nullable|string|max:100',
            'badge'           => 'nullable|in:New,Popular,Certification',
            'instructor'      => 'nullable|string|max:255',
            'instructor_role' => 'nullable|string|max:255',
            'checkout_name'   => 'nullable|string|max:255',
            'checkout_amount' => 'nullable|integer|min:0',
            'is_active'       => 'boolean',
            'is_featured'     => 'boolean',
            'sort_order'      => 'integer|min:0',
            'cta_headline'    => 'nullable|string|max:255',
            'cta_sub'         => 'nullable|string|max:255',
        ]) + ['is_active' => false, 'is_featured' => false];

        $raw = trim($request->input('topics', ''));
        $data['topics'] = $raw ? array_values(array_filter(array_map('trim', explode("\n", $raw)))) : [];

        $rawInc = trim($request->input('includes_text', ''));
        $data['includes'] = $rawInc ? array_values(array_filter(array_map('trim', explode("\n", $rawInc)))) : [];

        return $data;
    }
}
