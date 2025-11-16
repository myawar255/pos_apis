<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderPaymentController extends Controller
{
    public function store(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $remaining = $order->total() - $order->receivedAmount();
        if ($validated['amount'] > $remaining) {
            return response()->json([
                'message' => 'Amount exceeds outstanding balance.',
            ], 422);
        }

        DB::transaction(function () use ($order, $request, $validated) {
            $order->payments()->create([
                'amount' => $validated['amount'],
                'user_id' => $request->user()->id,
            ]);
        });

        $order->load(['items.product', 'payments', 'customer', 'user']);

        return response()->json([
            'message' => 'Payment recorded.',
            'order' => OrderResource::make($order),
        ]);
    }
}
