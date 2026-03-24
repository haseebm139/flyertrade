<?php

namespace App\Livewire\Admin\Reviews;

use Livewire\Component;
use App\Models\Review;

class ReviewShow extends Component
{
    public $reviewId;
    public $review;
    public $reviewText;
    public $isEditing = false;

    public function mount($id)
    {
        $this->reviewId = $id;
        $result = $this->loadReview();
        if ($result instanceof \Illuminate\Http\RedirectResponse) {
            return $result;
        }
    }

    public function loadReview()
    {
        try {
            $this->review = Review::with(['reviewer', 'reviewedProvider', 'booking', 'service'])->findOrFail($this->reviewId);
            $this->reviewText = $this->review->review;
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('error_toastr', 'Review not found.');
            return redirect()->route('reviews.index');
        }
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function saveReview()
    {
        if (!auth()->user()->can('Write Reviews')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized action.', 'Error');
            return;
        }
        $this->review->update([
            'review' => $this->reviewText,
        ]);
        $this->review->refresh();
        $this->isEditing = false;
        $this->dispatch('showSweetAlert', 'success', 'Review updated successfully.', 'Success');
    }

    public function setStatus($status)
    {
        if (!auth()->user()->can('Write Reviews')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized action.', 'Error');
            return;
        }
        $this->review->update(['status' => strtolower($status)]);
        $this->review->refresh();
        $this->dispatch('showSweetAlert', 'success', 'Status updated to ' . ucfirst(strtolower($status)) . '.', 'Success');
    }

    public function delete()
    {
        if (!auth()->user()->can('Delete Reviews')) {
            $this->dispatch('showSweetAlert', 'error', 'Unauthorized action.', 'Error');
            return;
        }
        $this->review->delete();
        $this->dispatch('showSweetAlert', 'success', 'Review deleted successfully.', 'Success');
        $this->redirect(route('reviews.index'));
    }

    public function render()
    {
        return view('livewire.admin.reviews.review-show');
    }
}
