<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function index()
    {
        $states = State::with(['districts'])->get();
        return view('admin.states.index', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states',
            'code' => 'required|string|max:10|unique:states',
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|image|max:1024',
        ]);

        $data = $request->only(['name', 'code']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('states/images', 'public');
        }

        if ($request->hasFile('icon')) {
            $data['icon'] = $request->file('icon')->store('states/icons', 'public');
        }

        State::create($data);

        return response()->json(['message' => 'State created successfully']);
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states,name,' . $state->id,
            'code' => 'required|string|max:10|unique:states,code,' . $state->id,
            'image' => 'nullable|image|max:2048',
            'icon' => 'nullable|image|max:1024',
        ]);

        $data = $request->only(['name', 'code']);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->hasFile('image')) {
            // Delete old image
            if ($state->image && \Storage::disk('public')->exists($state->image)) {
                \Storage::disk('public')->delete($state->image);
            }
            $data['image'] = $request->file('image')->store('states/images', 'public');
        }

        if ($request->hasFile('icon')) {
            // Delete old icon
            if ($state->icon && \Storage::disk('public')->exists($state->icon)) {
                \Storage::disk('public')->delete($state->icon);
            }
            $data['icon'] = $request->file('icon')->store('states/icons', 'public');
        }

        $state->update($data);

        return response()->json(['message' => 'State updated successfully']);
    }

    public function destroy(State $state)
    {
        if ($state->image && \Storage::disk('public')->exists($state->image)) {
            \Storage::disk('public')->delete($state->image);
        }
        if ($state->icon && \Storage::disk('public')->exists($state->icon)) {
            \Storage::disk('public')->delete($state->icon);
        }
        
        $state->delete();

        return response()->json(['message' => 'State deleted successfully']);
    }
}