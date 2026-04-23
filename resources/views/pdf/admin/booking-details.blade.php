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
            color: #717171;
            margin: 0;
            line-height: 1.5;
            background: #eceff1;
        }
        .sheet { max-width: 100%; background: #f4f7f8; border: 1px solid #dde3e6; }
        .masthead {
            background-color: #0b4f43;
            color: #fff;
            padding: 18px 20px;
        }
        .masthead-inner { width: 100%; border-collapse: collapse; }
        .masthead-inner td { vertical-align: middle; padding: 0; }
        .logo-cell { width: 44px; padding-right: 10px; }
        .brand-mark {
            font-size: 16px;
            font-weight: 500;
            color: #ffffff;
            line-height: 1.2;
        }
        .doc-label {
            text-align: right;
            font-size: 11px;
            font-weight: 600;
            color: #ffffff;
        }
        .doc-sub { font-size: 8.5px; font-weight: 400; opacity: 0.9; margin-top: 4px; color: #ffffff; }
        .panel {
            padding: 22px 24px 20px;
            background-color: #f4f7f8;
        }
        .meta-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .meta-row td { padding: 0; vertical-align: top; }
        .meta-box {
            background: #fff;
            border: 1px solid #e3e8ea;
            padding: 12px 14px;
        }
        .meta-label { font-size: 8px; color: #8a9399; text-transform: uppercase; letter-spacing: 0.05em; }
        .meta-value { font-size: 13px; font-weight: 600; color: #0b4f43; margin-top: 3px; }
        .meta-muted { font-size: 9px; color: #717171; margin-top: 5px; }
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #393939;
            margin: 0 0 12px 0;
            padding: 0;
        }
        .details { width: 100%; border-collapse: collapse; background: #fff; border: 1px solid #e3e8ea; }
        .details th {
            background: #f4f7f8;
            color: #393939;
            font-weight: 600;
            font-size: 9.5px;
            width: 30%;
            padding: 9px 12px;
            text-align: left;
            border-bottom: 1px solid #e8edf0;
        }
        .details td {
            padding: 9px 12px;
            border-bottom: 1px solid #eef1f3;
            font-size: 10.5px;
            color: #717171;
        }
        .details tr:last-child th,
        .details tr:last-child td { border-bottom: none; }
        .amount { font-weight: 600; color: #0b4f43; font-size: 11px; }
        .footer {
            margin-top: 20px;
            padding-top: 12px;
            border-top: 1px solid #dde3e6;
            font-size: 8.5px;
            color: #8a9399;
        }
        .footer strong { color: #393939; }
    </style>
</head>
<body>
    <div class="sheet">
        <div class="masthead">
            <table class="masthead-inner">
                <tr>
                    <td class="logo-cell">
                        <svg width="30" height="37" viewBox="0 0 30 37" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip_booking_pdf)">
                                <path d="M13.564 16.8645C13.5622 19.561 13.5622 22.2569 13.564 24.9528C13.564 32.5416 8.46223 34.8729 4.30127 36.8571L0.735246 31.0537C3.85551 29.4171 5.73778 27.5816 5.73778 25.1015C5.73778 24.5137 5.74266 24.2625 5.73778 21.258C5.73778 20.9238 5.73778 20.647 5.73778 20.4598C4.12345 19.1756 2.50973 17.8913 0.8954 16.6071C0.596405 14.6985 0.298019 12.7898 -0.000976562 10.8812C1.91235 10.8799 3.82507 10.8781 5.73839 10.8769C5.53013 10.3842 5.27863 9.65608 5.1605 8.74444C5.1605 8.74444 5.09351 8.21758 5.09351 7.68828C5.09412 2.82761 8.7594 0 13.9111 0C17.5271 0 19.4587 1.33911 21.0438 3.12458L17.2792 7.09251C16.9328 6.59675 16.1399 5.8528 15.1491 5.8528C14.5176 5.8528 13.9306 6.04976 13.4648 6.42113C12.6128 7.10044 12.5026 8.13709 12.4253 8.82859C12.3309 9.66889 12.4691 10.3817 12.6183 10.8769H13.5719C13.5683 12.8727 13.5659 14.868 13.5646 16.8645H13.564Z" fill="white"/>
                                <path d="M30.0002 10.8867C29.6872 12.8021 29.3748 14.7181 29.0618 16.6334C27.4317 17.9067 25.8021 19.1799 24.1719 20.4532C24.1719 20.5007 24.1719 21.2203 24.1719 21.2691C24.1719 24.6571 24.1719 24.9748 24.1719 25.087C24.1719 27.567 26.0542 29.4025 29.1745 31.0392L25.6085 36.8426C21.4475 34.8589 16.3457 32.5277 16.3457 24.9382C16.3585 18.9092 16.3719 22.7582 16.3847 16.7292C16.3798 14.7821 16.3743 12.835 16.3695 10.8879C20.9128 10.8879 25.4556 10.8873 29.999 10.8867H30.0002Z" fill="white"/>
                            </g>
                            <defs>
                                <clipPath id="clip_booking_pdf">
                                    <rect width="30" height="36.8571" fill="white"/>
                                </clipPath>
                            </defs>
                        </svg>
                    </td>
                    <td>
                        <div class="brand-mark">{{ $brandName }}</div>
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
                            <div class="meta-value" style="color: #393939;">{{ $statusLabel }}</div>
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
