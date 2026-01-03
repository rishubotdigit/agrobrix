@extends('layouts.admin.app')

@section('title', 'Manage Districts')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Districts</h1>
    
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
        @foreach($states as $state)
            <div class="border border-gray-200 rounded-lg overflow-hidden">
                <!-- State Header -->
                <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657l4.243 4.243a1 1 0 01-1.414 1.414l-4.243-4.243M9 17a8 8 0 100-16 8 8 0 000 16z"/>
                            </svg>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $state->name }}</h3>
                                <p class="text-sm text-gray-600">{{ $state->districts->count() }} districts</p>
                            </div>
                        </div>
                        <button type="button" class="state-toggle text-gray-400 hover:text-gray-600 transition-colors" data-state="{{ $state->id }}">
                            <svg class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- State Content -->
                <div class="state-content" data-state="{{ $state->id }}">
                    @foreach($state->districts as $district)
                        <div class="border-l-4 border-green-200 ml-6 mr-6 mb-4">
                            <!-- District Header -->
                            <div class="bg-green-50 px-4 py-3 border-b border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657l4.243 4.243a1 1 0 01-1.414 1.414l-4.243-4.243M9 17a8 8 0 100-16 8 8 0 000 16z"/>
                                        </svg>
                                        <div>
                                            <h4 class="text-md font-medium text-gray-800">{{ $district->name }}</h4>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="editDistrict({{ $district->id }}, '{{ $district->name }}', {{ $state->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                            Edit
                                        </button>
                                        <button onclick="deleteDistrict({{ $district->id }})" class="text-red-600 hover:text-red-800 text-sm">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach
                    <div class="px-6 pb-4">
                        <button onclick="openDistrictModal({{ $state->id }})" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                            + Add District to {{ $state->name }}
                        </button>
                    </div>
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
    if (confirm('Are you sure you want to delete this district?')) {
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


function openDistrictModal(stateId) {
    document.getElementById('districtModalTitle').textContent = 'Add District';
    document.getElementById('districtId').value = '';
    document.getElementById('districtName').value = '';
    document.getElementById('districtState').value = stateId;
    document.getElementById('districtModal').classList.remove('hidden');
}

// Tree toggle functions
document.addEventListener('DOMContentLoaded', function() {
    // Initially collapse all states except first
    const stateContents = document.querySelectorAll('.state-content');
    stateContents.forEach((content, index) => {
        if (index > 0) {
            content.classList.add('hidden');
        }
    });
});

document.addEventListener('click', function(e) {
    if (e.target.closest('.state-toggle')) {
        const button = e.target.closest('.state-toggle');
        const stateId = button.getAttribute('data-state');
        const content = document.querySelector(`.state-content[data-state="${stateId}"]`);
        const icon = button.querySelector('svg');

        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
});
</script>
@endsection