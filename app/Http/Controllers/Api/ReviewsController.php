<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController;
use App\Models\Review;
use App\Models\Booking; 
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
class ReviewsController extends BaseController
{
    /**
     * Create a review for a completed booking
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'booking_id' => 'required|exists:bookings,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $bookingId = $request->booking_id;
        $customer = auth()->user();
        
        // Find the booking
        $booking = Booking::with(['provider', 'service'])->find($bookingId);
        
        if (!$booking) {
            return $this->sendError('Booking not found.', 404);
        }

        // Verify the booking belongs to the customer
        // if ($booking->customer_id !== $customer->id) {
        //     return $this->sendError('Unauthorized. This booking does not belong to you.', 403);
        // }

        // Check if booking is completed
        // if ($booking->status !== 'completed') {
        //     return $this->sendError('You can only review completed bookings.', 400);
        // }

        // Check if review already exists for this booking
        // $existingReview = Review::where('booking_id', $bookingId)->first();
        // if ($existingReview) {
        //     return $this->sendError('You have already reviewed this booking.', 400);
        // }

        // Create the review
        $review = Review::create([
            'booking_id' => $booking->id,
            'sender_id' => $customer->id,
            'receiver_id' => $booking->provider_id,
            'service_id' => $booking->service_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'status' => 'pending', // Default status, can be changed by admin/provider
        ]);

        return $this->sendResponse($review->load(['reviewer', 'service', 'reviewedProvider']), 'Review submitted successfully.');
    }

    public function index(Request $request): JsonResponse
    {
        $provider = auth()->user();
        
        $query = Review::with(['service', 'reviewedProvider', 'booking'])
            ->where('receiver_id', $provider->id);

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'published', 'unpublished'])) {
            $query->where('status', $request->status);
        }

        // // Sort by date (default: newest first)
        // $sortBy = $request->get('sort_by', 'created_at');
        // $sortOrder = $request->get('sort_order', 'desc');
        // $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $reviews = $query->paginate($perPage);

        return $this->sendResponse([
            'reviews' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ], 'Reviews retrieved successfully.');
    }
}
