<div class="mb-3">
  <label>Kode Barang</label>
  <input type="text" name="code" class="form-control" required readonly placeholder="Kode Barang Otomatis diisi saat menambahkan">
</div>
<div class="mb-3">
  <label>Supplier</label>
  <select name="supplier_id" class="form-select">
    <option value="">Pilih Supplier</option>
    @foreach($suppliers as $supplier)
      <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
    @endforeach
  </select>
</div>

<div class="mb-3">
  <label>Nama Barang</label>
  <input type="text" name="name" class="form-control" required>
</div>
<div class="mb-3">
  <label>Kategori</label>
  <select name="category" class="form-select" id="">
    <option value="">Pilih Kategori</option>
    <option value="Bahan Pokok">Bahan Pokok</option>
    <option value="Bahan Penunjang">Bahan Penunjang</option>
  </select>
</div>
{{-- <div class="mb-3">
  <label>Satuan</label>
  <input type="text" name="satuan" class="form-control" value="pcs">
</div> --}}
<div class="mb-3">
  <label>Stok</label>
  <input type="number" name="stock" class="form-control" value="0">
</div>
<div class="mb-3">
  <label>Harga Beli</label>
  <input type="number" name="price_buy" class="form-control" required>
</div>
<div class="mb-3">
  <label>Harga Jual</label>
  <input type="number" name="price_sell" class="form-control" required>
</div>
