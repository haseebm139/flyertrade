@php
    $brandName = ($n = trim((string) config('app.name'))) !== '' && $n !== 'Laravel' ? $n : 'Flyertrade';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Booking {{ $booking->booking_ref }} — {{ $brandName }}</title>
    <style>
        @page { margin: 28px 32px; }
        * { box-sizing: border-box; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            color: #1a1a1a;
            margin: 0;
            line-height: 1.45;
        }
        .sheet { max-width: 100%; }
        .masthead {
            background: #17A55A;
            color: #fff;
            padding: 14px 18px;
            border-radius: 4px 4px 0 0;
        }
        .masthead-inner { width: 100%; border-collapse: collapse; }
        .masthead-inner td { vertical-align: middle; padding: 0; }
        .brand-mark {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }
        .brand-tagline {
            font-size: 9px;
            opacity: 0.92;
            margin-top: 4px;
            letter-spacing: 0.04em;
        }
        .doc-label {
            text-align: right;
            font-size: 11px;
            font-weight: bold;
        }
        .doc-sub { font-size: 8.5px; font-weight: normal; opacity: 0.9; margin-top: 3px; }
        .panel {
            border: 1px solid #d4e8dc;
            border-top: none;
            border-radius: 0 0 4px 4px;
            padding: 16px 18px 14px;
            background: #fafdfb;
        }
        .meta-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .meta-row td { padding: 0; vertical-align: top; }
        .meta-box {
            background: #fff;
            border: 1px solid #e2eee6;
            border-radius: 3px;
            padding: 10px 12px;
        }
        .meta-label { font-size: 8px; color: #5c6d63; text-transform: uppercase; letter-spacing: 0.06em; }
        .meta-value { font-size: 12px; font-weight: bold; color: #17A55A; margin-top: 2px; }
        .meta-muted { font-size: 9px; color: #666; margin-top: 4px; }
        .section-title {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #17A55A;
            font-weight: bold;
            margin: 0 0 8px 0;
            padding-bottom: 4px;
            border-bottom: 2px solid #17A55A;
        }
        .details { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #e2eee6; border-radius: 3px; overflow: hidden; }
        .details th {
            background: #eef7f1;
            color: #2d4a38;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            width: 30%;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #dceee2;
        }
        .details td {
            padding: 8px 10px;
            border-bottom: 1px solid #eef2ef;
            font-size: 10.5px;
        }
        .details tr:last-child th,
        .details tr:last-child td { border-bottom: none; }
        .amount { font-weight: bold; color: #128a4a; font-size: 11px; }
        .footer {
            margin-top: 18px;
            padding-top: 10px;
            border-top: 3px solid #17A55A;
            font-size: 8.5px;
            color: #6b7a72;
        }
        .footer strong { color: #17A55A; }
        .footer a { color: #17A55A; text-decoration: none; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="masthead">
            <table class="masthead-inner">
                <tr>
                    <td>
                        <div class="brand-mark">{{ $brandName }}</div>
                        <div class="brand-tagline">Trusted local services · Marketplace</div>
                    </td>
                    <td style="width: 38%;" class="doc-label">
                        Booking summary
                        <div class="doc-sub">Admin document</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="panel">
            <table class="meta-row">
                <tr>
                    <td style="width: 50%; padding-right: 8px;">
                        <div class="meta-box">
                            <div class="meta-label">Booking reference</div>
                            <div class="meta-value">{{ $booking->booking_ref }}</div>
                            <div class="meta-muted">Booked {{ $booking->created_at->format('d M Y · h:i A') }}</div>
                        </div>
                    </td>
                    <td style="width: 50%; padding-left: 8px;">
                        <div class="meta-box">
                            <div class="meta-label">Status</div>
                            <div class="meta-value" style="color: #1a1a1a;">{{ $statusLabel }}</div>
                            <div class="meta-muted">Generated {{ now()->format('d M Y, h:i A') }}</div>
                        </div>
                    </td>
                </tr>
            </table>

            <p class="section-title">Service details</p>
            <table class="details">
                <tr><th>Date</th><td>{{ $booking->created_at->format('d M, Y') }}</td></tr>
                <tr><th>Time</th><td>{{ $booking->created_at->format('h:i A') }}</td></tr>
                <tr><th>Duration</th><td>{{ $durationLabel }}</td></tr>
                <tr><th>Location</th><td>{{ $booking->booking_address ?? '—' }}</td></tr>
                <tr><th>Service type</th><td>{{ $booking->service->name ?? '—' }}</td></tr>
                <tr><th>Service cost</th><td class="amount">${{ number_format($booking->total_price, 2) }}</td></tr>
                <tr><th>Service provider</th><td>{{ $booking->provider->name ?? '—' }}</td></tr>
                <tr><th>Service user</th><td>{{ $booking->customer->name ?? '—' }}</td></tr>
            </table>

            <div class="footer">
                <strong>{{ $brandName }}</strong>
                · {{ rtrim((string) config('app.url'), '/') ?: 'https://flyertrade.com' }}
                <br>
                This PDF was produced from the Flyertrade admin panel for internal records. Not a tax invoice unless separately issued.
            </div>
        </div>
    </div>
</body>
</html>
