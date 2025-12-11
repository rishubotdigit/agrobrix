# Agrobrix API Documentation

This document provides comprehensive API documentation for the Agrobrix Property Management System.

## Authentication

All API requests require authentication except for authentication endpoints themselves. Include the authentication token in the request headers.

### Headers
```
Authorization: Bearer {token}
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}  // For web requests
```

## Authentication Endpoints

### Register User
```http
POST /register
```

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "mobile": "1234567890",
  "password": "password123",
  "password_confirmation": "password123",
  "role": "buyer|owner|agent"
}
```

**Response:**
```json
{
  "message": "Registration successful. Please verify your email with OTP.",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "buyer"
  }
}
```

### Login
```http
POST /login
```

**Request Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

**Response:**
```json
{
  "message": "OTP sent to your registered mobile number",
  "requires_otp": true
}
```

### Send OTP
```http
POST /otp/send
```

**Request Body:**
```json
{
  "mobile": "1234567890"
}
```

**Response:**
```json
{
  "message": "OTP sent successfully"
}
```

### Verify OTP
```http
POST /otp/verify
```

**Request Body:**
```json
{
  "mobile": "1234567890",
  "otp": "123456"
}
```

**Response:**
```json
{
  "message": "Login successful",
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "buyer"
  },
  "token": "bearer_token_here"
}
```

### Logout
```http
POST /logout
```

**Response:**
```json
{
  "message": "Logged out successfully"
}
```

## Property Endpoints

### Get Properties (Buyer API)
```http
GET /buyer/api/properties
```

**Query Parameters:**
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 10)

**Response:**
```json
{
  "properties": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "title": "Beautiful Apartment",
        "description": "Spacious 2BHK apartment",
        "price": 50000,
        "location": "Downtown",
        "owner": {
          "name": "Property Owner",
          "email": "owner@example.com"
        },
        "created_at": "2025-01-01T00:00:00.000000Z"
      }
    ],
    "per_page": 10,
    "total": 50
  },
  "usage": {
    "contacts_viewed": 5,
    "max_contacts": 10
  }
}
```

### View Property Contact
```http
POST /buyer/properties/{property}/contact
```

**Response (Success):**
```json
{
  "contact": {
    "owner_name": "John Doe",
    "owner_email": "john@example.com",
    "owner_mobile": "1234567890"
  },
  "usage": {
    "contacts_viewed": 6,
    "max_contacts": 10
  }
}
```

**Response (Payment Required):**
```json
{
  "error": "Maximum contacts viewed limit reached. Payment required to view more contacts.",
  "payment_required": true,
  "amount": 10.00,
  "current": 10,
  "limit": 10
}
```

## Admin Endpoints

### User Management

#### List Users
```http
GET /admin/users
```

**Query Parameters:**
- `page` (optional): Page number
- `role` (optional): Filter by role (admin|owner|agent|buyer)

**Response:**
```json
{
  "users": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "buyer",
        "plan": {
          "name": "Basic Plan"
        },
        "created_at": "2025-01-01T00:00:00.000000Z"
      }
    ]
  }
}
```

#### Create User
```http
POST /admin/users
```

**Request Body:**
```json
{
  "name": "New User",
  "email": "newuser@example.com",
  "mobile": "1234567890",
  "password": "password123",
  "role": "buyer",
  "plan_id": 1
}
```

#### Update User
```http
PUT /admin/users/{user}
```

**Request Body:**
```json
{
  "name": "Updated Name",
  "email": "updated@example.com",
  "role": "owner"
}
```

#### Delete User
```http
DELETE /admin/users/{user}
```

### Property Management

#### List Properties
```http
GET /admin/properties
```

**Response:**
```json
{
  "properties": [
    {
      "id": 1,
      "title": "Property Title",
      "owner": {
        "name": "Owner Name"
      },
      "versions_count": 3,
      "latest_version": {
        "status": "pending"
      }
    }
  ]
}
```

#### Get Property Details
```http
GET /admin/properties/{property}
```

#### Get Property Versions
```http
GET /admin/properties/{property}/versions
```

**Response:**
```json
{
  "versions": [
    {
      "id": 1,
      "version": 1,
      "status": "approved",
      "data": {
        "title": "Property Title",
        "description": "Description",
        "price": 50000
      },
      "created_at": "2025-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### Approve Version
```http
POST /admin/versions/{version}/approve
```

#### Reject Version
```http
POST /admin/versions/{version}/reject
```

#### Bulk Approve Versions
```http
POST /admin/versions/bulk-approve
```

**Request Body:**
```json
{
  "version_ids": [1, 2, 3]
}
```

#### Bulk Reject Versions
```http
POST /admin/versions/bulk-reject
```

**Request Body:**
```json
{
  "version_ids": [1, 2, 3]
}
```

#### Get Version Diff
```http
GET /admin/versions/{version}/diff
```

**Response:**
```json
{
  "diff": {
    "title": {
      "old": "Old Title",
      "new": "New Title"
    },
    "price": {
      "old": 45000,
      "new": 50000
    }
  }
}
```

### Settings Management

#### Get Settings
```http
GET /admin/settings
```

**Response:**
```json
{
  "settings": {
    "site_name": "Agrobrix",
    "contact_email": "admin@agrobrix.com",
    "max_contacts_per_plan": {
      "basic": 10,
      "premium": 50
    }
  }
}
```

#### Update Settings
```http
POST /admin/settings
```

**Request Body:**
```json
{
  "site_name": "New Site Name",
  "contact_email": "newadmin@example.com"
}
```

## Todo Endpoints

### List Todos
```http
GET /{role}/todos
```

Where `{role}` is one of: admin, agent, buyer, owner

**Response:**
```json
{
  "todos": [
    {
      "id": 1,
      "title": "Review property listing",
      "description": "Check the new property submission",
      "status": "pending",
      "due_date": "2025-01-15",
      "created_at": "2025-01-01T00:00:00.000000Z"
    }
  ]
}
```

### Create Todo
```http
POST /{role}/todos
```

**Request Body:**
```json
{
  "title": "New Task",
  "description": "Task description",
  "due_date": "2025-01-15"
}
```

### Update Todo
```http
PUT /{role}/todos/{todo}
```

**Request Body:**
```json
{
  "title": "Updated Task",
  "status": "completed"
}
```

### Delete Todo
```http
DELETE /{role}/todos/{todo}
```

## Payment Endpoints

### Initiate Contact Payment
```http
POST /payments/contact/{property}
```

**Response:**
```json
{
  "order_id": "order_xyz123",
  "amount": 10.00,
  "currency": "INR",
  "razorpay_key": "rzp_test_xxx"
}
```

### Payment Success
```http
POST /payments/success
```

**Request Body:**
```json
{
  "razorpay_payment_id": "pay_xxx",
  "razorpay_order_id": "order_xyz123",
  "razorpay_signature": "signature_xxx",
  "property_id": 1
}
```

**Response:**
```json
{
  "message": "Payment successful",
  "contact": {
    "owner_name": "John Doe",
    "owner_email": "john@example.com",
    "owner_mobile": "1234567890"
  }
}
```

### Payment Webhook
```http
POST /payments/webhook
```

This endpoint handles Razorpay webhooks for payment confirmations.

## Error Responses

### Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."],
    "password": ["The password must be at least 8 characters."]
  }
}
```

### Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### Forbidden
```json
{
  "message": "This action is unauthorized."
}
```

### Not Found
```json
{
  "message": "Resource not found."
}
```

### Server Error
```json
{
  "message": "Internal server error."
}
```

## Rate Limiting

API endpoints are rate limited. Standard limits:
- Authentication endpoints: 10 requests per minute
- General endpoints: 60 requests per minute
- Payment endpoints: 30 requests per minute

Rate limit headers are included in responses:
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640995200
```

## Data Formats

### DateTime Format
All datetime fields use ISO 8601 format:
```
2025-01-01T00:00:00.000000Z
```

### Currency
All monetary values are in INR (Indian Rupees) with 2 decimal places.

### Pagination
Paginated responses include:
- `current_page`: Current page number
- `per_page`: Items per page
- `total`: Total number of items
- `last_page`: Last page number
- `from`: First item number on current page
- `to`: Last item number on current page

## Webhooks

### Razorpay Payment Webhook
The system listens for payment confirmation webhooks from Razorpay to automatically update payment status and grant access to contacts.

**Webhook URL:** `/payments/webhook`

**Events Handled:**
- `payment.captured`
- `payment.failed`

## SDKs and Libraries

### JavaScript Integration

For frontend integration, use the following JavaScript setup for payments:

```javascript
// Razorpay payment integration
function initiatePayment(orderData) {
  const options = {
    key: orderData.razorpay_key,
    amount: orderData.amount * 100, // Amount in paisa
    currency: orderData.currency,
    order_id: orderData.order_id,
    name: 'Agrobrix',
    description: 'Contact Access Payment',
    handler: function(response) {
      // Handle payment success
      fetch('/payments/success', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
          razorpay_payment_id: response.razorpay_payment_id,
          razorpay_order_id: response.razorpay_order_id,
          razorpay_signature: response.razorpay_signature,
          property_id: propertyId
        })
      });
    }
  };
  const rzp = new Razorpay(options);
  rzp.open();
}
```

## Versioning

The API follows REST conventions. Future versions will be indicated by URL prefixes:
- Current: No prefix
- Future v2: `/api/v2/`

## Support

For API support and questions:
- Check this documentation
- Create an issue in the repository
- Contact the development team
