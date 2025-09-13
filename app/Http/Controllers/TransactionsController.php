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
use Illuminate\Support\Facades\Log;

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
    public function edit($id)
    {
        $transaction = Transactions::with(['client', 'items'])->findOrFail($id);

        return response()->json([
            'id' => $transaction->id,
            'client_id' => $transaction->client_id,
            'items' => $transaction->items->map(function ($item) {
                // dd($item);
                return [
                    'id' => $item->item->id,
                    'name' => $item->item->name,
                    'price_sell' => $item->item->price_sell,
                    'stock' => $item->item->stock,
                    'pivot' => [
                        'quantity' => $item->quantity
                    ]
                ];
            })
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, $id)
    {
        $transaction = Transactions::with('items')->find($id);

        if (!$transaction) {
            Log::error("Transaction tidak ditemukan", ['id' => $id]);
            return redirect()->back()->with("error", "Transaksi ID {$id} tidak ditemukan");
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'status' => 'required',
        ]);

        try {
            DB::transaction(function () use ($validated, $transaction) {
                $totalPrice = 0;

                // 1️⃣ Balikin stok lama
                foreach ($transaction->items as $oldItem) {
                    $product = Items::findOrFail($oldItem->item_id);
                    $product->increment('stock', $oldItem->quantity);

                    Log::info("Stok dikembalikan", [
                        'item_id' => $product->id,
                        'qty_restore' => $oldItem->quantity
                    ]);
                }

                // 2️⃣ Hapus item yang tidak ada di request
                $requestItemIds = collect($validated['items'])->pluck('item_id')->toArray();
                $transaction->items()->whereNotIn('item_id', $requestItemIds)->delete();

                // 3️⃣ Validasi stok baru & hitung total
                foreach ($validated['items'] as $item) {
                    $product = Items::find($item['item_id']);

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok item '{$product->name}' tidak cukup. Sisa stok: {$product->stock}");
                    }

                    $subtotal = $product->price_sell * $item['quantity'];
                    $totalPrice += $subtotal;

                    // Update / insert transaction item
                    TransactionItem::updateOrCreate(
                        [
                            'transaction_id' => $transaction->id,
                            'item_id' => $product->id,
                        ],
                        [
                            'quantity' => $item['quantity'],
                            'price' => $product->price_sell,
                            'subtotal' => $subtotal,
                        ]
                    );

                    // Kurangi stok
                    $product->decrement('stock', $item['quantity']);

                    Log::info("Item diperbarui & stok dikurangi", [
                        'item_id' => $product->id,
                        'qty' => $item['quantity'],
                        'stok_tersisa' => $product->stock - $item['quantity']
                    ]);
                }

                // 4️⃣ Update transaksi utama
                $transaction->update([
                    'client_id' => $validated['client_id'],
                    'quantity' => array_sum(array_column($validated['items'], 'quantity')),
                    'total_price' => $totalPrice,
                    'status' => $validated['status'],
                ]);

                Log::info("Transaction updated", ['transaction_id' => $transaction->id]);
            });

            Log::info("=== UPDATE TRANSACTION SUCCESS ===", ['transaction_id' => $transaction->id]);
            return redirect()->back()->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("UPDATE TRANSACTION FAILED", [
                'transaction_id' => $id,
                'message' => $e->getMessage()
            ]);

            return redirect()->back()->withErrors($e->getMessage());
        }
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
            ->filterColumn('invoice', function ($query, $keyword) {
                $query->where('transactions.invoice_number', 'like', "%{$keyword}%");
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
                $badgeClass = $row->status === 'pending' ? 'warning' : ($row->status === 'paid' ? 'success' : 'danger');
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

    public function getAllData()
    {
        $transactions = Transactions::with(['client', 'items.item'])->get();
        return response()->json($transactions);
    }
}
