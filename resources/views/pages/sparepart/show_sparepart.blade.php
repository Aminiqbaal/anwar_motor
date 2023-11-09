<div class="modal fade" id="detail">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <div class="modal-title text-center"><strong>{{ $sparepart->name }}</strong></div>
            </div>
            <div class="modal-body">
                <div class="text-center h-auto">
                    <img src="{{ asset('uploads/product/' . $sparepart->photo) }}" alt="Foto Sparepart" class="img-thumbnail" style="max-height: 150px;"/>
                </div>
                <table class="table table-borderless table-hover mt-3">
                    <tr>
                        <th>Kategori</th>
                        <td>{{ $sparepart->category->name }}</td>
                    </tr>
                    <tr>
                        <th>Supplier</th>
                        <td>{{ $sparepart->supplier->name }}</td>
                    </tr>
                    <tr>
                        <th>Harga Beli</th>
                        <td>Rp {{ number_format($sparepart->purchase_price, 0, '', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Harga Jual</th>
                        <td>Rp {{ number_format($sparepart->selling_price, 0, '', '.') }}</td>
                    </tr>
                </table>

                <hr>

                <h5 class="text-center">Stok Barang</h5>
                <table class="table table-bordered table-hover">
                    <thead>
                        <th>Nama Cabang</th>
                        <th>Jumlah Barang</th>
                    </thead>
                    @foreach ($sparepart->stocks->groupBy('workshop_id') as $stock)
                    <tr>
                        <td>{{ $stock[0]->workshop->name }}</td>
                        <td>{{ $stock->sum('quantity') }} {{ $sparepart->unit }}</td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

