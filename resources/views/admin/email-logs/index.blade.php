@extends('layouts.admin.app')

@section('title', 'Email Logs')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Email Logs</h1>

    <!-- Filters -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.email-logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="email_type" class="block text-sm font-medium text-gray-700">Email Type</label>
                <select id="email_type" name="email_type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All</option>
                    <option value="welcome_user" {{ request('email_type') == 'welcome_user' ? 'selected' : '' }}>Welcome User</option>
                    <option value="notify_admin_new_user" {{ request('email_type') == 'notify_admin_new_user' ? 'selected' : '' }}>Notify Admin New User</option>
                    <option value="notify_admin_payment_submitted" {{ request('email_type') == 'notify_admin_payment_submitted' ? 'selected' : '' }}>Notify Admin Payment Submitted</option>
                    <option value="notify_admin_plan_purchase" {{ request('email_type') == 'notify_admin_plan_purchase' ? 'selected' : '' }}>Notify Admin Plan Purchase</option>
                    <option value="payment_approved" {{ request('email_type') == 'payment_approved' ? 'selected' : '' }}>Payment Approved</option>
                    <option value="notify_admin_payment_approved" {{ request('email_type') == 'notify_admin_payment_approved' ? 'selected' : '' }}>Notify Admin Payment Approved</option>
                    <option value="invoice" {{ request('email_type') == 'invoice' ? 'selected' : '' }}>Invoice</option>
                    <!-- Add more as needed -->
                </select>
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select id="status" name="status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All</option>
                    <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Sent</option>
                    <option value="queued" {{ request('status') == 'queued' ? 'selected' : '' }}>Queued</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="resent" {{ request('status') == 'resent' ? 'selected' : '' }}>Resent</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="md:col-span-4 flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
                <a href="{{ route('admin.email-logs.index') }}" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Clear</a>
            </div>
        </form>
    </div>

    <!-- Email Logs Table -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Email Logs</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Recipient</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sent At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($emailLogs as $log)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $log->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $log->email_type)) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->recipient_email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->user ? $log->user->name : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @if($log->status == 'sent' || $log->status == 'resent') bg-green-100 text-green-800 @elseif($log->status == 'queued') bg-yellow-100 text-yellow-800 @elseif($log->status == 'failed') bg-red-100 text-red-800 @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->sent_at ? $log->sent_at->format('Y-m-d H:i:s') : 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form method="POST" action="{{ route('admin.email-logs.resend', $log->id) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="text-indigo-600 hover:text-indigo-900">Resend</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">No email logs found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $emailLogs->appends(request()->query())->links() }}
        </div>
    </div>
@endsection