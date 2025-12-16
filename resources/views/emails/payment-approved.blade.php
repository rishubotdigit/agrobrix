<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approved</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #10b981; }
        .logo { font-size: 28px; font-weight: 600; color: #059669; margin-bottom: 10px; }
        .content { margin-bottom: 30px; }
        .payment-details { background-color: #f0fdf4; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #10b981; }
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
            <h1 style="color: #059669; margin: 0;">Payment Approved!</h1>
        </div>

        <div class="content">
            <p>Dear {{ $payment->user->name }},</p>

            <p>Great news! Your payment has been reviewed and approved by our team. Your transaction is now complete and your services are activated.</p>

            <div class="payment-details">
                <h3 style="margin-top: 0; color: #059669;">Payment Details:</h3>
                <p><strong>Amount:</strong> ₹{{ number_format($payment->amount, 2) }}</p>
                <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? $payment->payment_id }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $payment->gateway)) }}</p>
                @if($payment->property)
                    <p><strong>Property:</strong> {{ $payment->property->title }}</p>
                @endif
                @if($payment->planPurchase)
                    <p><strong>Plan:</strong> {{ $payment->planPurchase->plan->name }}</p>
                @endif
                <p><strong>Approved At:</strong> {{ $payment->approved_at->format('M d, Y H:i') }}</p>
            </div>

            <p>You can now access all the features and services associated with your payment. Check your dashboard for more details.</p>

            <a href="{{ url('/dashboard') }}" class="cta-button">View Dashboard</a>

            <p>If you have any questions about your payment or need assistance, please don't hesitate to contact our support team.</p>
        </div>

        <div class="footer">
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
            <p>Need help? <a href="mailto:support@agrobrix.com">Contact Support</a></p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>