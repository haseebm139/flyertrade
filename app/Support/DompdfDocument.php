<?php

namespace App\Support;

use Dompdf\Dompdf;

final class DompdfDocument
{
    /**
     * Render a Blade view to a PDF binary string (dompdf/dompdf).
     */
    public static function renderView(string $view, array $data = []): string
    {
        $html = view($view, $data)->render();

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
