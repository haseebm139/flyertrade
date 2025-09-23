<?php

namespace App\Http\Controllers\Api;

use App\Events\Offers\OfferCreated;
use App\Events\Offers\OfferFinalized;
use App\Events\Offers\OfferResponded;
use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Offer;
use App\Models\OfferRevision;
use App\Services\OfferCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OffersController extends Controller
{
    public function create(Request $request, int $conversationId)
    {
        $user = $request->user();
        $conversation = Conversation::findOrFail($conversationId);
        abort_unless($conversation->participants()->where('user_id', $user->id)->exists(), 403);

        $data = $request->validate([
            'service_type' => ['required','string','max:191'],
            'time_from' => ['nullable','date'],
            'time_to' => ['nullable','date','after_or_equal:time_from'],
            'description' => ['nullable','string'],
            'provider_id' => ['required','integer','exists:users,id'],
        ]);

        $offer = Offer::create([
            'conversation_id' => $conversation->id,
            'customer_id' => $user->id,
            'provider_id' => (int)$data['provider_id'],
            'service_type' => $data['service_type'],
            'time_from' => $data['time_from'] ?? null,
            'time_to' => $data['time_to'] ?? null,
            'description' => $data['description'] ?? null,
            'status' => 'pending',
        ]);

        event(new OfferCreated($offer));

        return response()->json(['offer_id' => $offer->id], 201);
    }

    public function respond(Request $request, int $offerId, OfferCalculator $calc)
    {
        $user = $request->user();
        $offer = Offer::findOrFail($offerId);

        $data = $request->validate([
            'action' => ['required','in:accept,decline,counter,bargain'],
            'cost_items' => ['nullable','array'],
            'cost_items.*.cost_type' => ['required_with:cost_items','string'],
            'cost_items.*.amount' => ['required_with:cost_items','numeric','min:0'],
            'materials' => ['nullable','array'],
            'materials.*.name' => ['required_with:materials','string'],
            'materials.*.qty' => ['required_with:materials','numeric','min:0'],
            'materials.*.price' => ['required_with:materials','numeric','min:0'],
            'flat_fee' => ['nullable','numeric','min:0'],
            'currency' => ['nullable','string','max:8'],
            'notes' => ['nullable','string'],
        ]);

        return DB::transaction(function () use ($user, $offer, $data, $calc) {
            $action = $data['action'];

            $revision = null;
            if (in_array($action, ['counter','bargain'])) {
                $totals = $calc->calculateTotals($data['cost_items'] ?? [], $data['materials'] ?? [], (float)($data['flat_fee'] ?? 0));
                $revision = OfferRevision::create([
                    'offer_id' => $offer->id,
                    'by_user_id' => $user->id,
                    'cost_items' => $data['cost_items'] ?? [],
                    'materials' => $data['materials'] ?? [],
                    'flat_fee' => $data['flat_fee'] ?? 0,
                    'currency' => $data['currency'] ?? 'USD',
                    'subtotal' => $totals['subtotal'],
                    'tax' => $totals['tax'],
                    'total' => $totals['total'],
                    'notes' => $data['notes'] ?? null,
                ]);
                $offer->current_revision_id = $revision->id;
                $offer->status = $action === 'counter' ? 'countered' : 'bargained';
                $offer->responded_at = now();
                $offer->save();
            } elseif ($action === 'accept') {
                $offer->status = 'accepted';
                $offer->responded_at = now();
                $offer->save();
            } elseif ($action === 'decline') {
                $offer->status = 'declined';
                $offer->responded_at = now();
                $offer->save();
            }

            event(new OfferResponded($offer, $revision));

            return response()->json(['status' => $offer->status]);
        });
    }

    public function finalize(Request $request, int $offerId)
    {
        $user = $request->user();
        $offer = Offer::findOrFail($offerId);

        // Only customer can finalize
        abort_unless($offer->customer_id === $user->id, 403);

        $offer->status = 'finalized';
        $offer->finalized_at = now();
        $offer->save();

        event(new OfferFinalized($offer));

        return response()->json(['status' => $offer->status]);
    }
}

