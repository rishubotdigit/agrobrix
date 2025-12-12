<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    public function index()
    {
        $leads = Lead::where('agent_id', Auth::id())
            ->with(['property', 'agent'])
            ->get();
        return view('agent.leads.index', compact('leads'));
    }

    public function create()
    {
        $properties = Property::where('agent_id', Auth::id())->get();
        return view('agent.leads.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'required|string|max:20',
            'status' => 'required|in:new,contacted,interested,closed',
            'buying_purpose' => 'sometimes|string|max:255',
            'buying_timeline' => 'sometimes|in:3 months,6 months,More than 6 months',
            'interested_in_site_visit' => 'sometimes|boolean',
            'additional_message' => 'sometimes|string',
            'buyer_type' => 'sometimes|in:agent,buyer',
        ]);

        // Check if property is assigned to the agent
        $property = Property::where('id', $request->property_id)
            ->where('agent_id', Auth::id())
            ->first();

        if (!$property) {
            return redirect()->back()->withErrors(['property_id' => 'You are not assigned to this property.']);
        }

        Lead::create([
            'property_id' => $request->property_id,
            'agent_id' => Auth::id(),
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'buyer_phone' => $request->buyer_phone,
            'status' => $request->status,
            'buying_purpose' => $request->buying_purpose,
            'buying_timeline' => $request->buying_timeline,
            'interested_in_site_visit' => $request->interested_in_site_visit,
            'additional_message' => $request->additional_message,
            'buyer_type' => $request->buyer_type,
        ]);

        return redirect()->route('agent.leads.index')->with('success', 'Lead created successfully.');
    }

    public function show($id)
    {
        $lead = Lead::where('id', $id)
            ->where('agent_id', Auth::id())
            ->with(['property', 'agent', 'visits', 'followUps'])
            ->firstOrFail();

        return view('agent.leads.show', compact('lead'));
    }

    public function edit($id)
    {
        $lead = Lead::where('id', $id)
            ->where('agent_id', Auth::id())
            ->with('property')
            ->firstOrFail();

        $properties = Property::where('agent_id', Auth::id())->get();

        return view('agent.leads.edit', compact('lead', 'properties'));
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::where('id', $id)
            ->where('agent_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'required|email|max:255',
            'buyer_phone' => 'required|string|max:20',
            'status' => 'required|in:new,contacted,interested,closed',
            'buying_purpose' => 'sometimes|string|max:255',
            'buying_timeline' => 'sometimes|in:3 months,6 months,More than 6 months',
            'interested_in_site_visit' => 'sometimes|boolean',
            'additional_message' => 'sometimes|string',
            'buyer_type' => 'sometimes|in:agent,buyer',
        ]);

        // Check if property is assigned to the agent
        $property = Property::where('id', $request->property_id)
            ->where('agent_id', Auth::id())
            ->first();

        if (!$property) {
            return redirect()->back()->withErrors(['property_id' => 'You are not assigned to this property.']);
        }

        $lead->update($request->only([
            'property_id',
            'buyer_name',
            'buyer_email',
            'buyer_phone',
            'status',
            'buying_purpose',
            'buying_timeline',
            'interested_in_site_visit',
            'additional_message',
            'buyer_type',
        ]));

        return redirect()->route('agent.leads.index')->with('success', 'Lead updated successfully.');
    }

    public function destroy($id)
    {
        $lead = Lead::where('id', $id)
            ->where('agent_id', Auth::id())
            ->firstOrFail();

        $lead->delete();

        return redirect()->route('agent.leads.index')->with('success', 'Lead deleted successfully.');
    }
}