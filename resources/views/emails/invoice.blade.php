<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background-color: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #10b981; }
        .logo { font-size: 28px; font-weight: 600; color: #059669; margin-bottom: 10px; }
        .invoice-header { display: flex; justify-content: space-between; margin-bottom: 30px; }
        .invoice-details { background-color: #f0fdf4; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .invoice-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .invoice-table th, .invoice-table td { padding: 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        .invoice-table th { background-color: #f9fafb; font-weight: 600; }
        .total-row { background-color: #f0fdf4; font-weight: 600; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; text-align: center; }
        .footer a { color: #059669; text-decoration: none; }
        @media (max-width: 600px) { .container { padding: 20px; } .logo { font-size: 24px; } .invoice-header { flex-direction: column; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">{{ config('app.name') }}</div>
            <h1 style="color: #059669; margin: 0;">Invoice</h1>
        </div>

        <div class="invoice-header">
            <div>
                <h3>Bill To:</h3>
                <p>{{ $payment->user->name }}<br>
                {{ $payment->user->email }}</p>
            </div>
            <div>
                <h3>Invoice Details:</h3>
                <p><strong>Invoice #:</strong> INV-{{ $payment->id }}<br>
                <strong>Date:</strong> {{ $payment->created_at->format('M d, Y') }}<br>
                <strong>Payment ID:</strong> {{ $payment->payment_id }}</p>
            </div>
        </div>

        <div class="invoice-details">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            @if($payment->property)
                                Property Payment - {{ $payment->property->title }}
                            @elseif($payment->planPurchase)
                                Plan Purchase - {{ $payment->planPurchase->plan->name }}
                            @else
                                Payment
                            @endif
                        </td>
                        <td>₹{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td><strong>Total</strong></td>
                        <td><strong>₹{{ number_format($payment->amount, 2) }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="margin: 20px 0;">
            <h3>Payment Information:</h3>
            <p><strong>Payment Method:</strong> {{ ucfirst(str_replace('_', ' ', $payment->gateway)) }}</p>
            <p><strong>Transaction ID:</strong> {{ $payment->transaction_id ?? $payment->payment_id }}</p>
            <p><strong>Status:</strong> {{ ucfirst($payment->status) }}</p>
            @if($payment->approved_at)
                <p><strong>Processed At:</strong> {{ $payment->approved_at->format('M d, Y H:i') }}</p>
            @endif
        </div>

        <p>Thank you for your business! If you have any questions about this invoice, please contact our support team.</p>

        <div class="footer">
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
            <p>Need help? <a href="mailto:support@agrobrix.com">Contact Support</a></p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>