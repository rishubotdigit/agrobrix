<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Property Submitted for Approval</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f59e0b; }
        .logo { font-size: 28px; font-weight: 600; color: #d97706; margin-bottom: 10px; }
        .content { margin-bottom: 30px; }
        .property-details { background-color: #fffbeb; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f59e0b; }
        .cta-button { display: inline-block; background-color: #f59e0b; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: 500; margin: 10px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; text-align: center; }
        .footer a { color: #d97706; text-decoration: none; }
        @media (max-width: 600px) { .container { padding: 20px; } .logo { font-size: 24px; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1 style="color: #d97706; margin: 0;">New Property Submitted for Approval</h1>
        </div>

        <div class="content">
            <p>A new property listing has been submitted and requires your approval on {{ config('app.name') }}.</p>

            <div class="property-details">
                <h3 style="margin-top: 0; color: #d97706;">Property Details:</h3>
                <ul style="margin: 0; padding-left: 20px;">
                    <li><strong>Owner:</strong> {{ $property->owner->name ?? 'N/A' }} ({{ $property->owner->email ?? 'N/A' }})</li>
                    <li><strong>Title:</strong> {{ $property->title }}</li>
                    <li><strong>Location:</strong> {{ $property->state }}</li>
                    <li><strong>Price:</strong> ₹{{ number_format($property->price) }}</li>
                    <li><strong>Land Type:</strong> {{ $property->land_type }}</li>
                    <li><strong>Plot Area:</strong> {{ $property->plot_area }} {{ $property->plot_area_unit }}</li>
                    <li><strong>Submitted At:</strong> {{ $property->created_at->format('Y-m-d H:i:s') }}</li>
                    <li><strong>Status:</strong> {{ ucfirst($property->status) }}</li>
                </ul>
            </div>

            <p>Please review this property submission and approve or reject it as appropriate.</p>

            <a href="{{ url('/admin/properties') }}" class="cta-button">Review Properties</a>
        </div>

        <div class="footer">
            <p>This is an automated notification from {{ config('app.name') }}</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>