<div class="card p-3">
  <h5 class="mb-3">Tambah Transaksi</h5>

  {{-- Pilih Client --}}
  <div class="row g-3 mb-3">
    <div class="col-md-12">
      <label class="form-label">Pilih Client <span class="text-danger">*</span></label>
      <select name="client_id" class="form-select" required>
        <option value="">-- Pilih Client --</option>
        @foreach($clients as $client)
          <option value="{{ $client->id }}">{{ $client->name }} ({{ $client->pic }})</option>
        @endforeach
      </select>
    </div>
  </div>

  {{-- Daftar Item --}}
  <h6 class="mb-2">Daftar Item Transaksi</h6>
  <table class="table table-bordered table-sm" id="transactionItemsTable">
    <thead class="table-light">
      <tr>
        <th>Item</th>
        <th>Harga</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>

  <div class="d-flex justify-content-between align-items-center mt-3">
    <strong>Total: <span id="totalPrice">0</span></strong>
    <button type="button" id="addItemBtn" class="btn btn-success btn-sm">Tambah Item</button>
  </div>

  <div class="mt-3 text-end">
    <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
  </div>
</div>
