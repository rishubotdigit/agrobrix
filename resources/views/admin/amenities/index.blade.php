@extends('layouts.admin.app')

@section('title', 'Amenities Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Amenities Management</h1>
        
    </div>
    <button onclick="openModal('createAmenityModal')" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-emerald-700 transition">
        Add New Amenity
    </button>
</div>

<!-- Amenities List -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
            <tr>
                <th class="px-6 py-4 font-semibold">Name</th>
                <th class="px-6 py-4 font-semibold">Category</th>
                <th class="px-6 py-4 font-semibold">Subcategory</th>
                <th class="px-6 py-4 font-semibold text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($amenities as $amenity)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $amenity->name }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ $amenity->subcategory->category->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                            {{ $amenity->subcategory->name ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <button onclick="editAmenity({{ $amenity->id }}, '{{ $amenity->name }}', '{{ $amenity->subcategory_id }}')" class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                        <button onclick="deleteAmenity({{ $amenity->id }})" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        No amenities found. Click "Add New Amenity" to creating one.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $amenities->links() }}
    </div>
</div>

<!-- Create Modal -->
<div id="createAmenityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Add New Amenity</h3>
            <form action="{{ route('admin.amenities.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category & Subcategory</label>
                    <select name="subcategory_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        <option value="">Select Subcategory</option>
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amenity Name</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('createAmenityModal')" class="px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editAmenityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Amenity</h3>
            <form id="editAmenityForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category & Subcategory</label>
                    <select name="subcategory_id" id="edit_subcategory_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->subcategories as $subcategory)
                                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amenity Name</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal('editAmenityModal')" class="px-4 py-2 text-gray-500 hover:text-gray-700">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-emerald-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteAmenityForm" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function editAmenity(id, name, subcategoryId) {
        const form = document.getElementById('editAmenityForm');
        form.action = `/admin/amenities/${id}`;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_subcategory_id').value = subcategoryId;
        openModal('editAmenityModal');
    }

    function deleteAmenity(id) {
        if(confirm('Are you sure you want to delete this amenity?')) {
            const form = document.getElementById('deleteAmenityForm');
            form.action = `/admin/amenities/${id}`;
            form.submit();
        }
    }
</script>
@endsection
