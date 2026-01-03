@extends('layouts.admin.app')

@section('title', 'Manage Categories')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Categories</h1>
    
</div>

<!-- Categories Management -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900">Categories</h2>
        <button onclick="openCategoryModal()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-emerald-700">
            Add Category
        </button>
    </div>

    <div class="space-y-4" id="categories-list">
        @foreach($categories as $category)
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-800">{{ $category->name }}</h3>
                    <div class="flex space-x-2">
                        <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}')" class="text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button onclick="deleteCategory({{ $category->id }})" class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Subcategories -->
                <div class="ml-4 space-y-3">
                    @foreach($category->subcategories as $subcategory)
                        <div class="border-l-2 border-gray-300 pl-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-md font-medium text-gray-700">{{ $subcategory->name }}</h4>
                                <div class="flex space-x-2">
                                    <button onclick="editSubcategory({{ $subcategory->id }}, '{{ $subcategory->name }}', {{ $category->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                        Edit
                                    </button>
                                    <button onclick="deleteSubcategory({{ $subcategory->id }})" class="text-red-600 hover:text-red-800 text-sm">
                                        Delete
                                    </button>
                                </div>
                            </div>

                        </div>
                    @endforeach

                    <!-- Add Subcategory Button -->
                    <button onclick="openSubcategoryModal({{ $category->id }})" class="text-primary hover:text-emerald-700 text-sm font-medium">
                        + Add Sub-category
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="categoryModalTitle">Add Category</h3>
            <form id="categoryForm" onsubmit="saveCategory(event)">
                @csrf
                <input type="hidden" id="categoryId" name="category_id">
                <div class="mb-4">
                    <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                    <input type="text" id="categoryName" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCategoryModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
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

<!-- Subcategory Modal -->
<div id="subcategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="subcategoryModalTitle">Add Sub-category</h3>
            <form id="subcategoryForm" onsubmit="saveSubcategory(event)">
                @csrf
                <input type="hidden" id="subcategoryId" name="subcategory_id">
                <input type="hidden" id="subcategoryCategoryId" name="category_id">
                <div class="mb-4">
                    <label for="subcategoryName" class="block text-sm font-medium text-gray-700 mb-2">Sub-category Name</label>
                    <input type="text" id="subcategoryName" name="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeSubcategoryModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
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
    // Create a temporary success alert
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

    // Auto-remove after 2 seconds
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

function openCategoryModal() {
    document.getElementById('categoryModalTitle').textContent = 'Add Category';
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryName').value = '';
    document.getElementById('categoryModal').classList.remove('hidden');
}

function editCategory(id, name) {
    document.getElementById('categoryModalTitle').textContent = 'Edit Category';
    document.getElementById('categoryId').value = id;
    document.getElementById('categoryName').value = name;
    document.getElementById('categoryModal').classList.remove('hidden');
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function saveCategory(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const categoryId = formData.get('category_id');

    const url = categoryId ? `/admin/categories/${categoryId}` : '/admin/categories';
    const method = categoryId ? 'PUT' : 'POST';

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

function deleteCategory(id) {
    if (confirm('Are you sure you want to delete this category? This will also delete all related sub-categories and amenities.')) {
        fetch(`/admin/categories/${id}`, {
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

// Subcategory functions
function openSubcategoryModal(categoryId) {
    document.getElementById('subcategoryModalTitle').textContent = 'Add Sub-category';
    document.getElementById('subcategoryId').value = '';
    document.getElementById('subcategoryCategoryId').value = categoryId;
    document.getElementById('subcategoryName').value = '';
    document.getElementById('subcategoryModal').classList.remove('hidden');
}

function editSubcategory(id, name, categoryId) {
    document.getElementById('subcategoryModalTitle').textContent = 'Edit Sub-category';
    document.getElementById('subcategoryId').value = id;
    document.getElementById('subcategoryCategoryId').value = categoryId;
    document.getElementById('subcategoryName').value = name;
    document.getElementById('subcategoryModal').classList.remove('hidden');
}

function closeSubcategoryModal() {
    document.getElementById('subcategoryModal').classList.add('hidden');
}

function saveSubcategory(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const subcategoryId = formData.get('subcategory_id');

    const url = subcategoryId ? `/admin/subcategories/${subcategoryId}` : '/admin/subcategories';
    const method = subcategoryId ? 'PUT' : 'POST';

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
    });
}

function deleteSubcategory(id) {
    if (confirm('Are you sure you want to delete this sub-category?')) {
        fetch(`/admin/subcategories/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                location.reload();
            }
        });
    }
}

</script>
@endsection