<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Visit;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitController extends Controller
{
    public function index()
    {
        $visits = Visit::whereHas('lead', function ($query) {
            $query->where('agent_id', Auth::id());
        })->with('lead.property')->get();

        return view('agent.visits.index', compact('visits'));
    }

    public function create($leadId)
    {
        $lead = Lead::where('id', $leadId)
            ->where('agent_id', Auth::id())
            ->with('property')
            ->firstOrFail();

        return view('agent.visits.create', compact('lead'));
    }

    public function store(Request $request, $leadId)
    {
        $lead = Lead::where('id', $leadId)
            ->where('agent_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'scheduled_at' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        Visit::create([
            'lead_id' => $lead->id,
            'scheduled_at' => $request->scheduled_at,
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        return redirect()->route('agent.leads.show', $lead)->with('success', 'Visit scheduled successfully.');
    }

    public function show($id)
    {
        $visit = Visit::where('id', $id)
            ->whereHas('lead', function ($query) {
                $query->where('agent_id', Auth::id());
            })
            ->with('lead.property')
            ->firstOrFail();

        return view('agent.visits.show', compact('visit'));
    }

    public function edit($id)
    {
        $visit = Visit::where('id', $id)
            ->whereHas('lead', function ($query) {
                $query->where('agent_id', Auth::id());
            })
            ->with('lead.property')
            ->firstOrFail();

        return view('agent.visits.edit', compact('visit'));
    }

    public function update(Request $request, $id)
    {
        $visit = Visit::where('id', $id)
            ->whereHas('lead', function ($query) {
                $query->where('agent_id', Auth::id());
            })
            ->firstOrFail();

        $request->validate([
            'scheduled_at' => 'required|date',
            'status' => 'required|in:scheduled,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ]);

        $visit->update($request->only([
            'scheduled_at',
            'status',
            'notes',
        ]));

        return redirect()->route('agent.visits.show', $visit)->with('success', 'Visit updated successfully.');
    }

    public function destroy($id)
    {
        $visit = Visit::where('id', $id)
            ->whereHas('lead', function ($query) {
                $query->where('agent_id', Auth::id());
            })
            ->firstOrFail();

        $visit->delete();

        return redirect()->route('agent.visits.index')->with('success', 'Visit deleted successfully.');
    }
}