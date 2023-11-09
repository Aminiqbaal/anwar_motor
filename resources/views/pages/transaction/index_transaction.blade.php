@extends('layouts.master')

@section('title', 'Transaksi Penjualan')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="/transaction/create" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <div class="card-body">
            <table id="list-data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Transaksi</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Transaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $key => $transaction)
                    <tr>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ date_format(date_create($transaction->created_at), 'd M Y') }}</td>
                        <td class="align-middle">{{ $transaction->customer->name }}</td>
                        <td class="align-middle">Rp {{ number_format($transaction->grand_total, 0, '', '.') }}</td>
                        <td class="text-center">
                            <a href="/transaction/{{ $transaction->id }}/pdf" class="btn btn-warning btn-sm"><i class="fas fa-receipt"></i> Invoice</a>
                            <button class="btn btn-success btn-sm detail"  data-id="{{ $transaction->id }}"><i class="fas fa-search"></i> Detail</button>
                            <a href="/transaction/{{ $transaction->id }}/edit" class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Edit</a>
                            <form action="/transaction/{{ $transaction->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method("DELETE")
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?\n Jumlah barang akan dikembalikan ke stok')"><i class="far fa-trash-alt"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.card-body -->
    </div>
@endsection

@push('js')
    <script>
        var MyTable = $('#list-data').dataTable()

        $('div.dataTables_filter input').focus()

        $(document).on('click', '.detail', function(){
            var id = $(this).attr('data-id')

            $.ajax({
                method: 'GET',
                url: '/transaction/' + id
            })
            .done(function(data) {
                $('#modals').html(data)
                $('#detail').modal('show')
            })
        })
    </script>
@endpush
