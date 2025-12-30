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
                        <button onclick="editState({{ $state->id }}, '{{ $state->name }}', '{{ $state->code }}')" class="text-blue-600 hover:text-blue-800">
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
                            <div class="ml-4 space-y-1">
                                <h6 class="text-xs font-medium text-gray-600">Cities ({{ $district->cities->count() }})</h6>
                                @foreach($district->cities as $city)
                                    <div class="text-xs text-gray-500">- {{ $city->name }}</div>
                                @endforeach
                                <button onclick="openCityModal({{ $state->id }}, {{ $district->id }})" class="text-green-600 hover:text-green-700 text-xs font-medium">
                                    + Add City
                                </button>
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
            <form id="stateForm" onsubmit="saveState(event)">
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

<!-- City Modal -->
<div id="cityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="cityModalTitle">Add City</h3>
            <form id="cityForm" onsubmit="saveCity(event)">
                @csrf
                <input type="hidden" id="cityId" name="city_id">
                <input type="hidden" id="cityStateId" name="state_id">
                <div class="mb-4">
                    <label for="cityName" class="block text-sm font-medium text-gray-700 mb-2">City Name</label>
                    <input type="text" id="cityName" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="mb-4">
                    <label for="cityDistrict" class="block text-sm font-medium text-gray-700 mb-2">District</label>
                    <select id="cityDistrict" name="district_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select District</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCityModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
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
    document.getElementById('stateId').value = '';
    document.getElementById('stateName').value = '';
    document.getElementById('stateCode').value = '';
    document.getElementById('stateModal').classList.remove('hidden');
}

function editState(id, name, code) {
    document.getElementById('stateModalTitle').textContent = 'Edit State';
    document.getElementById('stateId').value = id;
    document.getElementById('stateName').value = name;
    document.getElementById('stateCode').value = code;
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

    const url = stateId ? `/admin/states/${stateId}` : '/admin/states';
    const method = stateId ? 'PUT' : 'POST';

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

function deleteState(id) {
    if (confirm('Are you sure you want to delete this state? This will also delete all related districts and cities.')) {
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

// City modal functions
function openCityModal(stateId, districtId) {
    document.getElementById('cityModalTitle').textContent = 'Add City';
    document.getElementById('cityId').value = '';
    document.getElementById('cityName').value = '';
    document.getElementById('cityStateId').value = stateId;
    document.getElementById('cityDistrict').innerHTML = '<option value="' + districtId + '">Loading...</option>';
    document.getElementById('cityDistrict').disabled = true;
    document.getElementById('cityModal').classList.remove('hidden');

    // Load districts for this state
    fetch(`/api/districts/${stateId}`)
        .then(response => response.json())
        .then(data => {
            let options = '<option value="">Select District</option>';
            data.forEach(district => {
                options += `<option value="${district.id}" ${district.id == districtId ? 'selected' : ''}>${district.name}</option>`;
            });
            document.getElementById('cityDistrict').innerHTML = options;
            document.getElementById('cityDistrict').disabled = false;
        })
        .catch(error => {
            console.error('Error loading districts:', error);
            document.getElementById('cityDistrict').innerHTML = '<option value="" disabled>Error loading districts</option>';
        });
}

function closeCityModal() {
    document.getElementById('cityModal').classList.add('hidden');
}

function saveCity(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch('/admin/cities', {
        method: 'POST',
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
</script>
@endsection