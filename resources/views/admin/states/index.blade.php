@extends('layouts.admin.app')

@section('title', 'Manage States')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage States</h1>
    <p class="text-gray-600">Create and manage Indian states.</p>
</div>

<!-- States Management -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">States</h2>
        <button onclick="openStateModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-emerald-700">
            Add State
        </button>
    </div>

    <div class="space-y-4" id="states-list">
        @foreach($states as $state)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-800">{{ $state->name }}</h3>
                        <p class="text-sm text-gray-600">Code: {{ $state->code }}</p>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="editState({{ $state->id }}, '{{ $state->name }}', '{{ $state->code }}', {{ $state->is_active ? 'true' : 'false' }})" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteState({{ $state->id }})" class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Districts -->
                <div class="ml-4 space-y-3">
                    <h4 class="text-md font-medium text-gray-700">Districts ({{ $state->districts->count() }})</h4>
                    @foreach($state->districts as $district)
                        <div class="border-l-2 border-gray-300 pl-4">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="text-sm font-medium text-gray-800">{{ $district->name }}</h5>
                                <div class="flex space-x-1">
                                    <button onclick="editDistrict({{ $district->id }}, '{{ $district->name }}', {{ $state->id }})" class="text-blue-600 hover:text-blue-800 text-xs">
                                        Edit
                                    </button>
                                    <button onclick="deleteDistrict({{ $district->id }})" class="text-red-600 hover:text-red-800 text-xs">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <button onclick="openDistrictModal({{ $state->id }})" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        + Add District
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- State Modal -->
<div id="stateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="stateModalTitle">Add State</h3>
            <form id="stateForm" onsubmit="saveState(event)" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="stateId" name="state_id">
                <div class="mb-4">
                    <label for="stateName" class="block text-sm font-medium text-gray-700 mb-2">State Name</label>
                    <input type="text" id="stateName" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="mb-4">
                    <label for="stateCode" class="block text-sm font-medium text-gray-700 mb-2">State Code</label>
                    <input type="text" id="stateCode" name="code" required maxlength="10"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="mb-4">
                    <label for="stateImage" class="block text-sm font-medium text-gray-700 mb-2">State Image</label>
                    <input type="file" id="stateImage" name="image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="mb-4">
                    <label for="stateIcon" class="block text-sm font-medium text-gray-700 mb-2">State Icon</label>
                    <input type="file" id="stateIcon" name="icon" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="mb-4 flex items-center">
                    <input type="checkbox" id="stateIsActive" name="is_active" value="1" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="stateIsActive" class="ml-2 block text-sm text-gray-900">
                        Display on Homepage
                    </label>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStateModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
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

function openStateModal() {
    document.getElementById('stateModalTitle').textContent = 'Add State';
    document.getElementById('stateForm').reset();
    document.getElementById('stateId').value = '';
    document.getElementById('stateIsActive').checked = true; // Default to true
    document.getElementById('stateModal').classList.remove('hidden');
}

function editState(id, name, code, isActive) {
    document.getElementById('stateModalTitle').textContent = 'Edit State';
    document.getElementById('stateForm').reset();
    document.getElementById('stateId').value = id;
    document.getElementById('stateName').value = name;
    document.getElementById('stateCode').value = code;
    document.getElementById('stateIsActive').checked = isActive;
    document.getElementById('stateModal').classList.remove('hidden');
}

function closeStateModal() {
    document.getElementById('stateModal').classList.add('hidden');
}

function saveState(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const stateId = formData.get('state_id');

    let url = '/admin/states';
    
    if (stateId) {
        url = `/admin/states/${stateId}`;
        formData.append('_method', 'PUT');
    }

    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => Promise.reject(data));
        }
        return response.json();
    })
    .then(data => {
        if (data.message) {
            showSuccessMessage(data.message);
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'An error occurred while saving the state.');
    });
}

function deleteState(id) {
    if (confirm('Are you sure you want to delete this state? This will also delete all related districts.')) {
        fetch(`/admin/states/${id}`, {
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

// Placeholder functions
function editDistrict(id, name, stateId) {
    alert('District edit not implemented here. Please use the Districts page.');
}

function deleteDistrict(id) {
    alert('District delete not implemented here. Please use the Districts page.');
}

function openDistrictModal(stateId) {
    alert('District modal not implemented here. Please use the Districts page.');
}

</script>
@endsection