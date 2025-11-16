<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $suppliers = Supplier::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($builder) use ($search) {
                    $builder->where('first_name', 'like', "%$search%")
                        ->orWhere('last_name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%")
                        ->orWhere('phone', 'like', "%$search%");
                });
            })
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return SupplierResource::collection($suppliers);
    }

    public function store(SupplierStoreRequest $request): SupplierResource
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('suppliers', 'public');
        }

        $supplier = Supplier::create($data);

        return SupplierResource::make($supplier);
    }

    public function show(Supplier $supplier): SupplierResource
    {
        return SupplierResource::make($supplier);
    }

    public function update(SupplierUpdateRequest $request, Supplier $supplier): SupplierResource
    {
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            if ($supplier->avatar) {
                Storage::disk('public')->delete($supplier->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('suppliers', 'public');
        }

        $supplier->update($data);

        return SupplierResource::make($supplier);
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        if ($supplier->avatar) {
            Storage::disk('public')->delete($supplier->avatar);
        }

        $supplier->delete();

        return response()->json([
            'message' => 'Supplier deleted.',
        ]);
    }
}
