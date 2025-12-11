<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\FollowUp;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowUpController extends Controller
{
    public function index()
    {
        $followUps = FollowUp::whereHas('lead', function ($query) {
            $query->where('agent_id', Auth::id());
        })->with('lead.property')->get();

        return view('agent.follow-ups.index', compact('followUps'));
    }

    public function create($leadId)
    {
        $lead = Lead::where('id', $leadId)
            ->where('agent_id', Auth::id())
            ->with('property')
            ->firstOrFail();

        return view('agent.follow-ups.create', compact('lead'));
    }

    public function store(Request $request, $leadId)
    {
        $lead = Lead::where('id', $leadId)
            ->where('agent_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'follow_up_date' => 'required|date|after:now',
            'notes' => 'nullable|string|max:1000',
        ]);

        FollowUp::create([
            'lead_id' => $lead->id,
            'follow_up_date' => $request->follow_up_date,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('agent.leads.show', $lead)->with('success', 'Follow-up scheduled successfully.');
    }

    public function show($id)
    {
        $followUp = FollowUp::where('id', $id)
            ->whereHas('lead', function ($query) {
                $query->where('agent_id', Auth::id());
            })
            ->with('lead.property')
            ->firstOrFail();

        return view('agent.follow-ups.show', compact('followUp'));
    }

    public function edit($id)
    {
        $followUp = FollowUp::where('id', $id)
            ->whereHas('lead', function ($query) {
                $query->where('agent_id', Auth::id());
            })
            ->with('lead.property')
            ->firstOrFail();

        return view('agent.follow-ups.edit', compact('followUp'));
    }

    public function update(Request $request, $id)
    {
        $followUp = FollowUp::where('id', $id)
            ->whereHas('lead', function ($query) {
                $query->where('agent_id', Auth::id());
            })
            ->firstOrFail();

        $request->validate([
            'follow_up_date' => 'required|date',
            'status' => 'required|in:pending,completed',
            'notes' => 'nullable|string|max:1000',
        ]);

        $followUp->update($request->only([
            'follow_up_date',
            'status',
            'notes',
        ]));

        return redirect()->route('agent.follow-ups.show', $followUp)->with('success', 'Follow-up updated successfully.');
    }

    public function destroy($id)
    {
        $followUp = FollowUp::where('id', $id)
            ->whereHas('lead', function ($query) {
                $query->where('agent_id', Auth::id());
            })
            ->firstOrFail();

        $followUp->delete();

        return redirect()->route('agent.follow-ups.index')->with('success', 'Follow-up deleted successfully.');
    }
}