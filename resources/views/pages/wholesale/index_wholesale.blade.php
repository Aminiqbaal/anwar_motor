@extends('layouts.master')

@section('title', 'Riwayat Pembelian Suku Cadang')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="/wholesale/create" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <div class="card-body">
            <table id="list-data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Transaksi</th>
                        <th>Total Transaksi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wholesales as $key => $wholesale)
                    <tr>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ date_format(date_create($wholesale->purchased_at), 'd M Y') }}</td>
                        <td class="align-middle">Rp {{ number_format($wholesale->grand_total, 0, '', '.') }}</td>
                        <td class="text-center">
                            <button class="btn btn-success btn-sm detail"  data-id="{{ $wholesale->id }}"><i class="fa fa-search"></i> Detail</button>
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
                url: '/wholesale/' + id
            })
            .done(function(data) {
                $('#modals').html(data)
                $('#detail').modal('show')
            })
        })
    </script>
@endpush
