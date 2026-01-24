<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use Livewire\Component;

class FinanceSummary extends Component
{
    public ?int $month = null;
    public array $financeSummary = [];
    public array $monthOptions = [];

    public function mount(): void
    {
        $this->monthOptions = $this->buildMonthOptions();
        $this->month = $this->month ?? now()->month;
        $this->loadSummary();
    }

    public function updatedMonth($value): void
    {
        $this->month = $value !== '' ? (int) $value : null;
        $this->loadSummary();
    }

    private function loadSummary(): void
    {
        $query = Transaction::where('status', 'succeeded');

        if ($this->month) {
            $query->whereMonth('created_at', $this->month)
                ->whereYear('created_at', now()->year);
        }

        $this->financeSummary = [
            'total_revenue' => (clone $query)->where('type', 'payment')->sum('service_charges'),
            'total_payout' => (clone $query)->where('type', 'payout')->sum('net_amount'),
        ];
    }

    private function buildMonthOptions(): array
    {
        return [
            ['value' => '', 'label' => 'All months'],
            ['value' => 1, 'label' => 'January'],
            ['value' => 2, 'label' => 'February'],
            ['value' => 3, 'label' => 'March'],
            ['value' => 4, 'label' => 'April'],
            ['value' => 5, 'label' => 'May'],
            ['value' => 6, 'label' => 'June'],
            ['value' => 7, 'label' => 'July'],
            ['value' => 8, 'label' => 'August'],
            ['value' => 9, 'label' => 'September'],
            ['value' => 10, 'label' => 'October'],
            ['value' => 11, 'label' => 'November'],
            ['value' => 12, 'label' => 'December'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.finance-summary');
    }
}
