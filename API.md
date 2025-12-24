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

## Business Model & Plans

The Agrobrix platform operates on a comprehensive subscription-based business model where users pay for access to property contacts and listing capabilities based on role-specific plans. The system supports three user roles (Buyer, Owner, Agent) with distinct capabilities and pricing structures.

### Subscription System Overview

The subscription system is built around the following core components:

1. **Role-Based Plans**: Each user role (buyer, owner, agent) has tailored plans with specific capabilities
2. **Capability-Based Limits**: All restrictions are enforced through a flexible capability system
3. **Add-on Extensions**: Users can purchase additional capabilities beyond their plan limits
4. **Plan Purchases**: Subscriptions are managed through plan purchases with activation and expiration dates
5. **Combined Capabilities**: User capabilities are calculated by combining active plan capabilities with purchased add-ons

### Plan Structure and User Capabilities

#### Plan Model Structure
Each plan contains the following key attributes:

- `name`: Plan display name
- `role`: Target user role (buyer|owner|agent)
- `price`: Current selling price in INR
- `original_price`: Original price before discount (optional)
- `discount`: Discount percentage (optional)
- `validity_days`: Plan validity period in days
- `capabilities`: JSON object defining user limits and features
- `features`: Array of feature descriptions
- `persona`: Target user description (for buyers)
- `contacts_to_unlock`: Number of contact views (for buyers)

#### Capability Types
The system supports the following capability types:

- `max_contacts`: Maximum number of contact views allowed
- `max_listings`: Maximum number of property listings allowed
- `max_featured_listings`: Maximum number of featured listings allowed
- `priority_support`: Boolean flag for priority customer support

#### User Capability Calculation
User capabilities are dynamically calculated by combining:
1. **Active Plan Capabilities**: From currently active plan purchases
2. **Addon Capabilities**: From purchased and active add-ons

Capabilities are merged additively for numeric values and logically for boolean values.

### Detailed Plan Specifications

#### Buyer Plans
Buyers pay for access to property owner contact information with tiered limits based on their investment needs.

##### Starter Plan
- **Price**: ₹249 (Original: ₹499, 50% discount)
- **Validity**: 30 days
- **Contact Views**: 5
- **Target Persona**: Testing the waters for a specific plot
- **Capabilities**:
  - `max_contacts`: 5

##### Explorer Plan
- **Price**: ₹399 (Original: ₹999, 60% discount)
- **Validity**: 90 days
- **Contact Views**: 15
- **Target Persona**: Serious buyers comparing multiple locations
- **Capabilities**:
  - `max_contacts`: 15

##### Investor Plan
- **Price**: ₹749 (Original: ₹2499, 70% discount)
- **Validity**: 180 days
- **Contact Views**: 40
- **Target Persona**: Professional farmers or long-term investors
- **Capabilities**:
  - `max_contacts`: 40

#### Owner Plans
Property owners pay for the ability to list their properties with varying limits on listings and featured promotions.

##### Basic Plan
- **Price**: ₹0 (Free)
- **Validity**: 30 days
- **Features**:
  - Standard Listing
  - 1 Photo upload
  - Engagement dashboard
- **Capabilities**:
  - `max_listings`: 1

##### Premium Plan
- **Price**: ₹0 (100% discount, Original: ₹99)
- **Validity**: 30 days
- **Features**:
  - Top of Search positioning
  - 5 Photos upload
  - "Who Viewed My Contact" visibility
- **Capabilities**:
  - `max_listings`: 3
  - `max_featured_listings`: 1

##### Elite Plan
- **Price**: ₹249 (Original: ₹499, 50% discount)
- **Validity**: 90 days
- **Features**:
  - Highlighted Tag on listings
  - Social Media Shoutout
  - Priority Support
  - Post 3 Properties
- **Capabilities**:
  - `max_listings`: 3
  - `max_featured_listings`: 3

#### Agent Plans
Real estate agents have comprehensive plans covering both contact access and property listing capabilities.

##### Basic Plan
- **Price**: ₹0 (Free)
- **Validity**: 30 days
- **Features**:
  - Standard Listing
  - 1 Photo upload
  - Engagement dashboard
- **Capabilities**:
  - `max_contacts`: 5
  - `max_listings`: 1

##### Professional Plan
- **Price**: ₹999 (Original: ₹1999, 50% discount)
- **Validity**: 90 days
- **Features**:
  - 15 Property Posts
  - Analytics Dashboard
- **Capabilities**:
  - `max_contacts`: 50
  - `max_listings`: 15

##### Business Plan
- **Price**: ₹1999 (Original: ₹4999, 60% discount)
- **Validity**: 180 days
- **Features**:
  - 50 Property Posts
  - Agent Profile Page
- **Capabilities**:
  - `max_contacts`: 150
  - `max_listings`: 50

##### Enterprise Plan
- **Price**: ₹3749 (Original: ₹14999, 75% discount)
- **Validity**: 360 days
- **Features**:
  - 150 Property Posts
  - Verified Partner Badge
- **Capabilities**:
  - `max_contacts`: 500
  - `max_listings`: 150

### Add-on System

Users can extend their capabilities beyond plan limits by purchasing add-ons. Add-ons provide additional capacity that stacks with existing plan capabilities.

#### Available Add-ons

##### Contact Add-ons
- **Extra 25 Contacts**: ₹249
  - Adds 25 additional contact views
  - Capabilities: `max_contacts: 25`

- **Extra 50 Contacts**: ₹449
  - Adds 50 additional contact views
  - Capabilities: `max_contacts: 50`

- **Extra 100 Contacts**: ₹799
  - Adds 100 additional contact views
  - Capabilities: `max_contacts: 100`

##### Listing Add-ons
- **Extra 5 Property Listings**: ₹199
  - Adds 5 additional property listings
  - Capabilities: `max_listings: 5`

##### Featured Listing Add-ons
- **Extra 5 Featured Listings**: ₹299
  - Adds 5 additional featured listing slots
  - Capabilities: `max_featured_listings: 5`

##### Support Add-ons
- **Priority Support**: ₹99
  - Enables priority customer support
  - Capabilities: `priority_support: true`

### Subscription Management

#### Plan Purchase Lifecycle
1. **Purchase**: User selects and pays for a plan
2. **Approval**: Payment may require admin approval (for certain payment methods)
3. **Activation**: Plan becomes active with capabilities applied
4. **Usage Tracking**: System tracks usage against limits (contacts viewed, listings created)
5. **Expiration**: Plan expires after validity period
6. **Renewal**: Users can purchase new plans or extend existing ones

#### Capability Enforcement
- **Pre-checks**: API endpoints validate capabilities before allowing actions
- **Usage Limits**: Contact viewing and listing creation are restricted by plan limits
- **Payment Triggers**: Exceeding limits may trigger payment flows for additional access
- **Real-time Updates**: Capability calculations update in real-time as add-ons are purchased

#### Payment Integration
- **Razorpay Integration**: Primary payment gateway for plan purchases
- **Multiple Payment Methods**: Support for cards, UPI, net banking
- **Approval Workflows**: Certain payment types require admin approval
- **Invoice Generation**: Automatic invoice creation and email delivery

### API Integration Points

#### Plan Management Endpoints
- `GET /plans`: Retrieve available plans by role
- `POST /plan-purchases`: Purchase a plan
- `GET /user/capabilities`: Get current user capabilities

#### Add-on Management Endpoints
- `GET /addons`: Retrieve available add-ons
- `POST /user/addons`: Purchase an add-on

#### Usage Tracking
- Contact viewing limits are tracked in real-time
- Property listing counts are validated before creation
- Featured listing usage is monitored per plan purchase

### Business Rules

#### Contact Access
- Buyers must have sufficient contact credits to view owner information
- Contact viewing consumes credits from the user's active plan/add-ons
- Exceeding limits triggers payment requirements

#### Property Listings
- Owners/Agents cannot create listings beyond their plan limits
- Featured listings are restricted by available featured slots
- Listing limits reset with new plan purchases

#### Plan Validity
- Plans expire after the specified validity period
- Expired plans lose all capabilities
- Users must purchase new plans to regain access

#### Add-on Stacking
- Multiple add-ons of the same type stack additively
- Add-ons expire independently of plans
- Add-on capabilities combine with plan capabilities

This comprehensive system ensures fair usage while providing flexibility for users to scale their capabilities based on their needs.

## Versioning

The API follows REST conventions. Future versions will be indicated by URL prefixes:
- Current: No prefix
- Future v2: `/api/v2/`

## Support

For API support and questions:
- Check this documentation
- Create an issue in the repository
- Contact the development team
