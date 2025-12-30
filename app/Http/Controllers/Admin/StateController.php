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
            'code' => 'required|string|max:10|unique:states'
        ]);

        State::create($request->only(['name', 'code']));

        return response()->json(['message' => 'State created successfully']);
    }

    public function update(Request $request, State $state)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:states,name,' . $state->id,
            'code' => 'required|string|max:10|unique:states,code,' . $state->id
        ]);

        $state->update($request->only(['name', 'code']));

        return response()->json(['message' => 'State updated successfully']);
    }

    public function destroy(State $state)
    {
        $state->delete();

        return response()->json(['message' => 'State deleted successfully']);
    }
}