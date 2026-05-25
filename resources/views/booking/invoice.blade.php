<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $booking->id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
            padding: 10px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            border: 1px solid #eee;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .header-table td {
            vertical-align: top;
        }

        .title {
            font-size: 28px;
            font-weight: 800;
            color: #6f42c1;
        }

        .invoice-details {
            text-align: right;
            color: #777;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .info-table td {
            width: 50%;
            vertical-align: top;
            padding-bottom: 20px;
        }

        .info-header {
            font-weight: bold;
            color: #6f42c1;
            margin-bottom: 5px;
            text-transform: uppercase;
            font-size: 12px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .items-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
            vertical-align: middle;
        }

        .total-row td {
            font-weight: bold;
            font-size: 16px;
            border-bottom: 2px solid #6f42c1;
            padding-top: 20px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #d1e7dd;
            color: #0f5132;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #777;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>

<div class="invoice-box">
    <!-- Header Row -->
    <table class="header-table">
        <tr>
            <td>
                <span class="title">TutorConnect</span><br>
                <small style="color:#777">Learn & Teach in Real-Time</small>
            </td>
            <td class="invoice-details">
                <strong>Invoice ID:</strong> #TC-INV-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}<br>
                <strong>Date:</strong> {{ date('F d, Y') }}<br>
                <strong>Payment:</strong> <span class="badge badge-success">PAID</span>
            </td>
        </tr>
    </table>

    <!-- Billing details -->
    <table class="info-table">
        <tr>
            <td>
                <div class="info-header">Billed To (Student)</div>
                <strong>{{ $booking->student->user->name }}</strong><br>
                Email: {{ $booking->student->user->email }}<br>
                Phone: {{ $booking->student->user->phone ?? 'N/A' }}
            </td>
            <td>
                <div class="info-header">Provider (Tutor)</div>
                <strong>{{ $booking->tutor->user->name }}</strong><br>
                Qualifications: {{ $booking->tutor->qualification }}<br>
                Transaction: {{ $booking->payment->transaction_id ?? 'N/A' }}
            </td>
        </tr>
    </table>

    <!-- Items Listing -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Service Details</th>
                <th>Subject</th>
                <th>Time Window</th>
                <th style="text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <strong>1-on-1 Tutoring Session</strong><br>
                    Date: {{ $booking->date->format('Y-m-d') }}
                </td>
                <td>{{ $booking->subject->name }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                </td>
                <td style="text-align: right;">${{ number_format($booking->total_price, 2) }}</td>
            </tr>
            <!-- Total Row -->
            <tr class="total-row">
                <td colspan="3" style="text-align: right;">Total Paid:</td>
                <td style="text-align: right; color:#6f42c1">${{ number_format($booking->total_price, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Footer message -->
    <div class="footer">
        Thank you for booking with TutorConnect! If you have any inquiries regarding this statement, please contact support@tutorconnect.com.
    </div>
</div>

</body>
</html>
