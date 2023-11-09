<div class="modal fade" id="detail">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center h-auto">
                    <img src="{{ asset('uploads/receipt/' . $wholesale->photo) }}" alt="Foto Nota" class="img-thumbnail" style="max-height: 250px;"/>
                </div>
                <table class="table table-borderless table-hover col-8 mt-3">
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <td>{{ date_format(date_create($wholesale->purchased_at), 'd M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Total Transaksi</th>
                        <td>Rp {{ number_format($wholesale->grand_total, 0, '', '.') }}</td>
                    </tr>
                </table>

                <hr>

                <h5 class="text-center">Daftar Suku Cadang</h5>
                <table class="table table-bordered table-hover">
                    <thead>
                        <th>#</th>
                        <th>Nama Suku Cadang</th>
                        <th>Kategori</th>
                        <th>Supplier</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th>Foto</th>
                    </thead>
                    @php $subtotal = 0 @endphp
                    @foreach ($wholesale->items as $key => $item)
                    @php $subtotal += $item->selling_price * $item->quantity @endphp
                    <tbody>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $item->name }}</td>
                        <td class="align-middle">{{ $item->category }}</td>
                        <td class="align-middle">{{ $item->supplier }}</td>
                        <td class="align-middle">{{ $item->quantity . ' ' . $item->unit }}</td>
                        <td class="text-right align-middle">Rp {{ number_format($item->purchase_price, 0, '', '.') }}</td>
                        <td class="text-right align-middle">Rp {{ number_format($item->selling_price, 0, '', '.') }}</td>
                        <td class="text-center align-middle">
                            <img src="{{ asset('uploads/product/' . $item->photo) }}" alt="Foto Sparepart" class="img-thumbnail" style="max-height: 150px;max-width: 250px;"/>
                        </td>
                    </tbody>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

