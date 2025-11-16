<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderStoreRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $ordersQuery = Order::with(['items.product', 'payments', 'customer', 'user'])
            ->when($request->filled('customer_id'), fn ($query) => $query->where('customer_id', $request->integer('customer_id')))
            ->when($request->filled('start_date'), fn ($query) => $query->whereDate('created_at', '>=', $request->input('start_date')))
            ->when($request->filled('end_date'), fn ($query) => $query->whereDate('created_at', '<=', $request->input('end_date')))
            ->orderByDesc('created_at');

        $totalsQuery = clone $ordersQuery;
        $orders = $ordersQuery->paginate($request->integer('per_page', 15));
        $aggregated = $totalsQuery->get();
        $gross = $aggregated->sum(fn ($order) => $order->total());
        $received = $aggregated->sum(fn ($order) => $order->receivedAmount());

        return OrderResource::collection($orders)->additional([
            'meta' => [
                'gross' => $gross,
                'received' => $received,
                'balance' => $gross - $received,
            ],
        ]);
    }

    public function store(OrderStoreRequest $request): JsonResponse
    {
        $cart = $request->user()->cart()->withPivot('quantity')->get();
        if ($cart->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty.',
            ], 422);
        }

        $order = DB::transaction(function () use ($request, $cart) {
            $order = Order::create([
                'customer_id' => $request->input('customer_id'),
                'user_id' => $request->user()->id,
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'price' => $item->price * $item->pivot->quantity,
                    'quantity' => $item->pivot->quantity,
                    'product_id' => $item->id,
                ]);

                $item->decrement('quantity', $item->pivot->quantity);
            }

            $request->user()->cart()->detach();

            $order->payments()->create([
                'amount' => $request->input('amount'),
                'user_id' => $request->user()->id,
            ]);

            return $order;
        });

        $order->load(['items.product', 'payments', 'customer', 'user']);

        return response()->json([
            'message' => __('order.sale_completed'),
            'order' => OrderResource::make($order),
        ], 201);
    }

    public function show(Order $order): OrderResource
    {
        $order->load(['items.product', 'payments', 'customer', 'user']);

        return OrderResource::make($order);
    }
}
