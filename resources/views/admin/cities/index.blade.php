@extends('layouts.admin.app')

@section('title', 'Manage Cities')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Cities</h1>
    <p class="text-gray-600">Create and manage cities within districts.</p>
</div>

<!-- Cities Management -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Cities</h2>
        <button onclick="openCityModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-emerald-700">
            Add City
        </button>
    </div>

    <div class="space-y-4" id="cities-list">
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
                                            <p class="text-sm text-gray-600">{{ $district->cities->count() }} cities</p>
                                        </div>
                                    </div>
                                    <button type="button" class="district-toggle text-gray-400 hover:text-gray-600 transition-colors" data-district="{{ $district->id }}">
                                        <svg class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Cities Content -->
                            <div class="district-content px-4 py-3" data-district="{{ $district->id }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($district->cities as $city)
                                        <div class="bg-white border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                                            <span class="text-sm font-medium text-gray-700">{{ $city->name }}</span>
                                            <div class="flex space-x-1">
                                                <button onclick="editCity({{ $city->id }}, '{{ $city->name }}', {{ $district->id }}, {{ $state->id }})" class="text-blue-600 hover:text-blue-800 text-xs">
                                                    Edit
                                                </button>
                                                <button onclick="deleteCity({{ $city->id }})" class="text-red-600 hover:text-red-800 text-xs">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <button onclick="openCityModal({{ $state->id }}, {{ $district->id }})" class="mt-3 text-green-600 hover:text-green-700 text-sm font-medium">
                                    + Add City to {{ $district->name }}
                                </button>
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

<!-- City Modal -->
<div id="cityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="cityModalTitle">Add City</h3>
            <form id="cityForm" onsubmit="saveCity(event)">
                @csrf
                <input type="hidden" id="cityId" name="city_id">
                <div class="mb-4">
                    <label for="cityName" class="block text-sm font-medium text-gray-700 mb-2">City Name</label>
                    <input type="text" id="cityName" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="mb-4">
                    <label for="cityState" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <select id="cityState" name="state_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select State</option>
                        @foreach($states as $state)
                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="cityDistrict" class="block text-sm font-medium text-gray-700 mb-2">District</label>
                    <select id="cityDistrict" name="district_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" disabled>
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

function openCityModal(stateId = null, districtId = null) {
    document.getElementById('cityModalTitle').textContent = 'Add City';
    document.getElementById('cityId').value = '';
    document.getElementById('cityName').value = '';

    if (stateId) {
        document.getElementById('cityState').value = stateId;
        if (districtId) {
            loadDistrictsForState(stateId, districtId);
        } else {
            loadDistrictsForState(stateId);
        }
    } else {
        document.getElementById('cityState').value = '';
        document.getElementById('cityDistrict').innerHTML = '<option value="">Select District</option>';
        document.getElementById('cityDistrict').disabled = true;
    }

    document.getElementById('cityModal').classList.remove('hidden');
}

function editCity(id, name, districtId, stateId) {
    document.getElementById('cityModalTitle').textContent = 'Edit City';
    document.getElementById('cityId').value = id;
    document.getElementById('cityName').value = name;
    document.getElementById('cityState').value = stateId;
    loadDistrictsForState(stateId, districtId);
    document.getElementById('cityModal').classList.remove('hidden');
}

function closeCityModal() {
    document.getElementById('cityModal').classList.add('hidden');
}

function saveCity(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const cityId = formData.get('city_id');

    const url = cityId ? `/admin/cities/${cityId}` : '/admin/cities';
    const method = cityId ? 'PUT' : 'POST';

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

function deleteCity(id) {
    if (confirm('Are you sure you want to delete this city?')) {
        fetch(`/admin/cities/${id}`, {
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

// Load districts based on state selection
document.getElementById('cityState').addEventListener('change', function() {
    const stateId = this.value;
    const districtSelect = document.getElementById('cityDistrict');

    // Reset district
    districtSelect.innerHTML = '<option value="">Select District</option>';
    districtSelect.disabled = true;

    if (stateId) {
        loadDistrictsForState(stateId);
    }
});

function loadDistrictsForState(stateId, selectedDistrictId = null) {
    const districtSelect = document.getElementById('cityDistrict');

    fetch(`/api/districts/${stateId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (Array.isArray(data) && data.length > 0) {
                data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.id;
                    option.textContent = district.name;
                    if (selectedDistrictId && district.id == selectedDistrictId) {
                        option.selected = true;
                    }
                    districtSelect.appendChild(option);
                });
                districtSelect.disabled = false;
            } else {
                districtSelect.innerHTML += '<option value="" disabled>No districts available</option>';
                districtSelect.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error loading districts:', error);
            districtSelect.innerHTML += '<option value="" disabled>Error loading districts</option>';
            districtSelect.disabled = false;
        });
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

    // Initially collapse all districts
    const districtContents = document.querySelectorAll('.district-content');
    districtContents.forEach(content => {
        content.classList.add('hidden');
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

    if (e.target.closest('.district-toggle')) {
        const button = e.target.closest('.district-toggle');
        const districtId = button.getAttribute('data-district');
        const content = document.querySelector(`.district-content[data-district="${districtId}"]`);
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

// Placeholder functions for district modal (to be implemented if needed)
function openDistrictModal(stateId) {
    alert('District modal not implemented yet. Please use the Districts page.');
}
</script>
@endsection