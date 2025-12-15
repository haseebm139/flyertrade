<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\StripeService;
use App\Models\UserPaymentMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
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
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: ' . $e->getMessage(),
            ], 422);
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

        return response()->json([
            'success' => true,
            'message' => 'Card added successfully.',
            'data' => $record,
        ]);
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

        return response()->json([
            'success' => true,
            'data' => $cards,
        ]);
    }

    /**
     * Set a card as default for current user.
     */
    public function makeDefault($id)
    {
        $card = UserPaymentMethod::where('user_id', Auth::id())->findOrFail($id);

        $user = Auth::user();
        $customerId = $this->stripe->ensureCustomer($user);

        try {
            $this->stripe->attachPaymentMethod($customerId, $card->stripe_payment_method_id, true);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe error: ' . $e->getMessage(),
            ], 422);
        }

        DB::transaction(function () use ($card) {
            UserPaymentMethod::where('user_id', $card->user_id)->update(['is_default' => false]);
            $card->update(['is_default' => true]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Default card updated.',
        ]);
    }
}


