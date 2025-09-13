@extends('admin.layouts.main')

@section('contentDashboard')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Daftar Transaksi</h4>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#itemModal">
                Tambah Transaksi
            </button>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table id="transactionsTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Client</th>
                            <th>PIC / Penanggung Jawab</th>
                            <th>Barang</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Item / POS -->
    <div class="modal fade" id="itemModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <form class="modal-content" action="{{ route('transactions.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body table-responsive">

                    {{-- Pilih Client --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Pilih Client <span class="text-danger">*</span></label>
                            <select name="client_id" class="form-select" required>
                                <option value="">-- Pilih Client --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->pic }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Search Item --}}
                    <input type="text" id="searchItem" class="form-control mb-3" placeholder="Cari item...">

                    {{-- Daftar Item --}}
                    <table class="table table-bordered table-striped table-sm align-middle" id="transactionItemsTable">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 25%">Item</th>
                                <th style="width: 15%">Harga</th>
                                <th style="width: 10%">Stok</th>
                                <th style="width: 15%">Qty</th>
                                <th style="width: 20%">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $index => $item)
                                <tr data-item-id="{{ $item->id }}">
                                    <td>
                                        <input type="hidden" name="items[{{ $index }}][item_id]"
                                            value="{{ $item->id }}">
                                        {{ $item->name }}
                                    </td>
                                    <td class="price text-end" data-value="{{ $item->price_sell }}">
                                        Rp {{ number_format($item->price_sell) }}
                                    </td>
                                    <td class="text-center">{{ $item->stock }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm qty text-center"
                                            name="items[{{ $index }}][quantity]" value="0" min="0"
                                            max="{{ $item->stock }}">
                                    </td>
                                    <td class="subtotal text-end">Rp 0</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h5>Total: <span id="totalPrice">Rp 0</span></h5>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>


    {{-- Modal Edit --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <form class="modal-content" id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    {{-- Pilih Client --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Pilih Client <span class="text-danger">*</span></label>
                            <select name="client_id" class="form-select" id="edit_client_id" required>
                                <option value="">-- Pilih Client --</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->pic }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Daftar Item --}}
                    <table class="table table-bordered table-striped table-sm align-middle" id="editTransactionItemsTable">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th style="width: 25%">Item</th>
                                <th style="width: 15%">Harga</th>
                                <th style="width: 10%">Stok</th>
                                <th style="width: 15%">Qty</th>
                                <th style="width: 20%">Subtotal</th>
                                <th style="width: 20%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Data transaksi akan dimuat via AJAX --}}
                        </tbody>
                    </table>
                    {{-- Tambah Item Baru --}}
                    <div class="row g-3 mb-3">
                      <h3 class="form-label">Tambah Item</h3>
                        <div class="col-md-8">
                            <select id="addItemSelect" class="form-select">
                                <option value="">-- Pilih Item --</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" data-name="{{ $item->name }}"
                                        data-price="{{ $item->price_sell }}" data-stock="{{ $item->stock }}">
                                        {{ $item->name }} - Rp {{ number_format($item->price_sell, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <button type="button" id="addItemBtn" class="btn btn-success w-100">Tambah ke
                                Daftar</button>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                      <h3 class="form-label">Update Status</h3>
                        <div class="col-md-12">
                          <label class="form-label">Status <span class="text-danger">*</span></label>
                          <select name="status" class="form-select" id="editStatus" required>
                            <option value="">-- Update Status Pembayaran--</option>
                            <option value="paid">Selesai</option>
                            <option value="unpaid">Belum Selesai</option>
                          </select>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <h5>Total: <span id="editTotalPrice">Rp 0</span></h5>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            let table = $('#transactionsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: "{{ route('transactions.data') }}",
                columns: [{
                        data: 'invoice',
                        name: 'invoice'
                    },
                    {
                        data: 'client',
                        name: 'client.name'
                    },
                    {
                        data: 'pic',
                        name: 'client.pic'
                    },
                    {
                        data: 'items',
                        name: 'items',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'quantity',
                        name: 'quantity'
                    },
                    {
                        data: 'total_price',
                        name: 'total_price'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // Fungsi update total
            function updateTotal() {
                let totalPrice = 0;
                $('#transactionItemsTable tbody tr').each(function() {
                    let subtotal = parseFloat($(this).data('subtotal')) || 0;
                    totalPrice += subtotal;
                });
                $('#totalPrice').text('Rp ' + totalPrice.toLocaleString('id-ID'));
            }

            // Fungsi update subtotal per row
            function updateSubtotal(row) {
                let price = parseFloat(row.find('.price').data('value')) || 0;
                let qty = parseInt(row.find('.qty').val()) || 0;
                let maxStock = parseInt(row.find('.qty').attr('max')) || 0;

                if (qty > maxStock) {
                    alert('Stok tidak cukup!');
                    qty = maxStock;
                    row.find('.qty').val(qty);
                } else if (qty < 0) {
                    qty = 0;
                    row.find('.qty').val(0);
                }

                let subtotal = price * qty;
                row.find('.subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
                row.data('subtotal', subtotal);
                updateTotal();
            }

            // Realtime hitung subtotal saat qty berubah
            $(document).on('input', '.qty', function() {
                let row = $(this).closest('tr');
                updateSubtotal(row);
            });

            // Search item realtime
            $('#searchItem').on('keyup', function() {
                let query = $(this).val().toLowerCase();
                $('#transactionItemsTable tbody tr').each(function() {
                    let name = $(this).find('td:first').text().toLowerCase();
                    $(this).toggle(name.includes(query));
                });
            });
            

            // Validasi submit (minimal qty > 0)
            $('#itemModal form').on('submit', function() {
                // Hapus item dengan qty = 0
                $('#transactionItemsTable tbody tr').each(function() {
                    let qty = parseInt($(this).find('.qty').val()) || 0;
                    if (qty === 0) {
                        $(this).remove(); // hapus row supaya tidak terkirim
                    }
                });

                // Validasi minimal satu item
                if ($('#transactionItemsTable tbody tr').length === 0) {
                    alert('Pilih minimal satu item!');
                    return false;
                }
            });

            $('#editForm').on('submit', function() {
                // Hapus item dengan qty = 0
                $('#editTransactionItemsTable tbody tr').each(function() {
                    let qty = parseInt($(this).find('.qty').val()) || 0;
                    if (qty === 0) {
                        $(this).remove();
                    }
                });

                // Validasi minimal satu item
                if ($('#editTransactionItemsTable tbody tr').length === 0) {
                    alert('Pilih minimal satu item!');
                    return false;
                }
            });


            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                console.log(id);
                $('#editForm').attr('action', '/transactions/' + id);

                // Reset table sebelum isi ulang
                let tbody = $('#editTransactionItemsTable tbody');
                tbody.empty();

                $.get('/transactions/' + id + '/edit', function(data) {
                    // Set client
                    $('#edit_client_id').val(data.client_id);

                    let total = 0;
                    data.items.forEach((item, index) => {
                        let subtotal = item.price_sell * item.pivot.quantity;
                        total += subtotal;

                        let row = `
        <tr data-item-id="${item.id}" data-subtotal="${subtotal}">
          <td>
            <input type="hidden" name="items[${index}][item_id]" value="${item.id}">
            ${item.name}
          </td>
          <td class="price text-end" data-value="${item.price_sell}">
            Rp ${item.price_sell.toLocaleString('id-ID')}
          </td>
          <td class="text-center">${item.stock}</td>
          <td>
            <input type="number" class="form-control form-control-sm qty text-center"
                   name="items[${index}][quantity]"
                   value="${item.pivot.quantity}" min="0" max="${item.stock}">
          </td>
          <td class="subtotal text-end">
            Rp ${subtotal.toLocaleString('id-ID')}
          </td>
          <td class="text-center">
      <button type="button" class="btn btn-sm btn-danger removeItemBtn"><i class="fas fa-trash fs-6"></i></button>
    </td>
        </tr>
      `;
                        tbody.append(row);
                    });

                    $('#editTotalPrice').text('Rp ' + total.toLocaleString('id-ID'));
                });
            });

            // Realtime hitung subtotal saat qty berubah di modal EDIT
            $(document).on('input', '#editTransactionItemsTable .qty', function() {
                let row = $(this).closest('tr');
                updateSubtotalEdit(row);
            });

            // Fungsi update subtotal di modal EDIT
            function updateSubtotalEdit(row) {
                let price = parseFloat(row.find('.price').data('value')) || 0;
                let qty = parseInt(row.find('.qty').val()) || 0;
                let maxStock = parseInt(row.find('.qty').attr('max')) || 0;

                if (qty > maxStock) {
                    alert('Stok tidak cukup!');
                    qty = maxStock;
                    row.find('.qty').val(qty);
                } else if (qty < 0) {
                    qty = 0;
                    row.find('.qty').val(0);
                }

                let subtotal = price * qty;
                row.find('.subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
                row.data('subtotal', subtotal);

                updateTotalEdit();
            }

            // Hitung total di modal EDIT
            function updateTotalEdit() {
                let totalPrice = 0;
                $('#editTransactionItemsTable tbody tr').each(function() {
                    let subtotal = parseFloat($(this).data('subtotal')) || 0;
                    totalPrice += subtotal;
                });
                $('#editTotalPrice').text('Rp ' + totalPrice.toLocaleString('id-ID'));
            }

            $('#addItemBtn').on('click', function() {
                let select = $('#addItemSelect');
                let itemId = select.val();

                if (!itemId) return;

                let name = select.find(':selected').data('name');
                let price = parseInt(select.find(':selected').data('price'));
                let stock = parseInt(select.find(':selected').data('stock'));

                // Cek apakah item sudah ada di tabel
                let exists = $('#editTransactionItemsTable tbody tr[data-item-id="' + itemId + '"]');
                if (exists.length > 0) {
                    // Kalau ada, tambah qty +1
                    let qtyInput = exists.find('.qty');
                    let newQty = parseInt(qtyInput.val()) + 1;
                    if (newQty <= stock) {
                        qtyInput.val(newQty).trigger('input');
                    } else {
                        alert('Stok tidak cukup!');
                    }
                    return;
                }

                let index = $('#editTransactionItemsTable tbody tr').length;

                let row = `
    <tr data-item-id="${itemId}" data-subtotal="${price}">
      <td>
        <input type="hidden" name="items[${index}][item_id]" value="${itemId}">
        ${name}
      </td>
      <td class="price text-end" data-value="${price}">
        Rp ${price.toLocaleString('id-ID')}
      </td>
      <td class="text-center">${stock}</td>
      <td>
        <input type="number" class="form-control form-control-sm qty text-center"
               name="items[${index}][quantity]" value="1" min="0" max="${stock}">
      </td>
      <td class="subtotal text-end">
        Rp ${price.toLocaleString('id-ID')}
      </td>
      <td class="text-center">
      <button type="button" class="btn btn-sm btn-danger removeItemBtn"><i class="fas fa-trash fs-6"></i></button>
    </td>
    </tr>
  `;

                $('#editTransactionItemsTable tbody').append(row);

                updateTotalEdit();
            });

            $(document).on('click', '.removeItemBtn', function() {
                $(this).closest('tr').remove();
                updateTotalEdit();
            });


            $(document).on('click', '.deleteBtn', function() {
                let url = $(this).data('url');

                Swal.fire({
                    title: 'Yakin hapus?',
                    text: "Data akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    $('#transactionsTable').DataTable().ajax.reload(
                                        null, false);

                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000,
                                        timerProgressBar: true
                                    });

                                    Toast.fire({
                                        icon: 'success',
                                        title: 'Data berhasil dihapus'
                                    });
                                } else {
                                    Swal.fire('Gagal!', 'Data gagal dihapus.', 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error!', 'Tidak bisa terhubung ke server.',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
