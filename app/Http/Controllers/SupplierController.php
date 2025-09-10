<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $request->validate([
            'name' => 'required',
            'pic' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'nullable',
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required',
            'pic' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'nullable',
        ]);

        $supplier->update($request->all());

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diupdate');    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(['success' => true]);
    }

     public function getData(Request $request)
    {
        if ($request->ajax()) {
            $supplier = Supplier::select(['id','name','pic','address','phone','email']);

            return DataTables::of($supplier)
                ->addColumn('action', function ($item) {
                    return '
                        <button class="btn btn-sm btn-success editBtn"
                            data-id="'.$item->id.'"
                            data-name="'.$item->name.'"
                            data-pic="'.$item->pic.'"
                            data-address="'.$item->address.'"
                            data-phone="'.$item->phone.'"
                            data-email="'.$item->email.'"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-edit fs-6"></i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$item->id.'" data-url="'.route('suppliers.destroy', $item->id).'">
                            <i class="fas fa-trash fs-6"></i>
                        </button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
