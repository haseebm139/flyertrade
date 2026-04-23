<?php

namespace App\Support;

use App\Models\Booking;

final class BookingDetailsPdf
{
    /**
     * Render admin booking-details blade to a PDF binary string (uses dompdf/dompdf only).
     */
    public static function render(Booking $booking, string $durationLabel, string $statusLabel): string
    {
        return DompdfDocument::renderView('pdf.admin.booking-details', [
            'booking' => $booking,
            'durationLabel' => $durationLabel,
            'statusLabel' => $statusLabel,
        ]);
    }
}
