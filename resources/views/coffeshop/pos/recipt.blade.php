<!DOCTYPE html>
<html>

<head>
    <title>Receipt - {{ $transaction->transaction_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            width: 300px;
        }

        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .outlet-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .transaction-info {
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .items {
            margin-bottom: 10px;
        }

        .item {
            margin-bottom: 5px;
        }

        .item-name {
            font-weight: bold;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            margin-left: 10px;
        }

        .totals {
            border-top: 1px dashed #000;
            padding-top: 10px;
            margin-top: 10px;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }

        .grand-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .payments {
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }

        @media print {
            body {
                width: auto;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="outlet-name">{{ $transaction->outlet_name }}</div>
        <div>{{ $transaction->outlet_address }}</div>
        <div>{{ $transaction->outlet_phone }}</div>
    </div>

    <div class="transaction-info">
        <div><strong>Transaction #:</strong> {{ $transaction->transaction_number }}</div>
        <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i') }}
        </div>
        <div><strong>Cashier:</strong> {{ $transaction->cashier_name }}</div>
        @if ($transaction->customer_name)
            <div><strong>Customer:</strong> {{ $transaction->customer_name }}</div>
            @if ($transaction->customer_whatsapp)
                <div><strong>WhatsApp:</strong> {{ $transaction->customer_whatsapp }}</div>
            @endif
        @endif
    </div>

    <div class="items">
        @foreach ($transactionDetails as $detail)
            <div class="item">
                <div class="item-name">{{ $detail->product_name }}</div>
                @if ($detail->variant_name !== 'Regular')
                    <div style="margin-left: 10px; font-size: 10px;">{{ $detail->variant_name }}</div>
                @endif
                <div class="item-details">
                    <span>{{ $detail->quantity }} x Rp {{ number_format($detail->unit_price, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($detail->total_price, 0, ',', '.') }}</span>
                </div>
                @if ($detail->discount_amount > 0)
                    <div class="item-details" style="font-size: 10px; color: #666;">
                        <span>{{ $detail->discount_name }}</span>
                        <span>-Rp {{ number_format($detail->discount_amount, 0, ',', '.') }}</span>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="totals">
        <div class="total-line">
            <span>Subtotal:</span>
            <span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
        </div>

        @if ($transaction->discount_amount > 0)
            <div class="total-line">
                <span>Discount:</span>
                <span>-Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
            </div>
        @endif

        @if ($transaction->tax_amount > 0)
            <div class="total-line">
                <span>Tax:</span>
                <span>Rp {{ number_format($transaction->tax_amount, 0, ',', '.') }}</span>
            </div>
        @endif

        <div class="total-line grand-total">
            <span>TOTAL:</span>
            <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="payments">
        <div style="font-weight: bold; margin-bottom: 5px;">Payment Methods:</div>
        @php $totalPaid = 0; @endphp
        @foreach ($payments as $payment)
            @php $totalPaid += $payment->amount; @endphp
            <div class="total-line">
                <span>{{ $payment->method_name }}:</span>
                <span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
            </div>
            @if ($payment->reference_number)
                <div style="font-size: 10px; margin-left: 10px; color: #666;">
                    Ref: {{ $payment->reference_number }}
                </div>
            @endif
        @endforeach

        @if ($totalPaid > $transaction->total_amount)
            <div class="total-line" style="margin-top: 5px;">
                <span><strong>Change:</strong></span>
                <span><strong>Rp
                        {{ number_format($totalPaid - $transaction->total_amount, 0, ',', '.') }}</strong></span>
            </div>
        @endif
    </div>

    <div class="footer">
        <div>Thank you for your visit!</div>
        <div>{{ now()->format('d/m/Y H:i:s') }}</div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
