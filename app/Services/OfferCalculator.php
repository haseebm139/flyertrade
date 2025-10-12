<?php

namespace App\Services;

class OfferCalculator
{
    public function calculateTotals(array $costItems = [], array $materials = [], float $flatFee = 0.0, float $taxRate = 0.0): array
    {
        $subtotalItems = 0.0;
        foreach ($costItems as $item) {
            $subtotalItems += (float)($item['amount'] ?? 0);
        }

        $materialsSum = 0.0;
        foreach ($materials as $m) {
            $qty = (float)($m['qty'] ?? 0);
            $price = (float)($m['price'] ?? 0);
            $materialsSum += $qty * $price;
        }

        $subtotal = $subtotalItems + $materialsSum + (float)$flatFee;
        $tax = $subtotal * (float)$taxRate;
        $total = $subtotal + $tax;

        return [
            'subtotal' => round($subtotal, 2),
            'tax' => round($tax, 2),
            'total' => round($total, 2),
        ];
    }
}

