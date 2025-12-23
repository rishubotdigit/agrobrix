@extends('layouts.admin.app')

@section('title', 'Manage Districts')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Districts</h1>
    <p class="text-gray-600">Create and manage districts within states.</p>
</div>

<!-- Districts Management -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Districts</h2>
        <button onclick="openDistrictModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-emerald-700">
            Add District
        </button>
    </div>

    <div class="space-y-4" id="districts-list">
        @foreach($districts as $district)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">{{ $district->name }}</h3>
                        <p class="text-sm text-gray-600">State: {{ $district->state->name }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="editDistrict({{ $district->id }}, '{{ $district->name }}', {{ $district->state_id }})" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteDistrict({{ $district->id }})" class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Cities -->
                <div class="ml-4 space-y-2">
                    <h4 class="text-md font-medium text-gray-700">Cities ({{ $district->cities->count() }})</h4>
                    @foreach($district->cities as $city)
                        <div class="text-sm text-gray-600">- {{ $city->name }}</div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- District Modal -->
<div id="districtModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="districtModalTitle">Add District</h3>
            <form id="districtForm" onsubmit="saveDistrict(event)">
                @csrf
                <input type="hidden" id="districtId" name="district_id">
                <div class="mb-4">
                    <label for="districtName" class="block text-sm font-medium text-gray-700 mb-2">District Name</label>
                    <input type="text" id="districtName" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="mb-4">
                    <label for="districtState" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <select id="districtState" name="state_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeDistrictModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showSuccessMessage(message) {
    const alertDiv = document.createElement('div');
    alertDiv.id = 'temp-success-alert';
    alertDiv.className = 'fixed top-4 right-4 z-50 max-w-sm w-full bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out';
    alertDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="block sm:inline">${message}</span>
        </div>
    `;
    document.body.appendChild(alertDiv);

    setTimeout(() => {
        alertDiv.style.transform = 'translateX(100%)';
        alertDiv.style.opacity = '0';
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.parentNode.removeChild(alertDiv);
            }
        }, 300);
    }, 2000);
}

function openDistrictModal() {
    document.getElementById('districtModalTitle').textContent = 'Add District';
    document.getElementById('districtId').value = '';
    document.getElementById('districtName').value = '';
    document.getElementById('districtState').value = '';
    document.getElementById('districtModal').classList.remove('hidden');
}

function editDistrict(id, name, stateId) {
    document.getElementById('districtModalTitle').textContent = 'Edit District';
    document.getElementById('districtId').value = id;
    document.getElementById('districtName').value = name;
    document.getElementById('districtState').value = stateId;
    document.getElementById('districtModal').classList.remove('hidden');
}

function closeDistrictModal() {
    document.getElementById('districtModal').classList.add('hidden');
}

function saveDistrict(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const districtId = formData.get('district_id');

    const url = districtId ? `/admin/districts/${districtId}` : '/admin/districts';
    const method = districtId ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            showSuccessMessage(data.message);
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteDistrict(id) {
    if (confirm('Are you sure you want to delete this district? This will also delete all related cities.')) {
        fetch(`/admin/districts/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                showSuccessMessage(data.message);
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        });
    }
}
</script>
@endsection