@extends('layouts.master')

@section('title', 'Laporan Stok Rendah')

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="list-data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Pelaporan</th>
                        <th>Nama Suku Cadang</th>
                        <th>Supplier</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $key => $report)
                    <tr>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ date_format(date_create($report->reported_at), 'd M Y') }}</td>
                        <td class="align-middle">{{ $report->sparepart->name }}</td>
                        <td class="align-middle">{{ $report->sparepart->supplier->name }}</td>
                        <td class="align-middle">{{ $report->sparepart->stock->quantity . ' ' . $report->sparepart->unit }}</td>
                        <td class="text-center">
                            @if($report->is_done == '0')
                                @if (Auth::user()->role == 'cashier')
                                <form action="/report/{{ $report->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="far fa-trash-alt"></i> Delete</button>
                                </form>
                                @elseif (Auth::user()->role == 'manager')
                                <form action="/report/{{ $report->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method("PUT")
                                    <button class="btn btn-primary btn-sm" onclick="return confirm('Tandai telah diproses?')"><i class="fas fa-check"></i> Proses</button>
                                </form>
                                @endif
                            @else <i class="fas fa-check text-success"></i>
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
    </script>
@endpush
