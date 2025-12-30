<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Property Inquiry</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #10b981; }
        .logo { font-size: 28px; font-weight: 600; color: #059669; margin-bottom: 10px; }
        .content { margin-bottom: 30px; }
        .inquiry-details { background-color: #f0fdf4; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #10b981; }
        .property-details { background-color: #ecfdf5; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .cta-button { display: inline-block; background-color: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500; margin: 10px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; text-align: center; }
        .footer a { color: #059669; text-decoration: none; }
        @media (max-width: 600px) { .container { padding: 20px; } .logo { font-size: 24px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1 style="color: #059669; margin: 0;">New Property Inquiry</h1>
        </div>

        <div class="content">
            <p>Hi {{ $lead->agent ? $lead->agent->name : $lead->property->owner->name }},</p>

            <p>Great news! Someone has shown interest in your property and submitted an inquiry.</p>

            <div class="inquiry-details">
                <h3 style="margin-top: 0; color: #059669;">Inquiry Details:</h3>
                <p><strong>Buyer Name:</strong> {{ $lead->buyer_name }}</p>
                <p><strong>Email:</strong> {{ $lead->buyer_email }}</p>
                <p><strong>Phone:</strong> {{ $lead->buyer_phone }}</p>
                <p><strong>Type:</strong> {{ ucfirst($lead->buyer_type) }}</p>
                @if($lead->buying_purpose)
                    <p><strong>Purpose:</strong> {{ $lead->buying_purpose }}</p>
                @endif
                @if($lead->buying_timeline)
                    <p><strong>Timeline:</strong> {{ $lead->buying_timeline }}</p>
                @endif
                @if($lead->interested_in_site_visit)
                    <p><strong>Interested in Site Visit:</strong> Yes</p>
                @endif
                @if($lead->additional_message)
                    <p><strong>Message:</strong> {{ $lead->additional_message }}</p>
                @endif
                <p><strong>Inquiry Date:</strong> {{ $lead->created_at->format('M d, Y H:i') }}</p>
            </div>

            <div class="property-details">
                <h4 style="margin-top: 0; color: #047857;">Property Details:</h4>
                <p><strong>Title:</strong> {{ $lead->property->title }}</p>
                <p><strong>Location:</strong> {{ $lead->property->state }}</p>
                <p><strong>Price:</strong> ₹{{ number_format($lead->property->price) }}</p>
            </div>

            <p>Please reach out to this potential buyer as soon as possible to discuss their interest and arrange next steps.</p>

            <a href="{{ url('/dashboard/leads') }}" class="cta-button">View All Leads</a>
            <a href="{{ url('/properties/' . $lead->property->id) }}" class="cta-button">View Property</a>

            <p>Remember to follow up promptly to convert this inquiry into a successful sale!</p>
        </div>

        <div class="footer">
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
            <p>Need help? <a href="mailto:support@agrobrix.com">Contact Support</a></p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>