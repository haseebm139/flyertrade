<?php

namespace App\Livewire\Admin\UserManagement\User;

use Livewire\Component;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\PasswordResetByAdminMail;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $userId;
    public $showResetModal = false;
    public $showDeleteModal = false;
    public $showBookingModal = false;
    public $selectedBooking = null;
    public $activeTab = 'details';
    public $perPage = 10;
    public $search = '';

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['categoryUpdated' => '$refresh'];

    public function mount($userId)
    {
        $this->userId = $userId;

        // Optional: Pre-check if user exists
        if (!User::where('id', $this->userId)->exists()) {
            session()->flash('error', 'User not found.');
            return redirect()->route('user-management.service.users.index');
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getUserProperty()
    {
        return User::withCount(['customerBookings as total_bookings_count',
            'customerBookings as completed_bookings_count' => function ($query) {
                $query->where('status', 'completed');
            },
            'customerBookings as cancelled_bookings_count' => function ($query) {
                $query->where('status', 'cancelled');
            }])
            ->withSum('customerBookings as total_spent_sum', 'total_price')
            ->findOrFail($this->userId);
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function openEditUserForm(): void
    {
        $this->dispatch('open-customer-form', id: $this->userId)
            ->to(Form::class);
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

            // Send email with new credentials
            dispatch(function () use ($user, $newPassword) {
                try {
                    Mail::to($user->email)->send(new PasswordResetByAdminMail($user, $newPassword));
                } catch (\Exception $e) {
                    \Log::error('Failed to send reset password email to user: ' . $user->email . ' - ' . $e->getMessage());
                }
            })->afterResponse();

            $this->dispatch('showSweetAlert', 'success', 'Password reset successfully and sent to user email.', 'Success');
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

            $this->dispatch('showSweetAlert', 'success', 'User status updated successfully.', 'Success');
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

            session()->flash('success_delete', 'User deleted successfully.');
            return redirect()->route('user-management.service.users.index');
        } catch (\Exception $e) {
            \Log::error('Error deleting user: ' . $e->getMessage());
            $this->dispatch('showSweetAlert', 'error', 'Error deleting user: ' . $e->getMessage(), 'Error');
        }
    }

    public function viewBooking($id)
    {
        $this->selectedBooking = Booking::with(['provider', 'service'])->find($id);
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

    public function render()
    {
        $bookings = [];
        if ($this->activeTab === 'history') {

            $bookings = Booking::where('customer_id', $this->userId)
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
            //  dd($this->getUserProperty());
        }
        return view('livewire.admin.user-management.user.show', [
            'user' => $this->user,
            'bookings' => $bookings,

        ]);
    }
}
