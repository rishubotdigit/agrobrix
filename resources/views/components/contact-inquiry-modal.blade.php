<!-- Inquiry Modal -->
<div id="inquiryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-xl rounded-2xl bg-white border-emerald-100">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Property Inquiry
                </h3>
                <button onclick="closeInquiryModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="inquiryForm">
                <input type="hidden" id="propertyId" name="property_id" value="{{ $propertyId ?? '' }}">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name *</label>
                        <input type="text" name="buyer_name" value="{{ auth()->check() ? auth()->user()->name : '' }}" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                    </div>
                    @if(!auth()->check() || !auth()->user()->mobile)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mobile Number *</label>
                        <input type="text" name="buyer_phone" value="{{ session('inquiry_data')['buyer_phone'] ?? '' }}" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                    </div>
                    @endif
                    @if(!auth()->check() || !auth()->user()->email)
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                        <input type="email" name="buyer_email" value="{{ session('inquiry_data')['buyer_email'] ?? '' }}" required class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors">
                    </div>
                    @endif
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Are you an agent? *</label>
                        <div class="mt-2 space-y-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="buyer_type" value="agent" required class="form-radio text-emerald-600 focus:ring-emerald-500">
                                <span class="ml-2 text-gray-700">Yes</span>
                            </label>
                            <label class="inline-flex items-center ml-6">
                                <input type="radio" name="buyer_type" value="buyer" required class="form-radio text-emerald-600 focus:ring-emerald-500" checked>
                                <span class="ml-2 text-gray-700">No</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Message</label>
                        <textarea name="additional_message" rows="3" class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"></textarea>
                    </div>
                </div>
                <div class="flex justify-end mt-8 space-x-3">
                    <button type="button" onclick="closeInquiryModal()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Submit Inquiry</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- OTP Modal -->
<div id="otpModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-xl rounded-2xl bg-white border-emerald-100">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Verify Phone Number
                </h3>
                <button onclick="closeOtpModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-sm text-gray-600 mb-6">Please enter the 6-digit OTP sent to your mobile number.</p>
            <form id="otpForm">
                <div class="mb-6">
                    <input type="text" id="otpInput" maxlength="6" class="w-full px-4 py-4 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-center text-2xl tracking-widest font-mono" placeholder="000000">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeOtpModal()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Cancel</button>
                    <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">Verify</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Confirmation Modal - Commented out as per flow change --}}
{{-- <div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-lg shadow-xl rounded-2xl bg-white border-emerald-100">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
                    <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Inquiry Submitted Successfully
                </h3>
                <button onclick="closeConfirmationModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div id="confirmationDetails" class="space-y-4 mb-6">
                <!-- Confirmation details will be loaded here -->
            </div>
            <div class="flex justify-end gap-3">
                <button onclick="closeConfirmationModal()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Close</button>
                <button id="viewContactBtnModal" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">View Contact</button>
            </div>
        </div>
    </div>
</div> --}}

<!-- Contact Modal -->
<div id="contactModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-xl rounded-2xl bg-white border-emerald-100">
        <div class="mt-3">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Contact Details
            </h3>
            <div id="contactDetails" class="space-y-3 text-gray-700">
                <!-- Contact details will be loaded here -->
            </div>
            <div class="flex justify-end mt-6">
                <button onclick="closeContactModal()" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">Close</button>
            </div>
        </div>
    </div>
</div>

<script>

    // Simple handleContactClick function - directly using Blade conditions
    function handleContactClick(propertyId, ownerId, agentId) {
        console.log('Contact button clicked for property:', propertyId, 'owner:', ownerId, 'agent:', agentId);

        @auth
            var userId = '{{ Auth::id() }}';
            var isOwnerOrAgent = (userId == ownerId) || (userId == agentId);

            if (isOwnerOrAgent) {
                // User is owner or agent - directly fetch contact
                fetchContactDirectly(propertyId);
            } else {
                @if(Auth::user()->role === 'buyer')
                    // User is logged in and is a buyer - check if inquiry exists
                    fetch(`/properties/${propertyId}/inquiry/status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            fetchContactDirectly(propertyId);
                        } else {
                            // Prompt for confirmation before using credit
                            if(confirm('Do you want to view contact details? This will use 1 contact credit.')) {
                                fetchContactDirectly(propertyId);
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error checking inquiry status:', error);
                        // Fallback: prompt anyway
                        if(confirm('Do you want to view contact details?')) {
                            fetchContactDirectly(propertyId);
                        }
                    });
                @else
                    // User is logged in as agent - fetch contact directly
                    fetchContactDirectly(propertyId);
                @endif
            }
        @else
            // User is not logged in - redirect to register
            if(confirm('Please register or login to view contact details.')) {
                window.location.href = "{{ route('register') }}";
            }
        @endauth
    }

    // Make function globally accessible
    window.handleContactClick = handleContactClick;

    function openInquiryModal(propertyId) {
        console.log('Opening inquiry modal for property:', propertyId);
        if (propertyId) {
            document.getElementById('propertyId').value = propertyId;
        }
        document.getElementById('inquiryModal').classList.remove('hidden');
    }

    function closeInquiryModal() {
        document.getElementById('inquiryModal').classList.add('hidden');
        document.getElementById('inquiryForm').reset();
    }

    function openOtpModal() {
        document.getElementById('inquiryModal').classList.add('hidden');
        document.getElementById('otpModal').classList.remove('hidden');
    }

    function closeOtpModal() {
        document.getElementById('otpModal').classList.add('hidden');
        document.getElementById('otpForm').reset();
    }

    // showConfirmationModal and closeConfirmationModal removed as confirmation modal is no longer used

    function showContactDetails(contact) {
        const contactHtml = `
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Name</p>
                        <p class="font-semibold text-gray-900">${contact.contact_name}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-emerald-50 rounded-lg">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wide">Mobile</p>
                        <p class="font-semibold text-gray-900">${contact.contact_mobile}</p>
                    </div>
                </div>
            </div>
        `;
        document.getElementById('contactDetails').innerHTML = contactHtml;
        document.getElementById('contactModal').classList.remove('hidden');
    }

    function closeContactModal() {
        document.getElementById('contactModal').classList.add('hidden');
    }

    function fetchContactDirectly(propertyId) {
        fetch(`/properties/${propertyId}/contact`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.contact) {
                showContactDetails(data.contact);
            } else if (data.error) {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.error);
                }
            } else {
                alert('An error occurred');
            }
        })
        .catch(error => {
            console.error('Error fetching contact:', error);
            alert('An error occurred while fetching contact details');
        });
    }

    function toggleWishlist(propertyId, buttonElement) {
        const heartIcon = buttonElement.querySelector('svg');
        const isInWishlist = heartIcon.classList.contains('fill-current');
    
        const url = isInWishlist 
            ? `/buyer/wishlist/remove?property_id=${propertyId}`
            : '/buyer/wishlist/add';
        const method = isInWishlist ? 'DELETE' : 'POST';
        const body = isInWishlist ? null : JSON.stringify({ property_id: propertyId });

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
            body: body
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (isInWishlist) {
                    heartIcon.classList.remove('text-red-500', 'fill-current');
                    heartIcon.classList.add('text-white');
                    heartIcon.setAttribute('fill', 'none');
                } else {
                    heartIcon.classList.remove('text-white');
                    heartIcon.classList.add('text-red-500', 'fill-current');
                    heartIcon.setAttribute('fill', 'currentColor');
                }
            } else {
                alert(data.error || 'An error occurred');
            }
        })
        .catch(error => {
            console.error('Error toggling wishlist:', error);
            alert('An error occurred while updating wishlist');
        });
    }

    window.toggleWishlist = toggleWishlist;

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Form submission
        const inquiryForm = document.getElementById('inquiryForm');
        if (inquiryForm) {
            inquiryForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                const propertyId = formData.get('property_id');

                fetch(`/properties/${propertyId}/inquiry/submit`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.otp_required) {
                            openOtpModal();
                        } else {
                            closeInquiryModal();
                            fetchContactDirectly(propertyId);
                        }
                    } else {
                        alert(data.message || 'An error occurred');
                    }
                })
                .catch(error => {
                    console.error('Error submitting inquiry:', error);
                    alert('An error occurred while submitting inquiry');
                });
            });
        }

        // OTP verification
        const otpForm = document.getElementById('otpForm');
        if (otpForm) {
            otpForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const otp = document.getElementById('otpInput').value;
                const propertyId = document.getElementById('propertyId').value;

                fetch(`/properties/${propertyId}/inquiry/verify-otp`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                    },
                    body: new URLSearchParams({ otp: otp })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeOtpModal();
                        const propertyId = document.getElementById('propertyId').value;
                        fetchContactDirectly(propertyId);
                    } else {
                        alert(data.message || 'Invalid OTP');
                    }
                })
                .catch(error => {
                    console.error('Error verifying OTP:', error);
                    alert('An error occurred while verifying OTP');
                });
            });
        }

        // View Contact button in confirmation modal - removed as confirmation modal is no longer used

        // Close modals when clicking outside
        ['inquiryModal', 'otpModal', 'contactModal'].forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        const closeFunc = modalId === 'inquiryModal' ? closeInquiryModal :
                                        modalId === 'otpModal' ? closeOtpModal :
                                        modalId === 'confirmationModal' ? closeConfirmationModal :
                                        closeContactModal;
                        closeFunc();
                    }
                });
            }
        });
    });
</script>