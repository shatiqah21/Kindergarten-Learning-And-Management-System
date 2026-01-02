<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; font-size: 12px; }
        .report-title { text-align: center; font-weight: bold; margin-bottom: 15px; font-size: 14px; }
        .student-info { margin-bottom: 15px; }
        .student-info span { display: inline-block; margin-right: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; font-size: 12px; }
        .total { font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h1>PASTI FIRDAUS</h1>
        <p>No. 12, Jalan Bunga Melur 3, Taman Harmoni, 85200 Jementah, Johor.</p>
        <div class="report-title">PAYMENT RECEIPT</div>
    </div>

    <div class="student-info">
        <span><strong>NAME:</strong> {{ $payment->paymentDetails->first()->studentRecord->user->name }}</span>
        <span><strong>ADM NO:</strong> {{ $payment->paymentDetails->first()->studentRecord->adm_no ?? '-' }}</span>
        <span><strong>CLASS:</strong> {{ $payment->paymentDetails->first()->studentRecord->my_class->name ?? '-' }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Child Name</th>
                <th>Class</th>
                <th>Amount Paid (RM)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payment->paymentDetails as $detail)
                <tr>
                    <td>{{ $detail->studentRecord->user->name }}</td>
                    <td>{{ $detail->studentRecord->my_class->name ?? '-' }}</td>
                    <td>{{ number_format($detail->amt_paid, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="total">Total</td>
                <td class="total">RM {{ number_format($payment->amount, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <p><strong>Payment Method:</strong> {{ ucfirst($payment->method) }}</p>
    <p><strong>Date:</strong> {{ $payment->created_at->format('d/m/Y') }}</p>

    <p>Thank you for your payment!</p>

</body>
</html>
