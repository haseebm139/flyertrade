<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\BaseController;
use App\Models\Review;
use App\Models\Booking;
use App\Services\Notification\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class ReviewController extends BaseController
{
    public function __construct(private NotificationService $notificationService) {}
     
    /**
     * Create a review for a completed booking
     */
    public function store(Request $request, $bookingId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $customer = auth()->user();
        
        // Find the booking
        $booking = Booking::with(['provider', 'service'])->find($bookingId);
        
        if (!$booking) {
            return $this->sendError('Booking not found.', 404);
        }

        // Verify the booking belongs to the customer
        if ($booking->customer_id !== $customer->id) {
            return $this->sendError('Unauthorized. This booking does not belong to you.', 403);
        }

        // Check if booking is completed
        if ($booking->status !== 'completed') {
            return $this->sendError('You can only review completed bookings.', 400);
        }

        // Check if review already exists for this booking
        $existingReview = Review::where('booking_id', $bookingId)->first();
        if ($existingReview) {
            return $this->sendError('You have already reviewed this booking.', 400);
        }

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

        // Send notifications
        $this->notificationService->notifyReviewReceived($review);
        $this->notificationService->notifyNewReviewPosted($review);

        return $this->sendResponse($review->load(['reviewer', 'service', 'reviewedProvider']), 'Review submitted successfully.');
    }

    /**
     * Get all reviews received by the authenticated provider
     */
    public function index(Request $request): JsonResponse
    {
        $provider = auth()->user();
        
        $query = Review::with(['service', 'reviewer', 'reviewedProvider', 'booking'])
            ->where('receiver_id', $provider->id);

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'published', 'unpublished'])) {
            $query->where('status', $request->status);
        }

        // Filter by rating (stars) - exact match
        if ($request->has('rating')) {
            $rating = (int) $request->rating;
            // Validate rating is between 1 and 5
            if ($rating >= 1 && $rating <= 5) {
                $query->where('rating', $rating);
            }
        }

        // Filter by minimum rating
        if ($request->has('min_rating')) {
            $minRating = (int) $request->min_rating;
            if ($minRating >= 1 && $minRating <= 5) {
                $query->where('rating', '>=', $minRating);
            }
        }

        // Filter by maximum rating
        if ($request->has('max_rating')) {
            $maxRating = (int) $request->max_rating;
            if ($maxRating >= 1 && $maxRating <= 5) {
                $query->where('rating', '<=', $maxRating);
            }
        }

        // Sort by date (default: newest first)
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

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

    /**
     * Get a single review by ID
     */
    public function show($id): JsonResponse
    {
        $customer = auth()->user();
        
        $review = Review::with(['service', 'reviewedProvider', 'booking'])
            ->where('sender_id', $customer->id)
            ->find($id);

        if (!$review) {
            return $this->sendError('Review not found.', 404);
        }

        return $this->sendResponse($review, 'Review retrieved successfully.');
    }

    /**
     * Update a review (only if status is pending)
     */
    public function update(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'review' => 'sometimes|nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $customer = auth()->user();
        
        $review = Review::where('sender_id', $customer->id)
            ->find($id);

        if (!$review) {
            return $this->sendError('Review not found.', 404);
        }

        // Only allow updates if review is pending
        if ($review->status !== 'pending') {
            return $this->sendError('You can only update pending reviews.', 400);
        }

        $updateData = [];
        if ($request->has('rating')) {
            $updateData['rating'] = $request->rating;
        }
        if ($request->has('review')) {
            $updateData['review'] = $request->review;
        }

        $review->update($updateData);

        return $this->sendResponse($review->load(['reviewer', 'service', 'reviewedProvider']), 'Review updated successfully.');
    }

    /**
     * Delete a review (only if status is pending)
     */
    public function destroy($id): JsonResponse
    {
        $customer = auth()->user();
        
        $review = Review::where('sender_id', $customer->id)
            ->find($id);

        if (!$review) {
            return $this->sendError('Review not found.', 404);
        }

        // Only allow deletion if review is pending
        if ($review->status !== 'pending') {
            return $this->sendError('You can only delete pending reviews.', 400);
        }

        $review->delete();

        return $this->sendResponse([], 'Review deleted successfully.');
    }

    /**
     * Get reviews written by the authenticated customer
     */
    public function myReviews(Request $request): JsonResponse
    {
        $customer = auth()->user();
        
        $query = Review::with(['service', 'reviewer', 'reviewedProvider', 'booking'])
            ->where('sender_id', $customer->id);

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['pending', 'published', 'unpublished'])) {
            $query->where('status', $request->status);
        }

        // Filter by rating
        if ($request->has('rating')) {
            $rating = (int) $request->rating;
            if ($rating >= 1 && $rating <= 5) {
                $query->where('rating', $rating);
            }
        }

        // Sort by date (default: newest first)
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

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
        ], 'My reviews retrieved successfully.');
    }

    /**
     * Update review status (for provider/admin)
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,published,unpublished',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first(), 422);
        }

        $user = auth()->user();
        
        // Find review
        $review = Review::find($id);
        
        if (!$review) {
            return $this->sendError('Review not found.', 404);
        }

        // Check if user is the provider (receiver) or admin
        $isProvider = $review->receiver_id === $user->id;
        $isAdmin = $user->role_id === 'admin' || $user->user_type === 'admin';

        if (!$isProvider && !$isAdmin) {
            return $this->sendError('Unauthorized. You can only update reviews for your own profile.', 403);
        }

        $review->update(['status' => $request->status]);

        return $this->sendResponse($review->load(['reviewer', 'service', 'reviewedProvider', 'booking']), 'Review status updated successfully.');
    }

    /**
     * Get review statistics for provider
     */
    public function statistics(): JsonResponse
    {
        $provider = auth()->user();
        
        $stats = [
            'total_reviews' => Review::where('receiver_id', $provider->id)->count(),
            'published_reviews' => Review::where('receiver_id', $provider->id)->where('status', 'published')->count(),
            'pending_reviews' => Review::where('receiver_id', $provider->id)->where('status', 'pending')->count(),
            'unpublished_reviews' => Review::where('receiver_id', $provider->id)->where('status', 'unpublished')->count(),
            'average_rating' => round(Review::where('receiver_id', $provider->id)->where('status', 'published')->avg('rating') ?? 0, 2),
            'rating_distribution' => [
                '5_stars' => Review::where('receiver_id', $provider->id)->where('status', 'published')->where('rating', 5)->count(),
                '4_stars' => Review::where('receiver_id', $provider->id)->where('status', 'published')->where('rating', 4)->count(),
                '3_stars' => Review::where('receiver_id', $provider->id)->where('status', 'published')->where('rating', 3)->count(),
                '2_stars' => Review::where('receiver_id', $provider->id)->where('status', 'published')->where('rating', 2)->count(),
                '1_star' => Review::where('receiver_id', $provider->id)->where('status', 'published')->where('rating', 1)->count(),
            ],
        ];

        return $this->sendResponse($stats, 'Review statistics retrieved successfully.');
    }

    /**
     * Get reviews for a specific provider (public endpoint)
     */
    public function providerReviews(Request $request, $providerId): JsonResponse
    {
        $query = Review::with(['service', 'reviewer', 'reviewedProvider', 'booking'])
            ->where('receiver_id', $providerId)
            ->where('status', 'published'); // Only published reviews

        // Filter by rating
        if ($request->has('rating')) {
            $rating = (int) $request->rating;
            if ($rating >= 1 && $rating <= 5) {
                $query->where('rating', $rating);
            }
        }

        // Filter by minimum rating
        if ($request->has('min_rating')) {
            $minRating = (int) $request->min_rating;
            if ($minRating >= 1 && $minRating <= 5) {
                $query->where('rating', '>=', $minRating);
            }
        }

        // Sort by date (default: newest first)
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

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
        ], 'Provider reviews retrieved successfully.');
    }
}

