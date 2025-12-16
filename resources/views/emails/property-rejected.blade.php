<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Submission Update</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #ef4444; }
        .logo { font-size: 28px; font-weight: 600; color: #059669; margin-bottom: 10px; }
        .content { margin-bottom: 30px; }
        .property-details { background-color: #fef2f2; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ef4444; }
        .cta-button { display: inline-block; background-color: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500; margin: 10px 5px 10px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; text-align: center; }
        .footer a { color: #059669; text-decoration: none; }
        @media (max-width: 600px) { .container { padding: 20px; } .logo { font-size: 24px; } .cta-button { display: block; margin: 10px 0; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1 style="color: #ef4444; margin: 0;">Property Submission Update</h1>
        </div>

        <div class="content">
            <p>Dear {{ $property->contact_name ?? ($property->owner->name ?? 'Property Owner') }},</p>

            <p>We regret to inform you that after careful review, we are unable to approve your property listing at this time. This decision may be due to incomplete information, policy violations, or other criteria that do not meet our platform standards.</p>

            <div class="property-details">
                <h3 style="margin-top: 0; color: #ef4444;">Property Details:</h3>
                <p><strong>Title:</strong> {{ $property->title }}</p>
                <p><strong>Location:</strong> {{ $property->city }}, {{ $property->state }}</p>
                <p><strong>Price:</strong> ₹{{ number_format($property->price) }}</p>
                <p><strong>Land Type:</strong> {{ $property->land_type }}</p>
                <p><strong>Plot Area:</strong> {{ $property->plot_area }} {{ $property->plot_area_unit }}</p>
            </div>

            <p>You can review and update your property details in your dashboard and resubmit for approval. We're here to help you get your property listed successfully.</p>

            <a href="{{ url('/dashboard') }}" class="cta-button">Update Property</a>

            <p>If you believe this rejection was made in error or need clarification, please contact our support team with your property details.</p>
        </div>

        <div class="footer">
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
            <p>Need help? <a href="mailto:support@agrobrix.com">Contact Support</a></p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>