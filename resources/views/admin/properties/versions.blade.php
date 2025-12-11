@extends('layouts.admin.app')

@section('title', 'Property Versions')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Versions for Property: {{ $property->title }}</h1>

    <form method="POST" action="{{ route('admin.versions.bulk-approve') }}" class="mb-4">
        @csrf
        <div class="flex space-x-2">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Bulk Approve Selected</button>
            <button type="submit" formaction="{{ route('admin.versions.bulk-reject') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Bulk Reject Selected</button>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden mt-4">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="select-all">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Version</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($versions as $version)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="versions[]" value="{{ $version->id }}" class="version-checkbox">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $version->version }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($version->status == 'approved') bg-green-100 text-green-800
                                @elseif($version->status == 'rejected') bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                {{ ucfirst($version->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $version->created_at->format('Y-m-d H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.versions.diff', $version) }}" class="text-indigo-600 hover:text-indigo-900 mr-2">Diff</a>
                            @if($version->status == 'pending')
                                <form method="POST" action="{{ route('admin.versions.approve', $version) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.versions.reject', $version) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
    </form>

    <div class="mt-4">
        {{ $versions->links() }}
    </div>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.version-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
@endsection