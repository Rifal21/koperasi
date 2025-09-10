@extends('admin.layouts.main')

@section('contentDashboard')
    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Daftar Barang</h4>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                + Tambah Barang
            </button>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <div class="mb-3">
                    <label for="filterSupplier" class="form-label">Filter Supplier</label>
                    <select id="filterSupplier" class="form-select">
                        <option value="">-- Semua Supplier --</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <table id="itemsTable" class="table table-bordered table-striped w-100">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Supplier</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Create --}}
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" action="{{ route('items.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.partials.form')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form class="modal-content" id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @include('admin.partials.form')
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
            let table = $('#itemsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,
                ajax: {
                    url: "{{ route('items.data') }}",
                    data: function(d) {
                        d.supplier_id = $('#filterSupplier').val(); // kirim ke backend
                    }
                },
                columns: [{
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'supplier',
                        name: 'supplier'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'category',
                        name: 'category'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'price_buy',
                        name: 'price_buy'
                    },
                    {
                        data: 'price_sell',
                        name: 'price_sell'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ]
            });

            $('#filterSupplier').change(function() {
                table.ajax.reload();
            });

            // isi data ke modal edit
            $(document).on('click', '.editBtn', function() {
                let id = $(this).data('id');
                $('#editForm').attr('action', '/items/' + id);

                $('#editForm [name="code"]').val($(this).data('code'));
                $('#editForm [name="supplier_id"]').val($(this).data('supplier_id'));
                $('#editForm [name="name"]').val($(this).data('name'));
                $('#editForm [name="category"]').val($(this).data('category'));
                $('#editForm [name="stock"]').val($(this).data('stock'));
                $('#editForm [name="price_buy"]').val($(this).data('price_buy'));
                $('#editForm [name="price_sell"]').val($(this).data('price_sell'));
            });
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
                                $('#itemsTable').DataTable().ajax.reload(null, false);
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
                            Swal.fire('Error!', 'Tidak bisa terhubung ke server.', 'error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
