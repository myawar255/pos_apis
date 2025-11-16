<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PurchaseCartController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = $request->user()->purchaseCart()->get();

        return CartItemResource::collection($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $existing = $request->user()->purchaseCart()->where('products.id', $validated['product_id'])->first();

        if ($existing) {
            $existing->pivot->quantity += $validated['quantity'];
            $existing->pivot->save();
        } else {
            $request->user()->purchaseCart()->attach($validated['product_id'], ['quantity' => $validated['quantity']]);
        }

        $items = $request->user()->purchaseCart()->get();

        $cartData = CartItemResource::collection($items)->toArray($request)['data'] ?? [];

        return response()->json([
            'message' => 'Purchase cart updated.',
            'cart' => $cartData,
        ], 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = $request->user()->purchaseCart()->where('products.id', $product->id)->first();
        if (! $item) {
            return response()->json([
                'message' => 'Item not found in purchase cart.',
            ], 404);
        }

        $item->pivot->quantity = $validated['quantity'];
        $item->pivot->save();

        $items = $request->user()->purchaseCart()->get();

        $cartData = CartItemResource::collection($items)->toArray($request)['data'] ?? [];

        return response()->json([
            'message' => 'Purchase cart updated.',
            'cart' => $cartData,
        ]);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $request->user()->purchaseCart()->detach($product->id);

        $items = $request->user()->purchaseCart()->get();

        $cartData = CartItemResource::collection($items)->toArray($request)['data'] ?? [];

        return response()->json([
            'message' => 'Item removed from purchase cart.',
            'cart' => $cartData,
        ]);
    }

    public function empty(Request $request): JsonResponse
    {
        $request->user()->purchaseCart()->detach();

        return response()->json([
            'message' => 'Purchase cart cleared.',
            'cart' => [],
        ]);
    }
}
