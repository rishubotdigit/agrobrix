<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Expiration Warning</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #f59e0b; }
        .logo { font-size: 28px; font-weight: 600; color: #d97706; margin-bottom: 10px; }
        .content { margin-bottom: 30px; }
        .warning-details { background-color: #fffbeb; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #f59e0b; }
        .plan-details { background-color: #fef3c7; padding: 15px; border-radius: 6px; margin: 15px 0; }
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
            <h1 style="color: #d97706; margin: 0;">Plan Expiration Warning</h1>
        </div>

        <div class="content">
            <p>Dear {{ $planPurchase->user->name }},</p>

            <p>This is a friendly reminder that your plan is about to expire. To continue enjoying all the benefits and features of your current plan, please renew it before the expiration date.</p>

            <div class="warning-details">
                <h3 style="margin-top: 0; color: #d97706;">Expiration Details:</h3>
                <p><strong>Plan:</strong> {{ $planPurchase->plan->name }}</p>
                <p><strong>Expires At:</strong> {{ $planPurchase->expires_at->format('M d, Y H:i') }}</p>
                <p><strong>Days Remaining:</strong> {{ now()->diffInDays($planPurchase->expires_at, false) }} days</p>
                <p><strong>Status:</strong> {{ ucfirst($planPurchase->status) }}</p>
            </div>

            <div class="plan-details">
                <h4 style="margin-top: 0; color: #92400e;">Current Usage:</h4>
                <p><strong>Contacts Used:</strong> {{ $planPurchase->used_contacts }} / {{ $planPurchase->plan->max_contacts ?? 'Unlimited' }}</p>
                <p><strong>Featured Listings Used:</strong> {{ $planPurchase->used_featured_listings }} / {{ $planPurchase->plan->max_featured_listings ?? 'Unlimited' }}</p>
            </div>

            <p>Don't lose access to your premium features! Renew your plan today to maintain uninterrupted service.</p>

            <a href="{{ url('/plans') }}" class="cta-button">Renew Plan</a>
            <a href="{{ url('/dashboard') }}" class="cta-button">View Dashboard</a>

            <p>If you have any questions about your plan or need assistance, please contact our support team.</p>
        </div>

        <div class="footer">
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
            <p>Need help? <a href="mailto:support@agrobrix.com">Contact Support</a></p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>