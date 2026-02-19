<?php

namespace App\Livewire\Admin\Transactions;

use App\Models\Transaction;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Table extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $fromDate = '';
    public $toDate = '';
    public $transactionType = '';
    public $paymentMethod = '';
    public $showFilterModal = false;
    public $activeFilters = [];

    public $tempFromDate = '';
    public $tempToDate = '';
    public $tempTransactionType = '';
    public $tempPaymentMethod = '';

    public $selected = [];
    public $selectAll = false;

    public $showTransactionModal = false;
    public $selectedTransaction = null;

    public function openFilterModal(): void
    {
        $this->tempFromDate = $this->fromDate;
        $this->tempToDate = $this->toDate;
        $this->tempTransactionType = $this->transactionType;
        $this->tempPaymentMethod = $this->paymentMethod;
        $this->showFilterModal = true;
    }

    public function closeFilterModal(): void
    {
        $this->showFilterModal = false;
    }

    public function updatingSearch($value): void
    {
        $this->search = $value;
        $this->resetPage();
    }

    public function applyFilters(): void
    {
        $this->fromDate = $this->tempFromDate;
        $this->toDate = $this->tempToDate;
        $this->transactionType = $this->tempTransactionType;
        $this->paymentMethod = $this->tempPaymentMethod;

        $this->resetPage();
        $this->closeFilterModal();
        $this->activeFilters = $this->getActiveFilters();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'fromDate',
            'toDate',
            'transactionType',
            'paymentMethod',
            'tempFromDate',
            'tempToDate',
            'tempTransactionType',
            'tempPaymentMethod',
        ]);
        $this->resetPage();
        $this->closeFilterModal();
        $this->activeFilters = $this->getActiveFilters();
    }

    public function removeFilter($key = null): void
    {
        if (is_array($key) && isset($key['key'])) {
            $key = $key['key'];
        }

        if ($key === 'date') {
            $this->fromDate = '';
            $this->toDate = '';
        } elseif ($key === 'transaction_type') {
            $this->transactionType = '';
        } elseif ($key === 'payment_method') {
            $this->paymentMethod = '';
        }

        $this->resetPage();
        $this->activeFilters = $this->getActiveFilters();
    }

    public function getActiveFilters(): array
    {
        $filters = [];

        if ($this->fromDate && $this->toDate) {
            $filters[] = [
                'type' => 'date',
                'label' => date('d M, Y', strtotime($this->fromDate)) . ' - ' . date('d M, Y', strtotime($this->toDate)),
                'key' => 'date',
            ];
        }

        if ($this->transactionType) {
            $labels = [
                'payout' => 'Payout',
                'booking_payment' => 'Booking Payment',
                'service_charges' => 'Service Charges',
            ];
            $filters[] = [
                'type' => 'transaction_type',
                'label' => $labels[$this->transactionType] ?? ucfirst(str_replace('_', ' ', $this->transactionType)),
                'key' => 'transaction_type',
            ];
        }

        if ($this->paymentMethod) {
            $labels = [
                'mobile_money' => 'Mobile money',
                'paystack' => 'Paystack',
                'card' => 'Card',
            ];
            $filters[] = [
                'type' => 'payment_method',
                'label' => $labels[$this->paymentMethod] ?? ucfirst(str_replace('_', ' ', $this->paymentMethod)),
                'key' => 'payment_method',
            ];
        }

        return $filters;
    }

    public function updatedSelectAll($value): void
    {
        if ($value) {
            $this->selected = $this->getDataQuery()->pluck('id')->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected(): void
    {
        $this->selectAll = false;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedPerPage($value): void
    {
        $this->perPage = (int) $value;
        $this->resetPage();
    }

    private function getDataQuery()
    {
        $search = $this->search;

        return Transaction::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('transaction_ref', 'like', "%{$search}%")
                        ->orWhere('stripe_charge_id', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$search}%"))
                        ->orWhereHas('provider', fn($q) => $q->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($this->fromDate && $this->toDate, fn($q) =>
                $q->whereBetween('created_at', [
                    $this->fromDate . ' 00:00:00',
                    $this->toDate . ' 23:59:59',
                ])
            )
            ->when($this->transactionType, function ($query) {
                if ($this->transactionType === 'payout') {
                    $query->where('type', 'payout');
                } elseif ($this->transactionType === 'booking_payment') {
                    $query->where('type', 'payment');
                } elseif ($this->transactionType === 'service_charges') {
                    $query->where('type', 'payment')
                        ->where('service_charges', '>', 0);
                }
            })
            ->when($this->paymentMethod, function ($query) {
                if ($this->paymentMethod === 'card') {
                    $query->where(function ($subQuery) {
                        $subQuery->whereNotNull('stripe_payment_method_id')
                            ->orWhere('metadata->payment_method', 'card')
                            ->orWhere('metadata->payment_method_type', 'card');
                    });
                } else {
                    $query->where(function ($subQuery) {
                        $subQuery->where('metadata->payment_method', $this->paymentMethod)
                            ->orWhere('metadata->payment_method_type', $this->paymentMethod)
                            ->orWhere('metadata->gateway', $this->paymentMethod);
                    });
                }
            })
            ->with(['customer:id,name,avatar', 'provider:id,name,avatar'])
            ->orderBy($this->sortField, $this->sortDirection);
    }

    public function render()
    {
        $data = $this->getDataQuery()->paginate($this->perPage);
        $this->activeFilters = $this->getActiveFilters();

        return view('livewire.admin.transactions.table', [
            'data' => $data,
            'activeFilters' => $this->activeFilters,
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'transactions.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Transaction ID',
                'Type',
                'Status',
                'Amount',
                'Currency',
                'Payment Method',
                'Associated User',
                'Created At',
            ]);

            $transactions = $this->getDataQuery()->get();
            foreach ($transactions as $transaction) {
                $associatedUser = $this->resolveAssociatedUser($transaction);
                fputcsv($handle, [
                    $transaction->transaction_ref ?? $transaction->id,
                    $this->getTransactionTypeLabel($transaction),
                    $this->getStatusLabel($transaction),
                    $transaction->amount,
                    strtoupper($transaction->currency ?? ''),
                    $this->getPaymentMethodLabel($transaction),
                    $associatedUser?->name ?? '',
                    $transaction->created_at?->format('Y-m-d H:i:s') ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function viewTransaction($id): void
    {
        if (!auth()->user()?->can('Read Transactions')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized access.', 'Error');
            return;
        }
        $this->selectedTransaction = Transaction::with(['customer', 'provider'])->find($id);
        if ($this->selectedTransaction) {
            $this->showTransactionModal = true;
        }
    }

    public function initiatePayout($id): void
    {
        if (!auth()->user()?->can('Write Transactions')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized action.', 'Error');
            return;
        }

        $transaction = Transaction::find($id);
        if (!$transaction) {
            $this->dispatch('showSweetAlert', 'error', 'Transaction not found.', 'Error');
            return;
        }

        if ($transaction->type !== 'payout') {
            $this->dispatch('showSweetAlert', 'error', 'Only payout transactions can be initiated.', 'Error');
            return;
        }

        if (in_array($transaction->status, ['succeeded', 'cancelled', 'failed'], true)) {
            $this->dispatch('showSweetAlert', 'error', 'This payout cannot be initiated.', 'Error');
            return;
        }

        $transaction->update([
            'status' => 'processing',
            'processed_at' => $transaction->processed_at ?? now(),
        ]);

        $this->dispatch('showSweetAlert', 'success', 'Payout initiated successfully.', 'Success');
    }

    public function closeTransactionModal(): void
    {
        $this->showTransactionModal = false;
        $this->selectedTransaction = null;
    }

    public function downloadTransactionDetails($id): StreamedResponse
    {
        if (!auth()->user()?->can('Read Transactions')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized action.', 'Error');
            return response()->stream(fn () => null, 403);
        }

        $transaction = Transaction::with(['customer', 'provider'])->find($id);
        if (!$transaction) {
            $this->dispatch('showSweetAlert', 'error', 'Transaction not found.', 'Error');
            return response()->stream(fn () => null, 404);
        }

        $fileName = "transaction-{$transaction->transaction_ref}.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($transaction) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Field', 'Details']);
            fputcsv($handle, ['Transaction ID', $transaction->transaction_ref ?? $transaction->id]);
            $date = $transaction->processed_at ?? $transaction->created_at;
            fputcsv($handle, ['Date', $date?->format('d M, Y')]);
            fputcsv($handle, ['Time', $date?->format('h:i A')]);
            fputcsv($handle, ['Transaction Type', $this->getTransactionTypeLabel($transaction)]);
            fputcsv($handle, ['Payment Method', $this->getPaymentMethodLabel($transaction)]);
            fputcsv($handle, ['Amount', $this->formatAmount($transaction->amount, $transaction->currency)]);
            fputcsv($handle, ['Status', $this->getStatusLabel($transaction)]);
            $associatedUser = $this->resolveAssociatedUser($transaction);
            fputcsv($handle, ['Associated User', $associatedUser?->name ?? '-']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function formatAmount($amount, $currency): string
    {
        $amount = $amount ?? 0;
        $currency = strtoupper($currency ?? '');
        $formatted = number_format((float) $amount, 2);

        if ($currency === 'USD' || $currency === '') {
            return '$' . $formatted;
        }

        return $currency . ' ' . $formatted;
    }

    public function getTransactionTypeLabel(Transaction $transaction): string
    {
        return match ($transaction->type) {
            'payment' => 'Booking Payment',
            'refund' => 'Refund',
            'payout' => 'Payout',
            default => ucfirst(str_replace('_', ' ', $transaction->type ?? '')),
        };
    }

    public function getStatusLabel(Transaction $transaction): string
    {
        return match ($transaction->status) {
            'succeeded' => 'Completed',
            'processing' => 'Processing',
            'pending' => 'Pending',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
            'refunded' => 'Refunded',
            default => ucfirst(str_replace('_', ' ', $transaction->status ?? '')),
        };
    }

    public function getStatusClass(Transaction $transaction): string
    {
        return match ($transaction->status) {
            'pending', 'processing' => 'pending',
            'succeeded', 'refunded' => 'active',
            'failed', 'cancelled' => 'cancelled',
            default => 'inactive',
        };
    }

    public function getPaymentMethodLabel(Transaction $transaction): string
    {
        $metadata = $transaction->metadata ?? [];
        $method = $metadata['payment_method']
            ?? $metadata['payment_method_type']
            ?? $metadata['gateway']
            ?? null;

        if (!$method && $transaction->stripe_payment_method_id) {
            return 'Card';
        }

        if (!$method) {
            return 'N/A';
        }

        return match ($method) {
            'mobile_money' => 'Mobile money',
            'paystack' => 'Paystack',
            'card' => 'Card',
            default => ucfirst(str_replace('_', ' ', (string) $method)),
        };
    }

    public function resolveAssociatedUser(Transaction $transaction)
    {
        if ($transaction->type === 'payout') {
            return $transaction->provider;
        }

        return $transaction->customer ?? $transaction->provider;
    }
}
