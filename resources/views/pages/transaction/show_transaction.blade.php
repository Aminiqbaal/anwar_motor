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
                <table class="table table-borderless table-hover col-6">
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <td>{{ date_format(date_create($transaction->created_at), 'd M Y') }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <td>{{ $transaction->customer->name }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Telepon Pelanggan</th>
                        <td>{{ $transaction->customer->phone }}</td>
                    </tr>
                    <tr>
                        <th>Total Transaksi</th>
                        <td>Rp {{ number_format($transaction->grand_total, 0, '', '.') }}</td>
                    </tr>
                </table>

                <hr>

                @if ($transaction->spareparts->count() > 0)
                <?php $sparepart_total = $transaction->spareparts->sum(function($item) {
                    return $item->sparepart->selling_price;
                }) ?>
                <h5 class="text-center">Daftar Suku Cadang</h5>
                <table class="table table-bordered table-hover">
                    <thead>
                        <th>Nama Suku Cadang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </thead>
                    @foreach ($transaction->spareparts as $item)
                    <tbody>
                        <td>{{ $item->sparepart->name }}</td>
                        <td>{{ $item->quantity . ' ' . $item->sparepart->unit }}</td>
                        <td class="text-right">Rp {{ number_format($item->sparepart->selling_price, 0, '', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($item->sparepart->selling_price * $item->quantity, 0, '', '.') }}</td>
                    </tbody>
                    @endforeach
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-right">Total</th>
                            <td class="text-right">Rp {{ number_format($sparepart_total, 0, '', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
                @endif

                @if ($transaction->services->count() > 0)
                <?php $service_total = $transaction->services->sum(function($item) {
                    return $item->service->cost;
                }) ?>
                <h5 class="text-center">Daftar Jasa</h5>
                <table class="table table-bordered table-hover">
                    <thead>
                        <th colspan="2">Nama Jasa</th>
                        <th>Biaya</th>
                    </thead>
                    @foreach ($transaction->services as $item)
                    <tbody>
                        <td class="align-middle" colspan="2">{{ $item->service->name }}</td>
                        <td class="align-middle text-right">Rp {{ number_format($item->service->cost, 0, '', '.') }}</td>
                    </tbody>
                    @endforeach
                    <tfoot>
                        <tr>
                            <th>Mekanik: {{ $transaction->mechanic->data->name ?? '-' }}</th>
                            <th class="text-right">Total</th>
                            <td class="text-right">Rp {{ number_format($service_total, 0, '', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
                @endif
            </div>
        </div>
    </div>
</div>

