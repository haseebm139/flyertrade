<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Booking {{ $booking->booking_ref }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
        h1 { font-size: 16px; margin: 0 0 12px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; width: 32%; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Booking details</h1>
    <table>
        <tr><th>Booking ID</th><td>{{ $booking->booking_ref }}</td></tr>
        <tr><th>Date</th><td>{{ $booking->created_at->format('d M, Y') }}</td></tr>
        <tr><th>Time</th><td>{{ $booking->created_at->format('h:i A') }}</td></tr>
        <tr><th>Duration</th><td>{{ $durationLabel }}</td></tr>
        <tr><th>Location</th><td>{{ $booking->booking_address ?? '-' }}</td></tr>
        <tr><th>Service type</th><td>{{ $booking->service->name ?? '-' }}</td></tr>
        <tr><th>Service cost</th><td>${{ number_format($booking->total_price, 2) }}</td></tr>
        <tr><th>Status</th><td>{{ $statusLabel }}</td></tr>
        <tr><th>Service provider</th><td>{{ $booking->provider->name ?? '-' }}</td></tr>
        <tr><th>Service user</th><td>{{ $booking->customer->name ?? '-' }}</td></tr>
    </table>
</body>
</html>
