<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>{{ __('order.receipt_title', ['id' => str_pad($order->id, 6, '0', STR_PAD_LEFT)]) }}</title>
    <style>
        :root {
            --receipt-width: 80mm;
            --font-family: 'Courier New', Courier, monospace;
            --text-color: #111827;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 12px;
            font-size: 12px;
            line-height: 1.4;
            color: var(--text-color);
            font-family: var(--font-family);
            background: #f9fafb;
        }

        .receipt {
            width: var(--receipt-width);
            margin: 0 auto;
            background: #fff;
            padding: 12px;
            border: 1px dashed #d1d5db;
        }

        .receipt__header,
        .receipt__footer {
            text-align: center;
        }

        .receipt__title {
            font-size: 14px;
            text-transform: uppercase;
            margin: 0 0 4px;
            font-weight: 700;
        }

        .receipt__meta {
            margin: 0 0 12px;
        }

        .receipt__meta div {
            display: flex;
            justify-content: space-between;
        }

        .receipt__section-title {
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.08em;
            margin: 16px 0 4px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 4px 0;
        }

        th {
            text-transform: uppercase;
            font-size: 11px;
            border-bottom: 1px dashed #d1d5db;
        }

        td {
            border-bottom: 1px dashed #e5e7eb;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .totals td {
            border-bottom: none;
        }

        .totals tr:last-child td {
            border-top: 1px dashed #d1d5db;
            font-weight: 700;
        }

        .small {
            font-size: 11px;
        }

        .receipt__footer p {
            margin: 4px 0;
        }

        .print-actions {
            text-align: center;
            margin-top: 16px;
        }

        .print-actions button {
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
        }

        @media print {
            body {
                background: #fff;
            }

            .print-actions {
                display: none;
            }

            @page {
                size: 80mm auto;
                margin: 4mm;
            }
        }
    </style>
</head>
<body>
    @php
        $subtotal = $order->items->sum('price');
        $paid = $order->receivedAmount();
        $change = max($paid - $subtotal, 0);
        $customerName = $order->customer ? trim($order->customer->first_name . ' ' . $order->customer->last_name) : __('order.walk_in_customer');
        $cashier = $order->user;
        if ($cashier && method_exists($cashier, 'getFullname')) {
            $cashierName = $cashier->getFullname();
        } elseif ($cashier) {
            $cashierName = trim(($cashier->first_name ?? '') . ' ' . ($cashier->last_name ?? ''));
        } else {
            $cashierName = null;
        }
    @endphp
    <div class="receipt">
        <div class="receipt__header">
            <h1 class="receipt__title">{{ $store['name'] ?? config('app.name') }}</h1>
            @if(!empty($store['description']))
                <p class="small">{{ $store['description'] }}</p>
            @endif
        </div>

        <div class="receipt__meta">
            <div>
                <span>{{ __('order.receipt_number') }}:</span>
                <span>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div>
                <span>{{ __('order.date') }}:</span>
                <span>{{ $order->created_at->format('d-m-Y H:i') }}</span>
            </div>
            @if($cashierName)
            <div>
                <span>{{ __('order.cashier') }}:</span>
                <span>{{ $cashierName }}</span>
            </div>
            @endif
            <div>
                <span>{{ __('order.customer') }}:</span>
                <span>{{ $customerName }}</span>
            </div>
        </div>

        <p class="receipt__section-title">{{ __('order.items') }}</p>
        <table>
            <thead>
                <tr>
                    <th>{{ __('order.item') }}</th>
                    <th class="text-center">{{ __('order.qty') }}</th>
                    <th class="text-right">{{ __('order.price') }}</th>
                    <th class="text-right">{{ __('order.total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    @php
                        $productName = optional($item->product)->name ?? __('order.unknown_item');
                        $unitPrice = $item->quantity > 0 ? $item->price / $item->quantity : $item->price;
                    @endphp
                    <tr>
                        <td>{{ $productName }}</td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">{{ $currencySymbol }}{{ number_format($unitPrice, 2) }}</td>
                        <td class="text-right">{{ $currencySymbol }}{{ number_format($item->price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tbody>
                <tr>
                    <td>{{ __('order.subtotal') }}</td>
                    <td class="text-right">{{ $currencySymbol }}{{ number_format($subtotal, 2) }}</td>
                </tr>
                <tr>
                    <td>{{ __('order.paid') }}</td>
                    <td class="text-right">{{ $currencySymbol }}{{ number_format($paid, 2) }}</td>
                </tr>
                <tr>
                    <td>{{ __('order.change') }}</td>
                    <td class="text-right">{{ $currencySymbol }}{{ number_format($change, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="receipt__footer">
            <p>{{ __('order.thank_you') }}</p>
            <p class="small">{{ __('order.no_refund_policy') }}</p>
        </div>
    </div>

    <div class="print-actions">
        <button onclick="window.print();">{{ __('order.print_now') }}</button>
    </div>

    <script>
        function triggerPrint() {
            if (window.matchMedia) {
                setTimeout(function () {
                    window.print();
                }, 300);
            } else {
                window.print();
            }
        }

        function handleAfterPrint() {
            window.removeEventListener('afterprint', handleAfterPrint);
            window.close();
        }

        window.addEventListener('load', function () {
            triggerPrint();
        });
        window.addEventListener('afterprint', handleAfterPrint);
    </script>
</body>
</html>
