<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view('admin.items.index', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'stock' => 'required|integer|min:0',
            'category' => 'required',
            'price_buy' => 'required|numeric|min:0',
            'price_sell' => 'required|numeric|min:0',
            'supplier_id' => 'required',
        ]);

        // ambil kode terakhir
        $lastItem = Items::latest('id')->first();
        $number = $lastItem ? ((int) substr($lastItem->code, 3)) + 1 : 1;
        $newCode = 'BRG' . str_pad($number, 4, '0', STR_PAD_LEFT);

        Items::create([
            'code' => $newCode,
            'name' => $request->name,
            'category' => $request->category,
            'stock' => $request->stock,
            'price_buy' => $request->price_buy,
            'price_sell' => $request->price_sell,
            'supplier_id' => $request->supplier_id
        ]);

        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Items $item)
    {
        $request->validate([
            // 'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required',
            'stock' => 'required|integer|min:0',
            'category' => 'required',
            'price_buy' => 'required|numeric|min:0',
            'price_sell' => 'required|numeric|min:0',
            'supplier_id' => 'required',
        ]);

        $item->update($request->only([
            'name', 'category', 'stock', 'price_buy', 'price_sell', 'supplier_id'
        ]));


        return redirect()->route('items.index')
            ->with('success', 'Barang berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
    $item = Items::findOrFail($id);
    $item->delete();

    return response()->json(['success' => true]);
    }

 public function getData(Request $request)
    {
        if ($request->ajax()) {
            $items = Items::with('supplier')->select(['id','code','supplier_id','name','category','stock','price_buy','price_sell']);

             if ($request->supplier_id) {
            $items->where('supplier_id', $request->supplier_id);
        }
            return DataTables::of($items)
                ->addColumn('supplier', function ($item) {
                    return $item->supplier ? $item->supplier->name : '-';
                    // return dd($item->supplier);
                })
                ->addColumn('action', function ($item) {
                    return '
                        <button class="btn btn-sm btn-success editBtn"
                            data-id="'.$item->id.'"
                            data-code="'.$item->code.'"
                            data-supplier_id="'.$item->supplier_id.'"
                            data-name="'.$item->name.'"
                            data-category="'.$item->category.'"
                            data-stock="'.$item->stock.'"
                            data-price_buy="'.$item->price_buy.'"
                            data-price_sell="'.$item->price_sell.'"
                            data-bs-toggle="modal" data-bs-target="#editModal">
                            <i class="fas fa-edit fs-6"></i>
                        </button>
                        <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$item->id.'" data-url="'.route('items.destroy', $item->id).'">
                            <i class="fas fa-trash fs-6"></i>
                        </button>
                    ';
                })
                ->editColumn('price_buy', function ($item) {
                    return 'Rp '.number_format($item->price_buy, 0, ',', '.');
                })
                ->editColumn('price_sell', function ($item) {
                    return 'Rp '.number_format($item->price_sell, 0, ',', '.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }
}
