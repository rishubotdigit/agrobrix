<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Plan Purchase</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; }
        .invoice-header { text-align: center; margin-bottom: 30px; }
        .invoice-details { margin-bottom: 20px; }
        .invoice-details table { width: 100%; border-collapse: collapse; }
        .invoice-details th, .invoice-details td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        .plan-details { margin-bottom: 20px; }
        .total { font-weight: bold; font-size: 18px; }
        .footer { margin-top: 30px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>{{ config('app.name') }} - Invoice</h1>
        <p>Invoice Number: INV-{{ $planPurchase->id }}</p>
        <p>Date: {{ $planPurchase->created_at->format('Y-m-d') }}</p>
    </div>

    <div class="invoice-details">
        <h2>Bill To:</h2>
        <table>
            <tr>
                <th>Name:</th>
                <td>{{ $planPurchase->user->name }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $planPurchase->user->email }}</td>
            </tr>
        </table>
    </div>

    <div class="plan-details">
        <h2>Plan Details:</h2>
        <table>
            <tr>
                <th>Plan Name:</th>
                <td>{{ $planPurchase->plan->name }}</td>
            </tr>
            <tr>
                <th>Price:</th>
                <td>${{ number_format($planPurchase->plan->price, 2) }}</td>
            </tr>
            <tr>
                <th>Validity:</th>
                <td>{{ $planPurchase->plan->validity_period_days }} days</td>
            </tr>
            <tr>
                <th>Features/Capabilities:</th>
                <td>
                    <ul>
                        @foreach($planPurchase->plan->capabilities as $key => $value)
                            <li>{{ ucfirst(str_replace('_', ' ', $key)) }}: {{ is_array($value) ? implode(', ', $value) : $value }}</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
        </table>
    </div>

    <div class="total">
        <p>Total Amount: ${{ number_format($planPurchase->plan->price, 2) }}</p>
    </div>

    <div class="footer">
        <p>Thank you for your purchase! If you have any questions, please contact our support team.</p>
        <p>Note: All prices are in USD. No taxes applied.</p>
    </div>
</body>
</html>