# Agrobrix UI Components

This document provides comprehensive documentation for the reusable UI components and design patterns used in the Agrobrix Property Management System.

## Design System

### Color Palette

The application uses a custom Tailwind CSS configuration with the following color scheme:

- **Primary Background**: `bg-[#FDFDFC]` (light mode), `bg-[#0a0a0a]` (dark mode)
- **Text Colors**: `text-[#1b1b18]` (light), `text-[#EDEDEC]` (dark)
- **Accent**: `text-[#f53003]` (red/orange accents)
- **Borders**: `border-[#19140035]` (light), `border-[#3E3E3A]` (dark)

### Typography

- **Font Family**: Instrument Sans (via Google Fonts)
- **Headings**: Responsive text sizes with `font-medium`
- **Body Text**: Standard Tailwind text utilities

### Spacing

- **Container**: `max-w-[335px]` (mobile), `lg:max-w-4xl` (desktop)
- **Padding**: `p-6` (standard), `lg:p-8` or `lg:p-20` (large screens)
- **Margins**: `mb-6`, `mb-4` for consistent spacing

## Layout Components

### Dashboard Layout

#### Buyer Dashboard Layout
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-4">
        <!-- Header -->
        <header class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Buyer Dashboard</h1>
            <p class="text-gray-600 mt-2">Browse and purchase properties.</p>
        </header>

        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Cards go here -->
        </div>
    </div>
</body>
</html>
```

#### Admin Layout with Sidebar
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white hidden md:block">
            <div class="p-4">
                <h2 class="text-xl font-bold">Admin Panel</h2>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" 
                   class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    Dashboard
                </a>
                <!-- More navigation items -->
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-4 md:p-6">
            @yield('content')
        </div>
    </div>
</body>
</html>
```

## Card Components

### Statistics Card
```html
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold text-gray-700">Contacts Viewed</h2>
    <p class="text-gray-500">Contacts viewed: {{ $contactsViewed }} / {{ $maxContacts }}</p>
    @if($contactsViewed >= $maxContacts)
        <p class="text-red-500 text-sm">Limit reached - upgrade your plan</p>
    @endif
</div>
```

### Todo Card
```html
<div class="flex items-center justify-between p-3 bg-gray-50 rounded todo-item" data-id="{{ $todo->id }}">
    <div class="flex-1">
        <h4 class="font-medium">{{ $todo->title }}</h4>
        <p class="text-sm text-gray-600">{{ $todo->description }}</p>
        @if($todo->due_date)
            <p class="text-xs text-gray-500">Due: {{ $todo->due_date->format('M d, Y') }}</p>
        @endif
    </div>
    <div class="flex items-center space-x-2">
        <select class="status-select text-xs p-1 border rounded" data-id="{{ $todo->id }}">
            <option value="pending" {{ $todo->status == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="in_progress" {{ $todo->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="completed" {{ $todo->status == 'completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <button class="delete-todo text-red-500 hover:text-red-700" data-id="{{ $todo->id }}">×</button>
    </div>
</div>
```

## Button Components

### Primary Button
```html
<button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    Add Todo
</button>
```

### Secondary Button
```html
<button class="bg-gray-300 hover:bg-gray-400 text-gray-800 py-2 px-4 rounded">
    Cancel
</button>
```

### Danger Button
```html
<button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
    Delete
</button>
```

### Success Button
```html
<button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
    Browse Properties
</button>
```

### Disabled Button
```html
<button class="bg-green-500 text-white font-bold py-2 px-4 rounded opacity-50 cursor-not-allowed" disabled>
    Browse Properties
</button>
```

## Form Components

### Input Field
```html
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Title</label>
    <input type="text" 
           name="title" 
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
           required>
</div>
```

### Textarea
```html
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
    <textarea name="description" 
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
</div>
```

### Select Dropdown
```html
<select class="status-select text-xs p-1 border rounded" data-id="{{ $todo->id }}">
    <option value="pending" {{ $todo->status == 'pending' ? 'selected' : '' }}>Pending</option>
    <option value="in_progress" {{ $todo->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
    <option value="completed" {{ $todo->status == 'completed' ? 'selected' : '' }}>Completed</option>
</select>
```

### Date Input
```html
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">Due Date</label>
    <input type="date" 
           name="due_date" 
           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
</div>
```

## Modal Components

### Modal Overlay
```html
<div id="todoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Todo</h3>
            <!-- Modal content -->
            <div class="flex justify-end">
                <button type="button" onclick="closeTodoModal()" class="mr-2 px-4 py-2 text-gray-500 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Add Todo</button>
            </div>
        </div>
    </div>
</div>
```

### Payment Modal
```html
<div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Pay to View More Contacts</h3>
            <p class="text-sm text-gray-500 mb-4">Pay ₹10 to unlock additional contact views.</p>
            <div class="flex justify-end space-x-2">
                <button onclick="closePaymentModal()" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400">Cancel</button>
                <button id="payButton" onclick="initiatePayment()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Pay ₹10</button>
            </div>
        </div>
    </div>
</div>
```

## Navigation Components

### Sidebar Navigation
```html
<nav class="mt-4">
    <a href="{{ route('admin.dashboard') }}" 
       class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
        Dashboard
    </a>
    <a href="{{ route('admin.users.index') }}" 
       class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}">
        Users
    </a>
    <a href="{{ route('admin.properties.index') }}" 
       class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.properties.*') ? 'bg-gray-700' : '' }}">
        Properties
    </a>
    <a href="{{ route('admin.todos.index') }}" 
       class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.todos.*') ? 'bg-gray-700' : '' }}">
        Todos
    </a>
    <a href="{{ route('admin.settings.index') }}" 
       class="block px-4 py-2 hover:bg-gray-700 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700' : '' }}">
        Settings
    </a>
    <a href="{{ route('logout') }}" class="block px-4 py-2 hover:bg-gray-700">Logout</a>
</nav>
```

## Grid Systems

### Dashboard Grid
```html
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <!-- Statistics cards -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700">Contacts Viewed</h2>
        <p class="text-gray-500">Contacts viewed: 5 / 10</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700">Purchases</h2>
        <p class="text-gray-500">Total purchases: 0</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700">Payments</h2>
        <p class="text-gray-500">Pending payments: 0</p>
    </div>
    
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-semibold text-gray-700">Todos</h2>
        <p class="text-gray-500">Pending tasks: 3</p>
        <a href="{{ route('buyer.todos.index') }}" class="text-blue-500 hover:text-blue-700 text-sm">View All</a>
    </div>
</div>
```

## Responsive Design Patterns

### Mobile-First Approach
- Base styles for mobile (`grid-cols-1`)
- Medium screens and up (`md:grid-cols-2`)
- Large screens and up (`lg:grid-cols-4`)

### Responsive Navigation
```html
<!-- Hidden on mobile, visible on medium and up -->
<div class="w-64 bg-gray-800 text-white hidden md:block">
    <!-- Sidebar content -->
</div>

<!-- Main content adjusts based on sidebar -->
<div class="flex-1 p-4 md:p-6">
    <!-- Content -->
</div>
```

### Responsive Text
```html
<h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-gray-800">
    Responsive Heading
</h1>
```

## JavaScript Components

### Todo Management
```javascript
// Add todo
$('#todoForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: '{{ route("buyer.todos.store") }}',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            closeTodoModal();
            location.reload();
        },
        error: function(xhr) {
            alert('Error adding todo');
        }
    });
});

// Update todo status
$('.status-select').on('change', function() {
    const todoId = $(this).data('id');
    const status = $(this).val();
    $.ajax({
        url: '{{ route("buyer.todos.update", ":id") }}'.replace(':id', todoId),
        method: 'PUT',
        data: { status: status, _token: '{{ csrf_token() }}' },
        success: function(response) {
            // Update UI
        },
        error: function(xhr) {
            alert('Error updating todo');
        }
    });
});

// Delete todo
$('.delete-todo').on('click', function() {
    if (confirm('Are you sure you want to delete this todo?')) {
        const todoId = $(this).data('id');
        $.ajax({
            url: '{{ route("buyer.todos.destroy", ":id") }}'.replace(':id', todoId),
            method: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                $(`.todo-item[data-id="${todoId}"]`).remove();
            },
            error: function(xhr) {
                alert('Error deleting todo');
            }
        });
    }
});
```

### Modal Management
```javascript
function openTodoModal() {
    document.getElementById('todoModal').classList.remove('hidden');
}

function closeTodoModal() {
    document.getElementById('todoModal').classList.add('hidden');
    document.getElementById('todoForm').reset();
}

// Close modal when clicking outside
document.getElementById('paymentModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePaymentModal();
    }
});
```

### Razorpay Payment Integration
```javascript
function initiatePayment() {
    const payButton = document.getElementById('payButton');
    payButton.disabled = true;
    payButton.textContent = 'Processing...';

    // Implementation for payment initiation
    alert('Payment integration would be triggered when viewing a specific property contact.');
    closePaymentModal();
    payButton.disabled = false;
    payButton.textContent = 'Pay ₹10';
}
```

## Utility Classes

### Common Patterns

#### Flexbox Utilities
```html
<!-- Center content -->
<div class="flex items-center justify-center min-h-screen">

<!-- Space between items -->
<div class="flex justify-between items-center">

<!-- Vertical stack -->
<div class="flex flex-col space-y-4">
```

#### Spacing Utilities
```html
<!-- Margin bottom -->
<div class="mb-4">Content</div>

<!-- Padding all sides -->
<div class="p-6">Content</div>

<!-- Padding x-axis -->
<div class="px-5">Content</div>

<!-- Padding y-axis -->
<div class="py-2">Content</div>
```

#### Color Utilities
```html
<!-- Text colors -->
<p class="text-gray-600">Gray text</p>
<p class="text-red-500">Red text</p>
<p class="text-blue-500">Blue text</p>

<!-- Background colors -->
<div class="bg-white">White background</div>
<div class="bg-gray-100">Light gray background</div>
<div class="bg-green-500">Green background</div>
```

#### Border Utilities
```html
<!-- Border -->
<div class="border border-gray-300 rounded">

<!-- Border radius -->
<div class="rounded-lg">

<!-- Shadow -->
<div class="shadow-md">
```

## Accessibility

### ARIA Labels
```html
<button aria-label="Add new todo item">
    <span>+</span>
</button>
```

### Focus Management
```html
<input class="focus:outline-none focus:shadow-outline" />
```

### Screen Reader Support
```html
<span class="sr-only">Loading...</span>
```

## Dark Mode Support

The application includes dark mode support using Tailwind's dark mode utilities:

```html
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC]">
    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]">
        <!-- Content -->
    </div>
</body>
```

## Performance Considerations

### CSS Optimization
- Use Tailwind's purging to remove unused styles
- Minimize custom CSS
- Leverage utility-first approach

### JavaScript Optimization
- Use event delegation for dynamic elements
- Minimize DOM manipulation
- Implement lazy loading where appropriate

### Image Optimization
- Use appropriate image formats
- Implement responsive images
- Consider lazy loading for below-the-fold content

## Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## Customization

### Theme Customization
Modify `tailwind.config.js` to customize colors, fonts, and spacing:

```javascript
module.exports = {
  theme: {
    extend: {
      colors: {
        primary: '#f53003',
        secondary: '#1b1b18',
      },
      fontFamily: {
        sans: ['Instrument Sans', 'sans-serif'],
      },
    },
  },
}
```

### Component Libraries
Consider integrating with component libraries like:
- Headless UI for accessible components
- Heroicons for consistent iconography
- Tailwind UI for pre-built components

This documentation provides a foundation for maintaining consistent UI patterns across the Agrobrix application. All components follow responsive design principles and accessibility guidelines.
