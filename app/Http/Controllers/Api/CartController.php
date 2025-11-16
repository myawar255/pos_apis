<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartItemResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CartController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $cart = $request->user()->cart()->get();

        return CartItemResource::collection($cart);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'barcode' => ['required_without:product_id', 'string', 'exists:products,barcode'],
            'product_id' => ['required_without:barcode', 'integer', 'exists:products,id'],
        ]);

        $product = isset($validated['product_id'])
            ? Product::findOrFail($validated['product_id'])
            : Product::where('barcode', $validated['barcode'])->first();

        $cartProduct = $request->user()->cart()->where('products.id', $product->id)->first();

        if ($cartProduct) {
            $newQty = $cartProduct->pivot->quantity + 1;
            if ($product->quantity < $newQty) {
                return response()->json([
                    'message' => __('cart.available', ['quantity' => $product->quantity]),
                ], 400);
            }

            $cartProduct->pivot->quantity = $newQty;
            $cartProduct->pivot->save();
        } else {
            if ($product->quantity < 1) {
                return response()->json([
                    'message' => __('cart.outstock'),
                ], 400);
            }

            $request->user()->cart()->attach($product->id, ['quantity' => 1]);
        }

        $cart = $request->user()->cart()->get();

        $cartData = CartItemResource::collection($cart)->toArray($request)['data'] ?? [];

        return response()->json([
            'message' => 'Cart updated.',
            'cart' => $cartData,
        ], 201);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartItem = $request->user()->cart()->where('products.id', $product->id)->first();
        if (! $cartItem) {
            return response()->json([
                'message' => 'Item not found in cart.',
            ], 404);
        }

        if ($product->quantity < $validated['quantity']) {
            return response()->json([
                'message' => __('cart.available', ['quantity' => $product->quantity]),
            ], 400);
        }

        $cartItem->pivot->quantity = $validated['quantity'];
        $cartItem->pivot->save();

        $cart = $request->user()->cart()->get();

        $cartData = CartItemResource::collection($cart)->toArray($request)['data'] ?? [];

        return response()->json([
            'message' => 'Quantity updated.',
            'cart' => $cartData,
        ]);
    }

    public function destroy(Request $request, Product $product): JsonResponse
    {
        $request->user()->cart()->detach($product->id);

        $cart = $request->user()->cart()->get();

        $cartData = CartItemResource::collection($cart)->toArray($request)['data'] ?? [];

        return response()->json([
            'message' => 'Item removed.',
            'cart' => $cartData,
        ]);
    }

    public function empty(Request $request): JsonResponse
    {
        $request->user()->cart()->detach();

        return response()->json([
            'message' => 'Cart cleared.',
            'cart' => [],
        ]);
    }
}
