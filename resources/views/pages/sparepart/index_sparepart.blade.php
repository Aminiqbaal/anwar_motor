@extends('layouts.master')

@section('title', 'List Suku Cadang')

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="list-data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Suku Cadang</th>
                        <th>Harga Jual</th>
                        @if(Auth::user()->role == 'cashier')
                        <th>Jumlah Barang</th>
                        @endif
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($spareparts as $key => $sparepart)
                    <tr>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $sparepart->name }}</td>
                        <td class="align-middle">Rp {{ number_format($sparepart->selling_price, 0, '', '.') }}</td>
                        @if(Auth::user()->role == 'cashier')
                        <td class="align-middle">{{ $sparepart->stock . ' ' . $sparepart->unit }}</td>
                        @endif
                        <td class="text-center">
                            <button class="btn btn-success btn-sm detail"  data-id="{{ $sparepart->id }}"><i class="fa fa-search"></i> Detail</button>
                            @if(Auth::user()->role == 'manager')
                                <a href="/sparepart/{{ $sparepart->id }}/edit" class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Edit</a>
                                <form action="/sparepart/{{ $sparepart->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="far fa-trash-alt"></i> Delete</button>
                                </form>
                            @elseif(Auth::user()->role == 'cashier' && $sparepart->stock <= 10)
                                <form action="/report" method="POST" style="display: inline;">
                                    @csrf
                                    <input type="hidden" name="sparepart_id" value="{{ $sparepart->id }}">
                                    <button class="btn btn-primary btn-sm"><i class="fas fa-bullhorn"></i> Lapor Stok Rendah</button>
                                </form>
                            @endif
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
                url: '/sparepart/' + id
            })
            .done(function(data) {
                $('#modals').html(data)
                $('#detail').modal('show')
            })
        })
    </script>
@endpush
