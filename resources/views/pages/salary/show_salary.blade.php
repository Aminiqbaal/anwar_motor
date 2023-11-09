<div class="modal fade" id="detail">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <div class="modal-title text-center">Detail Gaji Transaksi</div>
            </div>
            <div class="modal-body">
                <table class="table table-borderless table-hover col-8">
                    <tr>
                        <th>Tanggal Transaksi</th>
                        <td>{{ date_format(date_create($transaction->created_at), 'd F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pelanggan</th>
                        <td>{{ $transaction->customer->name }}</td>
                    </tr>
                </table>

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Jasa</th>
                            <th>Gaji</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $service_total = $transaction->services->sum(function($item){
                            return $item->service->cost;
                        })?>
                        @foreach($transaction->services as $key => $item)
                        <tr>
                            <td class="align-middle">{{ $key + 1 }}</td>
                            <td class="align-middle">{{ $item->service->name }}</td>
                            <td class="align-middle text-right">Rp{{ number_format($item->service->cost, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-right font-weight-bold">Total</td>
                            <td class="text-right">Rp{{ number_format($service_total, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
