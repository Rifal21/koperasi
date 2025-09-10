@extends('admin.layouts.main')

@section('contentDashboard')
<div class="container-fluid py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Daftar Supplier</h4>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
      + Tambah Supplier
    </button>
  </div>

  <div class="card">
    <div class="card-body">
      <table id="suppliersTable" class="table table-bordered table-striped w-100">
        <thead>
          <tr>
            <th>Nama Supplier</th>
            <th>PIC / Penanggung jawab</th>
            <th>Alamat</th>
            <th>NO. HP</th>
            <th>Email</th>
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
    <form class="modal-content" action="{{ route('suppliers.store') }}" method="POST">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tambah Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @include('admin.suppliers.form')
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
        <h5 class="modal-title">Edit Supplier</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @include('admin.suppliers.form')
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
  let table = $('#suppliersTable').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    autoWidth: false,
    ajax: "{{ route('suppliers.data') }}",
    columns: [
      { data: 'name', name: 'name' },
      { data: 'pic', name: 'pic' },
      { data: 'address', name: 'address' },
      { data: 'phone', name: 'phone' },
      { data: 'email', name: 'email' },
      { data: 'action', name: 'action', orderable: false, searchable: false },
    ]
  });

  // isi data ke modal edit
  $(document).on('click', '.editBtn', function() {
    let id = $(this).data('id');
    $('#editForm').attr('action', '/suppliers/' + id);

    $('#editForm [name="name"]').val($(this).data('name'));
    $('#editForm [name="pic"]').val($(this).data('pic'));
    $('#editForm [name="address"]').val($(this).data('address'));
    $('#editForm [name="phone"]').val($(this).data('phone'));
    $('#editForm [name="email"]').val($(this).data('email'));
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
            $('#suppliersTable').DataTable().ajax.reload(null, false);

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
