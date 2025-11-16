<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierStoreRequest;
use App\Http\Requests\SupplierUpdateRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->wantsJson()) {
            return response(
                Supplier::all()
            );
        }
        $suppliers = Supplier::latest()->paginate(10);
        return view('suppliers.index')->with('suppliers', $suppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SupplierStoreRequest $request)
    {
        $avatar_path = '';

        if ($request->hasFile('avatar')) {
            $avatar_path = $request->file('avatar')->store('suppliers', 'public');
        }

        $supplier = Supplier::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'avatar' => $avatar_path,
        ]);

        if (!$supplier) {
            return redirect()->back()->with('error', __('supplier.error_creating'));
        }
        return redirect()->route('suppliers.index')->with('success', __('supplier.success_creating'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        // Implement logic to show details of a specific supplier
        return view('suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(SupplierUpdateRequest $request, Supplier $supplier)
    {
        $supplier->first_name = $request->first_name;
        $supplier->last_name = $request->last_name;
        $supplier->email = $request->email;
        $supplier->phone = $request->phone;
        $supplier->address = $request->address;

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($supplier->avatar) {
                Storage::delete($supplier->avatar);
            }
            // Store new avatar
            $avatar_path = $request->file('avatar')->store('suppliers', 'public');
            // Save to Database
            $supplier->avatar = $avatar_path;
        }

        if (!$supplier->save()) {
            return redirect()->back()->with('error', __('supplier.error_updating'));
        }
        return redirect()->route('suppliers.index')->with('success', __('supplier.success_updating'));
    }

    public function destroy(Supplier $supplier)
    {
        if ($supplier->avatar) {
            Storage::delete($supplier->avatar);
        }

        $supplier->delete();

        return response()->json([
            'success' => true
        ]);
    }
}
