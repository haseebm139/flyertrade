<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Services\Payment\StripeService;
use App\Models\UserPaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Stripe\PaymentMethod;

class PaymentController extends BaseController
{
    public function __construct(private StripeService $stripe) {}

    /**
     * Add a card (Stripe payment_method) for the authenticated user (customer or provider).
     * Body: { "payment_method_id": "pm_xxx", "make_default": true/false }
     */
    public function addCard(Request $request)
    {
        $data = $request->validate([
            'payment_method_id' => 'required|string',
            'make_default' => 'sometimes|boolean',
        ]);

        $user = Auth::user();
        $makeDefault = (bool) ($data['make_default'] ?? false);

        $customerId = $this->stripe->ensureCustomer($user);

        try {
            $paymentMethod = $this->stripe->attachPaymentMethod(
                customerId: $customerId,
                paymentMethodId: $data['payment_method_id'],
                makeDefault: $makeDefault
            );
        } catch (\Throwable $e) {
            return $this->sendError('Stripe error: '.$e->getMessage(), 422);
        }

        $card = $paymentMethod->card;

        $record = DB::transaction(function () use ($user, $paymentMethod, $card, $makeDefault) {
            if ($makeDefault) {
                UserPaymentMethod::where('user_id', $user->id)->update(['is_default' => false]);
            }

            return UserPaymentMethod::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'stripe_payment_method_id' => $paymentMethod->id,
                ],
                [
                    'brand' => $card->brand ?? null,
                    'last4' => $card->last4 ?? null,
                    'exp_month' => $card->exp_month ?? null,
                    'exp_year' => $card->exp_year ?? null,
                    'is_default' => $makeDefault,
                ]
            );
        });

        return $this->sendResponse($record, 'Card added successfully.');
    }

    /**
     * List cards for current user.
     */
    public function listCards()
    {
        $cards = UserPaymentMethod::where('user_id', Auth::id())
            ->orderByDesc('is_default')
            ->latest()
            ->get();

        return $this->sendResponse($cards, 'Cards fetched successfully.');
    }

    /**
     * Set a card as default for current user.
     */
    public function makeDefault($id)
    {
        $card = UserPaymentMethod::where('user_id', Auth::id())->find($id);

        if (empty($card)) {
            return $this->sendError('Card not found.', 404);
        }
        $user = Auth::user();
        $customerId = $this->stripe->ensureCustomer($user);

        try {
            $this->stripe->attachPaymentMethod($customerId, $card->stripe_payment_method_id, true);
        } catch (\Throwable $e) {
            return $this->sendError('Stripe error: '.$e->getMessage(), 422);
        }

        DB::transaction(function () use ($card) {
            UserPaymentMethod::where('user_id', $card->user_id)->update(['is_default' => false]);
            $card->update(['is_default' => true]);
        });

        return $this->sendResponse([], 'Default card updated.');
    }

    /**
     * TEST ONLY: Create a test payment_method using Stripe test token.
     * Use this to get a valid payment_method_id for testing addCard API.
     * Body: { "test_token": "tok_visa" } (optional, defaults to tok_visa)
     */
    public function createTestPaymentMethod(Request $request)
    {
        $testToken = $request->input('test_token', 'tok_visa'); // Default Stripe test token

        try {
            $paymentMethod = PaymentMethod::create([
                'type' => 'card',
                'card' => ['token' => $testToken],
            ]);

            return $this->sendResponse([
                'payment_method_id' => $paymentMethod->id,
                'message' => 'Test payment_method created. Use this payment_method_id in addCard API.',
            ], 'Test payment_method created successfully.');
        } catch (\Throwable $e) {
            return $this->sendError('Stripe error: '.$e->getMessage(), 422);
        }
    }
}


