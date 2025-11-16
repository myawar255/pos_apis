<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *    title="POS API",
 *     description="Comprehensive endpoints for the POS React client. All authenticated requests require the Bearer token obtained from the auth endpoints."
 * )
 * @OA\Server(
 *     url="/api/v1",
 *     description="Version 1 API"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="BearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token",
 *     description="Use the token returned by /auth/login or /auth/register"
 * )
 * @OA\Tag(name="Auth", description="Authentication and token management")
 * @OA\Tag(name="Products", description="Product catalogue management")
 * @OA\Tag(name="Customers", description="Customer records")
 * @OA\Tag(name="Suppliers", description="Supplier directory")
 * @OA\Tag(name="Cart", description="POS sales cart for the logged in cashier")
 * @OA\Tag(name="Purchase Cart", description="Inbound purchase cart handling")
 * @OA\Tag(name="Orders", description="Sales orders and payments")
 * @OA\Tag(name="Settings", description="Storefront configuration values")
 */
class OpenApiSpec
{
    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register a new staff account",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Account created",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponse")
     *     )
     * )
     */
    public function register(): void {}

    /**
     * @OA\Post(
     *     path="/auth/login",
     *     summary="Issue a bearer token for existing staff",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Authenticated",
     *         @OA\JsonContent(ref="#/components/schemas/AuthResponse")
     *     ),
     *     @OA\Response(response=422, description="Invalid credentials")
     * )
     */
    public function login(): void {}

    /**
     * @OA\Get(
     *     path="/auth/me",
     *     summary="Show the currently authenticated user",
     *     tags={"Auth"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Current user",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="user", ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function me(): void {}

    /**
     * @OA\Post(
     *     path="/auth/logout",
     *     summary="Revoke the active token",
     *     tags={"Auth"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logged out",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function logout(): void {}

    /**
     * @OA\Get(
     *     path="/products",
     *     summary="List products",
     *     tags={"Products"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="search", in="query", description="Filter by name or barcode", @OA\Schema(type="string")),
     *     @OA\Parameter(name="status", in="query", @OA\Schema(type="boolean")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="Paginated products", @OA\JsonContent(ref="#/components/schemas/ProductCollection"))
     * )
     */
    public function listProducts(): void {}

    /**
     * @OA\Post(
     *     path="/products",
     *     summary="Create a product",
     *     tags={"Products"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/ProductInput"))),
     *     @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/Product"))
     * )
     */
    public function storeProduct(): void {}

    /**
     * @OA\Get(
     *     path="/products/{product}",
     *     summary="Show product",
     *     tags={"Products"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Product", @OA\JsonContent(ref="#/components/schemas/Product"))
     * )
     */
    public function showProduct(): void {}

    /**
     * @OA\Put(
     *     path="/products/{product}",
     *     summary="Update product",
     *     tags={"Products"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/ProductInput"))),
     *     @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/Product"))
     * )
     */
    public function updateProduct(): void {}

    /**
     * @OA\Delete(
     *     path="/products/{product}",
     *     summary="Delete product",
     *     tags={"Products"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Deleted", @OA\JsonContent(type="object", @OA\Property(property="message", type="string")))
     * )
     */
    public function deleteProduct(): void {}

    /**
     * @OA\Patch(
     *     path="/products/{product}/quantity",
     *     summary="Update stock level",
     *     tags={"Products"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(required={"quantity"}, @OA\Property(property="quantity", type="integer", minimum=0))),
     *     @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/Product"))
     * )
     */
    public function updateProductQuantity(): void {}

    /**
     * @OA\Get(
     *     path="/customers",
     *     summary="List customers",
     *     tags={"Customers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="search", in="query", description="Search name, email or phone", @OA\Schema(type="string")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="Customers", @OA\JsonContent(ref="#/components/schemas/CustomerCollection"))
     * )
     */
    public function listCustomers(): void {}

    /**
     * @OA\Post(
     *     path="/customers",
     *     summary="Create customer",
     *     tags={"Customers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/CustomerInput"))),
     *     @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/Customer"))
     * )
     */
    public function storeCustomer(): void {}

    /**
     * @OA\Get(
     *     path="/customers/{customer}",
     *     summary="Show customer",
     *     tags={"Customers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="customer", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Customer", @OA\JsonContent(ref="#/components/schemas/Customer"))
     * )
     */
    public function showCustomer(): void {}

    /**
     * @OA\Put(
     *     path="/customers/{customer}",
     *     summary="Update customer",
     *     tags={"Customers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="customer", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/CustomerInput"))),
     *     @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/Customer"))
     * )
     */
    public function updateCustomer(): void {}

    /**
     * @OA\Delete(
     *     path="/customers/{customer}",
     *     summary="Remove customer",
     *     tags={"Customers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="customer", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Deleted", @OA\JsonContent(type="object", @OA\Property(property="success", type="boolean")))
     * )
     */
    public function deleteCustomer(): void {}

    /**
     * @OA\Get(
     *     path="/suppliers",
     *     summary="List suppliers",
     *     tags={"Suppliers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="search", in="query", @OA\Schema(type="string")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="Suppliers", @OA\JsonContent(ref="#/components/schemas/SupplierCollection"))
     * )
     */
    public function listSuppliers(): void {}

    /**
     * @OA\Post(
     *     path="/suppliers",
     *     summary="Create supplier",
     *     tags={"Suppliers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/SupplierInput"))),
     *     @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/Supplier"))
     * )
     */
    public function storeSupplier(): void {}

    /**
     * @OA\Get(
     *     path="/suppliers/{supplier}",
     *     summary="Show supplier",
     *     tags={"Suppliers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="supplier", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Supplier", @OA\JsonContent(ref="#/components/schemas/Supplier"))
     * )
     */
    public function showSupplier(): void {}

    /**
     * @OA\Put(
     *     path="/suppliers/{supplier}",
     *     summary="Update supplier",
     *     tags={"Suppliers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="supplier", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\MediaType(mediaType="multipart/form-data", @OA\Schema(ref="#/components/schemas/SupplierInput"))),
     *     @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/Supplier"))
     * )
     */
    public function updateSupplier(): void {}

    /**
     * @OA\Delete(
     *     path="/suppliers/{supplier}",
     *     summary="Delete supplier",
     *     tags={"Suppliers"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="supplier", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Deleted", @OA\JsonContent(type="object", @OA\Property(property="success", type="boolean")))
     * )
     */
    public function deleteSupplier(): void {}

    /**
     * @OA\Get(
     *     path="/settings",
     *     summary="Fetch key/value settings",
     *     tags={"Settings"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(response=200, description="Settings", @OA\JsonContent(type="object", @OA\Property(property="settings", ref="#/components/schemas/Settings")))
     * )
     */
    public function getSettings(): void {}

    /**
     * @OA\Put(
     *     path="/settings",
     *     summary="Update settings",
     *     tags={"Settings"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/Settings")),
     *     @OA\Response(response=200, description="Saved", @OA\JsonContent(type="object", @OA\Property(property="message", type="string"), @OA\Property(property="settings", ref="#/components/schemas/Settings")))
     * )
     */
    public function updateSettings(): void {}

    /**
     * @OA\Get(
     *     path="/cart",
     *     summary="List cart items",
     *     tags={"Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(response=200, description="Current cart", @OA\JsonContent(ref="#/components/schemas/CartCollection"))
     * )
     */
    public function cartIndex(): void {}

    /**
     * @OA\Post(
     *     path="/cart",
     *     summary="Add product to cart",
     *     tags={"Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/CartAddRequest")),
     *     @OA\Response(response=201, description="Updated cart", @OA\JsonContent(ref="#/components/schemas/CartMutationResponse"))
     * )
     */
    public function cartStore(): void {}

    /**
     * @OA\Patch(
     *     path="/cart/{product}",
     *     summary="Change cart quantity",
     *     tags={"Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(required={"quantity"}, @OA\Property(property="quantity", type="integer", minimum=1))),
     *     @OA\Response(response=200, description="Updated cart", @OA\JsonContent(ref="#/components/schemas/CartMutationResponse"))
     * )
     */
    public function cartUpdate(): void {}

    /**
     * @OA\Delete(
     *     path="/cart/{product}",
     *     summary="Remove product from cart",
     *     tags={"Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Updated cart", @OA\JsonContent(ref="#/components/schemas/CartMutationResponse"))
     * )
     */
    public function cartDestroy(): void {}

    /**
     * @OA\Delete(
     *     path="/cart",
     *     summary="Empty cart",
     *     tags={"Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(response=200, description="Cleared", @OA\JsonContent(ref="#/components/schemas/CartMutationResponse"))
     * )
     */
    public function cartEmpty(): void {}

    /**
     * @OA\Get(
     *     path="/purchase-cart",
     *     summary="List purchase cart",
     *     tags={"Purchase Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(response=200, description="Current purchase cart", @OA\JsonContent(ref="#/components/schemas/CartCollection"))
     * )
     */
    public function purchaseCartIndex(): void {}

    /**
     * @OA\Post(
     *     path="/purchase-cart",
     *     summary="Add product to purchase cart",
     *     tags={"Purchase Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/PurchaseCartAddRequest")),
     *     @OA\Response(response=201, description="Updated", @OA\JsonContent(ref="#/components/schemas/CartMutationResponse"))
     * )
     */
    public function purchaseCartStore(): void {}

    /**
     * @OA\Patch(
     *     path="/purchase-cart/{product}",
     *     summary="Change purchase cart quantity",
     *     tags={"Purchase Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(required={"quantity"}, @OA\Property(property="quantity", type="integer", minimum=1))),
     *     @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/CartMutationResponse"))
     * )
     */
    public function purchaseCartUpdate(): void {}

    /**
     * @OA\Delete(
     *     path="/purchase-cart/{product}",
     *     summary="Remove product from purchase cart",
     *     tags={"Purchase Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="product", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Updated", @OA\JsonContent(ref="#/components/schemas/CartMutationResponse"))
     * )
     */
    public function purchaseCartDestroy(): void {}

    /**
     * @OA\Delete(
     *     path="/purchase-cart",
     *     summary="Empty purchase cart",
     *     tags={"Purchase Cart"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Response(response=200, description="Cleared", @OA\JsonContent(ref="#/components/schemas/CartMutationResponse"))
     * )
     */
    public function purchaseCartEmpty(): void {}

    /**
     * @OA\Get(
     *     path="/orders",
     *     summary="List orders",
     *     tags={"Orders"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="customer_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="start_date", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="end_date", in="query", @OA\Schema(type="string", format="date")),
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer", default=15)),
     *     @OA\Response(response=200, description="Orders", @OA\JsonContent(ref="#/components/schemas/OrderCollection"))
     * )
     */
    public function listOrders(): void {}

    /**
     * @OA\Post(
     *     path="/orders",
     *     summary="Create an order from the current cart",
     *     tags={"Orders"},
     *     security={{"BearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(required={"amount"}, @OA\Property(property="customer_id", type="integer", nullable=true), @OA\Property(property="amount", type="number", format="float"))),
     *     @OA\Response(response=201, description="Order created", @OA\JsonContent(type="object", @OA\Property(property="message", type="string"), @OA\Property(property="order", ref="#/components/schemas/Order")))
     * )
     */
    public function storeOrder(): void {}

    /**
     * @OA\Get(
     *     path="/orders/{order}",
     *     summary="Show order",
     *     tags={"Orders"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="order", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Order", @OA\JsonContent(ref="#/components/schemas/Order"))
     * )
     */
    public function showOrder(): void {}

    /**
     * @OA\Post(
     *     path="/orders/{order}/payments",
     *     summary="Record an additional payment",
     *     tags={"Orders"},
     *     security={{"BearerAuth":{}}},
     *     @OA\Parameter(name="order", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(required={"amount"}, @OA\Property(property="amount", type="number", format="float", minimum=0.01))),
     *     @OA\Response(response=200, description="Updated order", @OA\JsonContent(type="object", @OA\Property(property="message", type="string"), @OA\Property(property="order", ref="#/components/schemas/Order")))
     * )
     */
    public function storePayment(): void {}
}
