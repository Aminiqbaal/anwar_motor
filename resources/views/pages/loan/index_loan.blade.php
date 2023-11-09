@extends('layouts.master')

@section('title', 'Pinjaman')

@section('content')
    <div class="card">
        @if(Auth::user()->role == 'mechanic')
        <div class="card-header">
            <a href="/loan/create" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        @endif
        <div class="card-body">
            <table id="list-data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Pengajuan</th>
                        @if(Auth::user()->role == 'manager')
                        <th>Nama Mekanik</th>
                        @endif
                        <th>Nominal Pinjaman</th>
                        <th>Sisa Pinjaman</th>
                        @if(Auth::user()->role == 'mechanic')
                        <th>Persentase Pemotongan</th>
                        @endif
                        <th>Catatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loans as $key => $loan)
                    <tr>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ date_format(date_create($loan->created_at), 'd M Y') }}</td>
                        @if(Auth::user()->role == 'manager')
                        <td class="align-middle">{{ $loan->user->data->name }}</td>
                        @endif
                        <td class="align-middle">Rp {{ number_format($loan->amount, 0, '', '.') }}</td>
                        <td class="align-middle">Rp {{ number_format($loan->remaining, 0, '', '.') }}</td>
                        @if(Auth::user()->role == 'mechanic')
                        <td class="align-middle">{{ $loan->percentage }}%</td>
                        @endif
                        <td class="align-middle">{{ $loan->info }}</td>
                        <td class="align-middle text-center">
                            @if($loan->is_paid == '1') Lunas
                            @else
                                @if($loan->is_approved == '-1') Ditolak
                                @elseif($loan->is_approved == '1') Diterima
                                @elseif($loan->is_approved == '0') Belum diproses
                                @endif
                            @endif
                        </td>
                        <td class="text-center">
                            @if(Auth::user()->role == 'mechanic')
                                @if($loan->is_approved == '0')
                                <a href="/loan/{{ $loan->id }}/edit" class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Edit</a>
                                <form action="/loan/{{ $loan->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method("DELETE")
                                    <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="far fa-trash-alt"></i> Delete</button>
                                </form>
                                @endif

                            @elseif(Auth::user()->role == 'manager' && $loan->is_approved == '0')
                                <form action="/loan/{{ $loan->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method("PUT")
                                    <input type="hidden" name="is_approved" value="1" required>
                                    <button class="btn btn-success btn-sm"><i class="fas fa-check"></i> Setuju</button>
                                </form>
                                <form action="/loan/{{ $loan->id }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method("PUT")
                                    <input type="hidden" name="is_approved" value="-1" required>
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-times"></i> Tolak</button>
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
    </script>
@endpush
