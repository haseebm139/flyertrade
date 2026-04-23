<?php

namespace App\Support;

use App\Models\Booking;
use Dompdf\Dompdf;

final class BookingDetailsPdf
{
    /**
     * Render admin booking-details blade to a PDF binary string (uses dompdf/dompdf only).
     */
    public static function render(Booking $booking, string $durationLabel, string $statusLabel): string
    {
        $html = view('pdf.admin.booking-details', [
            'booking' => $booking,
            'durationLabel' => $durationLabel,
            'statusLabel' => $statusLabel,
        ])->render();

        $dompdf = new Dompdf();
        $publicPath = realpath(base_path('public'));
        if ($publicPath !== false) {
            $dompdf->setBasePath($publicPath);
        }
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->render();

        return $dompdf->output();
    }
}
