<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 3px solid #1572e8;
            padding-bottom: 20px;
        }

        .company-name {
            font-size: 28px;
            font-weight: bold;
            color: #1572e8;
            margin-bottom: 5px;
        }

        .company-info {
            font-size: 11px;
            color: #666;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            text-align: right;
            margin-top: -60px;
        }

        .invoice-meta {
            text-align: right;
            margin-bottom: 30px;
        }

        .invoice-meta table {
            margin-left: auto;
            border-collapse: collapse;
        }

        .invoice-meta td {
            padding: 4px 10px;
            font-size: 11px;
        }

        .invoice-meta td:first-child {
            font-weight: bold;
            text-align: right;
            color: #666;
        }

        .parties {
            display: table;
            width: 100%;
            margin-bottom: 40px;
        }

        .party {
            display: table-cell;
            width: 48%;
            vertical-align: top;
        }

        .party-title {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .party-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .party-details {
            font-size: 11px;
            color: #555;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table thead {
            background-color: #1572e8;
            color: white;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #1572e8;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals {
            margin-left: auto;
            width: 300px;
            margin-top: 20px;
        }

        .totals table {
            width: 100%;
            border-collapse: collapse;
        }

        .totals td {
            padding: 8px 12px;
            font-size: 12px;
        }

        .totals td:first-child {
            text-align: right;
            color: #666;
        }

        .totals td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .totals .subtotal {
            border-top: 1px solid #e0e0e0;
        }

        .totals .tax {
            color: #666;
        }

        .totals .total {
            background-color: #1572e8;
            color: white;
            font-size: 16px;
        }

        .notes {
            margin-top: 40px;
            padding: 20px;
            background-color: #f8f9fa;
            border-left: 4px solid #1572e8;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #1572e8;
        }

        .notes-content {
            font-size: 11px;
            color: #555;
        }

        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            font-size: 10px;
            color: #999;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-paid {
            background-color: #28a745;
            color: white;
        }

        .status-pending {
            background-color: #ffc107;
            color: #333;
        }

        .status-overdue {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">HOONIAN</div>
        <div class="company-info">
            Property Marketplace & Bidding Platform<br>
            Email: info@hoonian.com | Phone: +62 123 4567 890<br>
            Jakarta, Indonesia
        </div>
    </div>

    <!-- Invoice Title & Meta -->
    <div class="invoice-title">INVOICE</div>
    <div class="invoice-meta">
        <table>
            <tr>
                <td>Invoice Number:</td>
                <td><strong>{{ $transaction->invoice_number }}</strong></td>
            </tr>
            <tr>
                <td>Invoice Date:</td>
                <td>{{ $transaction->created_at->format('d F Y') }}</td>
            </tr>
            <tr>
                <td>Due Date:</td>
                <td>{{ $transaction->due_date ? $transaction->due_date->format('d F Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Status:</td>
                <td>
                    @if($transaction->isPaid())
                        <span class="status-badge status-paid">PAID</span>
                    @elseif($transaction->isOverdue())
                        <span class="status-badge status-overdue">OVERDUE</span>
                    @else
                        <span class="status-badge status-pending">PENDING</span>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- Bill To / From -->
    <div class="parties">
        <div class="party">
            <div class="party-title">Bill To</div>
            <div class="party-name">{{ $transaction->user->name }}</div>
            <div class="party-details">
                Email: {{ $transaction->user->email }}<br>
                Phone: {{ $transaction->user->phone ?? '-' }}
            </div>
        </div>
        <div class="party" style="padding-left: 40px;">
            <div class="party-title">Property Owner</div>
            <div class="party-name">{{ $transaction->property->owner->name ?? 'N/A' }}</div>
            <div class="party-details">
                Email: {{ $transaction->property->owner->email ?? '-' }}<br>
                Phone: {{ $transaction->property->owner->phone ?? '-' }}
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 10%;">No</th>
                <th style="width: 50%;">Description</th>
                <th style="width: 20%;" class="text-center">Type</th>
                <th style="width: 20%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td>
                    <strong>{{ $transaction->property->name }}</strong><br>
                    <small style="color: #666;">
                        {{ $transaction->property->address }}<br>
                        {{ $transaction->property->city }}
                    </small>
                </td>
                <td class="text-center">
                    <span style="background-color: #e3f2fd; padding: 4px 8px; border-radius: 3px; font-size: 10px;">
                        {{ strtoupper($transaction->property->type) }}
                    </span>
                </td>
                <td class="text-right">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr class="subtotal">
                <td>Subtotal:</td>
                <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="tax">
                <td>Tax ({{ $transaction->tax_rate }}%):</td>
                <td>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="total">
                <td>TOTAL:</td>
                <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <!-- Payment Info -->
    @if($transaction->isPaid())
        <div class="notes">
            <div class="notes-title">Payment Information</div>
            <div class="notes-content">
                <strong>Payment Method:</strong> {{ $transaction->payment_method }}<br>
                <strong>Payment Date:</strong> {{ $transaction->paid_at->format('d F Y H:i') }}<br>
                <strong>Status:</strong> Payment received and confirmed
            </div>
        </div>
    @else
        <div class="notes">
            <div class="notes-title">Payment Instructions</div>
            <div class="notes-content">
                Please make payment to the following bank account:<br>
                <strong>Bank:</strong> Bank Central Asia (BCA)<br>
                <strong>Account Name:</strong> PT Hoonian Indonesia<br>
                <strong>Account Number:</strong> 1234567890<br>
                <strong>Amount:</strong> Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}
            </div>
        </div>
    @endif

    @if($transaction->notes)
        <div class="notes" style="margin-top: 20px;">
            <div class="notes-title">Additional Notes</div>
            <div class="notes-content">
                {{ $transaction->notes }}
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        This is a computer-generated invoice and does not require a signature.<br>
        Thank you for your business with Hoonian!<br>
        <strong>{{ now()->format('Y') }} © Hoonian. All rights reserved.</strong>
    </div>
</body>

</html>