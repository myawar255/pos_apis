<?php

namespace App\OpenApi;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="email", type="string", format="email")
 * )
 * @OA\Schema(
 *     schema="RegisterRequest",
 *     required={"first_name","last_name","email","password","password_confirmation"},
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="password", type="string", format="password"),
 *     @OA\Property(property="password_confirmation", type="string", format="password")
 * )
 * @OA\Schema(
 *     schema="LoginRequest",
 *     required={"email","password"},
 *     @OA\Property(property="email", type="string", format="email"),
 *     @OA\Property(property="password", type="string", format="password")
 * )
 * @OA\Schema(
 *     schema="AuthResponse",
 *     type="object",
 *     @OA\Property(property="token", type="string"),
 *     @OA\Property(property="user", ref="#/components/schemas/User")
 * )
 * @OA\Schema(
 *     schema="ProductInput",
 *     required={"name","barcode","price","quantity","status"},
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="image", type="string", format="binary", nullable=true),
 *     @OA\Property(property="barcode", type="string"),
 *     @OA\Property(property="price", type="number", format="float"),
 *     @OA\Property(property="quantity", type="integer"),
 *     @OA\Property(property="status", type="boolean")
 * )
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string", nullable=true),
 *     @OA\Property(property="barcode", type="string"),
 *     @OA\Property(property="price", type="number", format="float"),
 *     @OA\Property(property="quantity", type="integer"),
 *     @OA\Property(property="status", type="boolean"),
 *     @OA\Property(property="image_url", type="string", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true)
 * )
 * @OA\Schema(
 *     schema="ProductCollection",
 *     type="object",
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Product"))
 * )
 * @OA\Schema(
 *     schema="CustomerInput",
 *     required={"first_name","last_name"},
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="email", type="string", format="email", nullable=true),
 *     @OA\Property(property="phone", type="string", nullable=true),
 *     @OA\Property(property="address", type="string", nullable=true),
 *     @OA\Property(property="avatar", type="string", format="binary", nullable=true)
 * )
 * @OA\Schema(
 *     schema="Customer",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="email", type="string", nullable=true),
 *     @OA\Property(property="phone", type="string", nullable=true),
 *     @OA\Property(property="address", type="string", nullable=true),
 *     @OA\Property(property="avatar_url", type="string", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true)
 * )
 * @OA\Schema(
 *     schema="CustomerCollection",
 *     type="object",
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Customer"))
 * )
 * @OA\Schema(
 *     schema="SupplierInput",
 *     required={"first_name","last_name"},
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="email", type="string", format="email", nullable=true),
 *     @OA\Property(property="phone", type="string", nullable=true),
 *     @OA\Property(property="address", type="string", nullable=true),
 *     @OA\Property(property="avatar", type="string", format="binary", nullable=true)
 * )
 * @OA\Schema(
 *     schema="Supplier",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="first_name", type="string"),
 *     @OA\Property(property="last_name", type="string"),
 *     @OA\Property(property="email", type="string", nullable=true),
 *     @OA\Property(property="phone", type="string", nullable=true),
 *     @OA\Property(property="address", type="string", nullable=true),
 *     @OA\Property(property="avatar_url", type="string", nullable=true)
 * )
 * @OA\Schema(
 *     schema="SupplierCollection",
 *     type="object",
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Supplier"))
 * )
 * @OA\Schema(
 *     schema="CartAddRequest",
 *     type="object",
 *     description="Provide either barcode or product_id",
 *     @OA\Property(property="barcode", type="string", nullable=true),
 *     @OA\Property(property="product_id", type="integer", nullable=true)
 * )
 * @OA\Schema(
 *     schema="PurchaseCartAddRequest",
 *     type="object",
 *     required={"product_id","quantity"},
 *     @OA\Property(property="product_id", type="integer"),
 *     @OA\Property(property="quantity", type="integer", minimum=1)
 * )
 * @OA\Schema(
 *     schema="CartItem",
 *     type="object",
 *     @OA\Property(property="product_id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="barcode", type="string"),
 *     @OA\Property(property="price", type="number", format="float"),
 *     @OA\Property(property="quantity", type="integer"),
 *     @OA\Property(property="stock", type="integer"),
 *     @OA\Property(property="subtotal", type="number", format="float")
 * )
 * @OA\Schema(
 *     schema="CartCollection",
 *     type="object",
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CartItem"))
 * )
 * @OA\Schema(
 *     schema="CartMutationResponse",
 *     type="object",
 *     @OA\Property(property="message", type="string"),
 *     @OA\Property(property="cart", type="array", @OA\Items(ref="#/components/schemas/CartItem"))
 * )
 * @OA\Schema(
 *     schema="OrderItem",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="product_id", type="integer"),
 *     @OA\Property(property="quantity", type="integer"),
 *     @OA\Property(property="price", type="number", format="float")
 * )
 * @OA\Schema(
 *     schema="Payment",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="amount", type="number", format="float"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true)
 * )
 * @OA\Schema(
 *     schema="Order",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="customer", type="object", nullable=true,
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="first_name", type="string"),
 *         @OA\Property(property="last_name", type="string")
 *     ),
 *     @OA\Property(property="user", type="object",
 *         @OA\Property(property="id", type="integer"),
 *         @OA\Property(property="first_name", type="string"),
 *         @OA\Property(property="last_name", type="string")
 *     ),
 *     @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/OrderItem")),
 *     @OA\Property(property="payments", type="array", @OA\Items(ref="#/components/schemas/Payment")),
 *     @OA\Property(property="totals", type="object",
 *         @OA\Property(property="gross", type="number", format="float"),
 *         @OA\Property(property="received", type="number", format="float"),
 *         @OA\Property(property="balance", type="number", format="float")
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true)
 * )
 * @OA\Schema(
 *     schema="OrderCollection",
 *     type="object",
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Order")),
 *     @OA\Property(property="meta", type="object",
 *         @OA\Property(property="gross", type="number", format="float"),
 *         @OA\Property(property="received", type="number", format="float"),
 *         @OA\Property(property="balance", type="number", format="float")
 *     )
 * )
 * @OA\Schema(
 *     schema="Settings",
 *     type="object",
 *     @OA\Property(property="app_name", type="string", nullable=true),
 *     @OA\Property(property="app_description", type="string", nullable=true),
 *     @OA\Property(property="currency_symbol", type="string", nullable=true),
 *     @OA\Property(property="warning_quantity", type="integer", nullable=true)
 * )
 */
class Schemas
{
}
