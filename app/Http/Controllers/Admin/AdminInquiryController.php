<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\ViewedContact;

class AdminInquiryController extends Controller
{
    public function leads()
    {
        $leads = Lead::with(['property.owner', 'agent'])->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.inquiries.leads.index', compact('leads'));
    }

    public function viewedContacts()
    {
        $viewedContacts = ViewedContact::with(['buyer', 'property'])->orderBy('viewed_at', 'desc')->paginate(20);

        return view('admin.inquiries.viewed-contacts.index', compact('viewedContacts'));
    }

    public function showLead(Lead $lead)
    {
        $lead->load(['property.owner', 'agent']);

        return view('admin.inquiries.leads.show', compact('lead'));
    }

}