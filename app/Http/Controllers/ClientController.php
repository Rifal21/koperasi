<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.clients.index');
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
            'address' => 'required',
            'phone' => 'required',
            'email' => 'nullable',
            'pic' => 'required',
        ]);

        Client::create($request->all());

        return redirect()->route('clients.index')
            ->with('success', 'Client berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'nullable',
            'pic' => 'required',
        ]);

        $client->update($request->only([
            'name', 'address', 'phone', 'email', 'pic'
        ]));

        return redirect()->route('clients.index')
            ->with('success', 'Client berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
        return response()->json(['success' => true]);
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $clients = Client::select(['id', 'name', 'pic', 'address', 'phone', 'email']);

            return DataTables::of($clients)
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
