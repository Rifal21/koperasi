<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionsRequest;
use App\Http\Requests\UpdateTransactionsRequest;
use App\Models\Client;
use App\Models\Items;
use App\Models\TransactionItem;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransactionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        $items = Items::where('stock', '>', 0)->get();
        return view('admin.transaksi.index', compact('clients', 'items'));
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
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $totalPrice = 0;

        // Mulai transaksi DB untuk rollback jika gagal
        DB::beginTransaction();

        try {
            // Validasi stok
            foreach ($request->items as $item) {
                $product = Items::findOrFail($item['item_id']);
                if ($product->stock < $item['quantity']) {
                    return redirect()->back()->withErrors("Stok item '{$product->name}' tidak cukup. Sisa stok: {$product->stock}");
                }
                $totalPrice += $product->price_sell * $item['quantity'];
            }

            $prefix = 'INV-' . date('Ymd') . '-';
            $lastTransaction = Transactions::whereDate('created_at', now()->toDateString())
                ->orderBy('id', 'desc')
                ->first();

            if ($lastTransaction) {
                // Ambil nomor terakhir, lalu increment
                $lastNumber = (int) substr($lastTransaction->invoice_number, -4);
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $invoiceNumber = $prefix . $newNumber;

            // Buat transaksi
            $transaction = Transactions::create([
                'invoice_number' => $invoiceNumber,
                'client_id' => $validated['client_id'],
                'quantity' => array_sum(array_column($request->items, 'quantity')),
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);

            // Simpan transaction items & kurangi stock
            foreach ($request->items as $item) {
                $product = Items::findOrFail($item['item_id']);

                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'item_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price_sell,
                    'subtotal' => $product->price_sell * $item['quantity'],
                ]);

                // Kurangi stock
                $product->decrement('stock', $item['quantity']);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil dibuat.');
        } catch (\Exception $e) {
            // dd($e);
            DB::rollBack();
            return redirect()->back()->withErrors('Gagal membuat transaksi: ' . $e->getMessage());
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Transactions $transactions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transactions $transactions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $transaction = Transactions::findOrFail($id);

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required',
            'quantity' => 'required|integer',
            'total_price' => 'required',
        ]);

        $transaction->update($validated);

        return redirect()->back()->with('success', 'Transaksi berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = Transactions::findOrFail($id);
        $transaction->delete();

        return response()->json(['success' => true]);
    }

public function getData()
{
    $transactions = Transactions::with(['client', 'items.item'])->select('transactions.*');

    return DataTables::of($transactions)
        ->addColumn('invoice', function ($row) {
            return $row->invoice_number;
        })
        ->addColumn('client', function ($row) {
            return $row->client ? $row->client->name : '-';
        })
        ->addColumn('pic', function ($row) {
            return $row->client ? $row->client->pic : '-';
        })
        ->addColumn('items', function ($row) {
            // dd($row->items);
            if (!$row->items || $row->items->isEmpty()) {
                return '-';
            }
            $itemsList = '<ul class="mb-0 ps-3">';
            foreach ($row->items as $item) {
                $itemsList .= '<li>' . e($item->item->name) . ' (Qty: ' . $item->quantity . ')</li>';
            }
            $itemsList .= '</ul>';
            return $itemsList;
        })
        ->addColumn('quantity', function ($row) {
            return $row->quantity;
        })
        ->addColumn('total_price', function ($row) {
            return 'Rp ' . number_format($row->total_price, 0, ',', '.');
        })
        ->addColumn('status', function ($row) {
            $badgeClass = $row->status === 'pending' ? 'warning' : 'success';
            return '<span class="badge bg-' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
        })
        ->addColumn('action', function ($row) {
            $editBtn = '<button class="btn btn-success btn-sm editBtn" data-id="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-edit fs-6"></i></button>';
            $deleteBtn = '<button class="btn btn-danger btn-sm deleteBtn" data-url="' . route('transactions.destroy', $row->id) . '"><i class="fas fa-trash fs-6"></i></button>';
            return $editBtn . ' ' . $deleteBtn;
        })
        ->rawColumns(['items', 'status', 'action'])
        ->make(true);
}

}
