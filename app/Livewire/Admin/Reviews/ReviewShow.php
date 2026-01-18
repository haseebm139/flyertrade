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
        $this->loadReview();
    }

    public function loadReview()
    {
        $this->review = Review::with(['reviewer', 'reviewedProvider', 'booking', 'service'])->findOrFail($this->reviewId);
        $this->reviewText = $this->review->review;
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function saveReview()
    {
        $this->review->update([
            'review' => $this->reviewText
        ]);
        $this->isEditing = false;
        $this->dispatch('showSweetAlert', type: 'success', message: 'Review updated successfully');
    }

    public function setStatus($status)
    {
        $this->review->update(['status' => strtolower($status)]);
        $this->dispatch('showSweetAlert', type: 'success', message: 'Status updated to ' . $status);
    }

    public function delete()
    {
        $this->review->delete();
        $this->dispatch('showSweetAlert', type: 'success', message: 'Review deleted successfully');
        return redirect()->route('reviews.index');
    }

    public function render()
    {
        return view('livewire.admin.reviews.review-show');
    }
}
