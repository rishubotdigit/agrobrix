@extends('layouts.admin.app')

@section('title', 'SMS Gateway Settings')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">SMS Gateway Settings</h1>
                    <p class="mt-2 text-sm text-gray-600">Configure MSG91 settings and manage SMS templates</p>
                </div>
                <a href="{{ route('admin.sms-logs.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    View SMS Logs
                </a>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">There were errors:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.sms-gateways.update') }}" id="sms-gateways-form">
                @csrf

                <!-- MSG91 Configuration Card -->
                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden mb-8">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                                <div>
                                    <h3 class="text-xl font-semibold text-white">MSG91 SMS Gateway</h3>
                                    <p class="text-blue-100 text-sm">Flow API for template-based SMS</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                Active
                            </span>
                        </div>
                    </div>

                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="msg91_authkey" class="block text-sm font-medium text-gray-700 mb-2">
                                        MSG91 Auth Key <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="msg91_authkey" name="msg91_authkey" required value="{{ $settings['msg91_authkey'] }}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Your MSG91 Auth Key">
                                    <p class="mt-1 text-xs text-gray-500">Get from MSG91 Dashboard → Settings → API Keys</p>
                                </div>

                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <h4 class="text-sm font-medium text-blue-900 mb-2">📍 API Endpoint</h4>
                                    <code class="text-xs text-blue-700 break-all">https://control.msg91.com/api/v5/flow</code>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="otp_expiry_time" class="block text-sm font-medium text-gray-700 mb-2">
                                        OTP Expiry Time (minutes)
                                    </label>
                                    <input type="number" id="otp_expiry_time" name="otp_expiry_time" value="{{ $settings['otp_expiry_time'] }}" min="1" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="5">
                                    <p class="mt-1 text-xs text-gray-500">Time before OTP expires</p>
                                </div>

                                <div>
                                    <label for="otp_resend_limit" class="block text-sm font-medium text-gray-700 mb-2">
                                        OTP Resend Limit
                                    </label>
                                    <input type="number" id="otp_resend_limit" name="otp_resend_limit" value="{{ $settings['otp_resend_limit'] }}" min="1" class="block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="3">
                                    <p class="mt-1 text-xs text-gray-500">Maximum OTP resends allowed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end mb-8">
                    <button type="submit" id="save-button" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Configuration
                    </button>
                </div>
            </form>

            <!-- SMS Templates Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">SMS Templates</h2>
                        <p class="mt-1 text-sm text-gray-600">Manage templates for different SMS types</p>
                    </div>
                    <button type="button" onclick="openAddTemplateModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Template
                    </button>
                </div>

                <div class="bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Template ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($templates as $template)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $template->name }}</div>
                                        @if($template->description)
                                            <div class="text-xs text-gray-500">{{ Str::limit($template->description, 40) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $template->slug }}</code>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $template->template_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($template->is_active)
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-3">
                                        <button type="button" onclick="testTemplate({{ $template->id }}, '{{ $template->name }}')" class="text-green-600 hover:text-green-900">Test</button>
                                        <button type="button" onclick='editTemplate(@json($template))' class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button type="button" onclick="deleteTemplate({{ $template->id }})" class="text-red-600 hover:text-red-900">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No templates</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new SMS template.</p>
                                        <div class="mt-6">
                                            <button type="button" onclick="openAddTemplateModal()" class="inline-flex items-center px-4 py-2 border shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                                Add Template
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Test SMS Modal -->
    <div id="testModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeTestModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4" id="test-modal-title">Test SMS Template</h3>
                <form id="testForm" onsubmit="sendTestSMS(event)">
                    <input type="hidden" id="test_template_id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Mobile Number *</label>
                        <input type="text" id="test_mobile" required pattern="[0-9]{10,15}" class="block w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="918091176400">
                        <p class="mt-1 text-xs text-gray-500">Enter mobile with country code (10-15 digits)</p>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeTestModal()" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Send Test SMS</button>
                    </div>
                </form>
                <div id="test-result" class="mt-4 hidden"></div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Template Modal -->
    <div id="templateModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeTemplateModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form id="templateForm" method="POST">
                    @csrf
                    <input type="hidden" id="template_method" name="_method" value="POST">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">Add SMS Template</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="template_name" class="block text-sm font-medium text-gray-700 mb-1">Template Name *</label>
                                <input type="text" id="template_name" name="name" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div>
                                <label for="template_slug" class="block text-sm font-medium text-gray-700 mb-1">Template Slug *</label>
                                <select id="template_slug" name="slug" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg">
                                    <option value="">Select template type</option>
                                    @foreach($templateTypes as $slug => $name)
                                        <option value="{{ $slug }}">{{ $slug }} - {{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="template_template_id" class="block text-sm font-medium text-gray-700 mb-1">MSG91 Template ID *</label>
                                <input type="text" id="template_template_id" name="template_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <input type="hidden" name="gateway" value="msg91">
                            <div>
                                <label for="template_description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="template_description" name="description" rows="2" class="block w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
                            </div>
                            <div>
                                <label for="template_variables" class="block text-sm font-medium text-gray-700 mb-1">Variables (comma-separated)</label>
                                <input type="text" id="template_variables" name="variables_text" class="block w-full px-3 py-2 border border-gray-300 rounded-lg" placeholder="var1,var2">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="template_is_active" name="is_active" value="1" checked class="h-4 w-4 text-indigo-600 rounded">
                                <label for="template_is_active" class="ml-2 text-sm text-gray-700">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 sm:ml-3">Save Template</button>
                        <button type="button" onclick="closeTemplateModal()" class="mt-3 w-full sm:mt-0 sm:w-auto px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="deleteTemplateForm" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        // Save button loading state
        document.getElementById('sms-gateways-form').addEventListener('submit', function() {
            const button = document.getElementById('save-button');
            button.disabled = true;
            button.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Saving...';
        });

        // Test SMS Modal
        function testTemplate(id, name) {
            document.getElementById('test_template_id').value = id;
            document.getElementById('test-modal-title').textContent = 'Test: ' + name;
            document.getElementById('test-result').classList.add('hidden');
            document.getElementById('testModal').classList.remove('hidden');
        }

        function closeTestModal() {
            document.getElementById('testModal').classList.add('hidden');
            document.getElementById('testForm').reset();
        }

        async function sendTestSMS(e) {
            e.preventDefault();
            const id = document.getElementById('test_template_id').value;
            const mobile = document.getElementById('test_mobile').value;
            const resultDiv = document.getElementById('test-result');

            try {
                const response = await fetch(`/admin/sms-gateways/templates/${id}/test`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ mobile })
                });

                const data = await response.json();
                resultDiv.className = `mt-4 p-3 rounded ${data.success ? 'bg-green-50 text-green-800' : 'bg-red-50 text-red-800'}`;
                resultDiv.textContent = data.message;
                resultDiv.classList.remove('hidden');

                if (data.success) {
                    setTimeout(() => closeTestModal(), 2000);
                }
            } catch (error) {
                resultDiv.className = 'mt-4 p-3 rounded bg-red-50 text-red-800';
                resultDiv.textContent = 'Error sending test SMS';
                resultDiv.classList.remove('hidden');
            }
        }

        // Template Modal Functions
        function openAddTemplateModal() {
            document.getElementById('modal-title').textContent = 'Add SMS Template';
            document.getElementById('templateForm').action = "{{ route('admin.sms-templates.store') }}";
            document.getElementById('template_method').value = 'POST';
            document.getElementById('templateForm').reset();
            document.getElementById('template_is_active').checked = true;
            document.getElementById('templateModal').classList.remove('hidden');
        }

        function editTemplate(template) {
            document.getElementById('modal-title').textContent = 'Edit SMS Template';
            document.getElementById('templateForm').action = `/admin/sms-gateways/templates/${template.id}`;
            document.getElementById('template_method').value = 'PUT';
            document.getElementById('template_name').value = template.name;
            document.getElementById('template_slug').value = template.slug;
            document.getElementById('template_template_id').value = template.template_id;
            document.getElementById('template_description').value = template.description || '';
            document.getElementById('template_variables').value = template.variables ? template.variables.join(',') : '';
            document.getElementById('template_is_active').checked = template.is_active;
            document.getElementById('templateModal').classList.remove('hidden');
        }

        function closeTemplateModal() {
            document.getElementById('templateModal').classList.add('hidden');
        }

        function deleteTemplate(id) {
            if (confirm('Are you sure you want to delete this template?')) {
                const form = document.getElementById('deleteTemplateForm');
                form.action = `/admin/sms-gateways/templates/${id}`;
                form.submit();
            }
        }

        document.getElementById('templateForm').addEventListener('submit', function() {
            const variablesText = document.getElementById('template_variables').value;
            if (variablesText) {
                const variables = variablesText.split(',').map(v => v.trim()).filter(v => v);
                const container = document.createElement('div');
                variables.forEach((v, i) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `variables[${i}]`;
                    input.value = v;
                    container.appendChild(input);
                });
                this.appendChild(container);
            }
        });

        document.addEventListener('keydown', e => e.key === 'Escape' && (closeTemplateModal(), closeTestModal()));
    </script>
@endsection