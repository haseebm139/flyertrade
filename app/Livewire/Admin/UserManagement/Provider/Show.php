<?php

namespace App\Livewire\Admin\UserManagement\Provider;

use Livewire\Component;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PasswordResetByAdminMail;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Show extends Component
{
    use WithPagination;

    public $userId;
    public $showResetModal = false;
    public $showDeleteModal = false;
    public $showBookingModal = false;
    public $showServiceModal = false;
    public $selectedBooking = null;
    public $selectedService = null;
    public $activeTab = 'details';
    public $perPage = 10;
    public $search = '';

    public $bookingSortField = 'created_at';
    public $bookingSortDirection = 'desc';
    public $serviceSortField = 'created_at';
    public $serviceSortDirection = 'desc';

    protected $listeners = ['categoryUpdated' => '$refresh'];

    public function mount($userId)
    {
        $this->userId = $userId;

        if (!User::where('id', $this->userId)->exists()) {
            session()->flash('error', 'Provider not found.');
            return redirect()->route('user-management.service.providers.index');
        }
    }

    public function sortBy($field, $type = 'booking')
    {
        if ($type === 'booking') {
            if ($this->bookingSortField === $field) {
                $this->bookingSortDirection = $this->bookingSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                $this->bookingSortField = $field;
                $this->bookingSortDirection = 'asc';
            }
        } else {
            if ($this->serviceSortField === $field) {
                $this->serviceSortDirection = $this->serviceSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                $this->serviceSortField = $field;
                $this->serviceSortDirection = 'asc';
            }
        }
    }

    public function getUserProperty()
    {
        return User::with(['providerProfile'])
            ->withCount(['providerBookings as total_bookings_count',
            'providerBookings as completed_bookings_count' => function ($query) {
                $query->where('status', 'completed');
            },
            'providerBookings as cancelled_bookings_count' => function ($query) {
                $query->where('status', 'cancelled');
            }])
            ->withSum(['providerBookings as total_earned_sum' => function ($query) {
                $query->where('status', 'completed');
            }], 'total_price')
            ->withSum(['providerBookings as total_payout_sum' => function ($query) {
                $query->where('status', 'completed');
            }], DB::raw('total_price - service_charges'))
            ->findOrFail($this->userId);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function resetPassword()
    {
        try {
            $this->showResetModal = false;
            
            $user = $this->user;
            $newPassword = Str::random(10);
            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            dispatch(function () use ($user, $newPassword) {
                try {
                    Mail::to($user->email)->send(new PasswordResetByAdminMail($user, $newPassword));
                } catch (\Exception $e) {
                    \Log::error('Failed to send reset password email to provider: ' . $user->email . ' - ' . $e->getMessage());
                }
            })->afterResponse();

            $this->dispatch('showSweetAlert', 'success', 'Password reset successfully and sent to provider email.', 'Success');
        } catch (\Exception $e) {
            \Log::error('Error resetting password: ' . $e->getMessage());
            $this->dispatch('showSweetAlert', 'error', 'Error resetting password: ' . $e->getMessage(), 'Error');
        }
    }

    public function toggleStatus()
    {
        try {
            $user = $this->user;
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();

            $this->dispatch('showSweetAlert', 'success', 'Provider status updated successfully.', 'Success');
        } catch (\Exception $e) {
            \Log::error('Error updating status: ' . $e->getMessage());
            $this->dispatch('showSweetAlert', 'error', 'Error updating status: ' . $e->getMessage(), 'Error');
        }
    }

    public function deleteUser()
    {
        try {
            $this->showDeleteModal = false;

            $user = $this->user;
            $user->delete();

            session()->flash('success_delete', 'Provider deleted successfully.');
            return redirect()->route('user-management.service.providers.index');
        } catch (\Exception $e) {
            \Log::error('Error deleting provider: ' . $e->getMessage());
            $this->dispatch('showSweetAlert', 'error', 'Error deleting provider: ' . $e->getMessage(), 'Error');
        }
    }

    public function viewBooking($id)
    {
        $this->selectedBooking = Booking::with(['customer', 'service'])->find($id);
        if ($this->selectedBooking) {
            $this->showBookingModal = true;
        }
    }

    public function closeBookingModal()
    {
        $this->showBookingModal = false;
        $this->selectedBooking = null;
    }

    public function downloadBookingDetails($id)
    {
        $booking = Booking::with(['customer', 'provider', 'service'])->find($id);
        if (!$booking) {
            $this->dispatch('showSweetAlert', 'error', 'Booking not found.', 'Error');
            return;
        }

        $fileName = "booking-{$booking->booking_ref}.csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () use ($booking) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Field', 'Details']);
            fputcsv($handle, ['Booking ID', $booking->booking_ref]);
            fputcsv($handle, ['Date', $booking->created_at->format('d M, Y')]);
            fputcsv($handle, ['Time', $booking->created_at->format('h:i A')]);
            fputcsv($handle, ['Duration', $booking->duration ?? '-']);
            fputcsv($handle, ['Location', $booking->booking_address ?? '-']);
            fputcsv($handle, ['Service Type', $booking->service->name ?? '-']);
            fputcsv($handle, ['Service Cost', '$' . number_format($booking->total_price, 2)]);
            fputcsv($handle, ['Status', ucfirst($booking->status)]);
            fputcsv($handle, ['Service Provider', $booking->provider->name ?? '-']);
            fputcsv($handle, ['Service User', $booking->customer->name ?? '-']);
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function viewService($id)
    {
        $this->selectedService = \App\Models\ProviderService::with(['service', 'media'])->find($id);
        if ($this->selectedService) {
            $this->showServiceModal = true;
        }
    }

    public function closeServiceModal()
    {
        $this->showServiceModal = false;
        $this->selectedService = null;
    }

    public function render()
    {
        $bookings = [];
        $providerServices = [];

        if ($this->activeTab === 'history') {
            $bookings = Booking::where('provider_id', $this->userId)
                ->orderBy($this->bookingSortField, $this->bookingSortDirection)
                ->paginate($this->perPage);
        } elseif ($this->activeTab === 'services') {
            $providerServices = $this->user->providerServices()
                ->with('service')
                ->join('services', 'provider_services.service_id', '=', 'services.id')
                ->select('provider_services.*')
                ->orderBy($this->serviceSortField === 'service_name' ? 'services.name' : 'provider_services.'.$this->serviceSortField, $this->serviceSortDirection)
                ->paginate($this->perPage);
        }

        return view('livewire.admin.user-management.provider.show', [
            'user' => $this->user,
            'bookings' => $bookings,
            'providerServices' => $providerServices,
        ]);
    }
}
