@extends('layouts.master')

@section('title', 'Jasa')

@section('content')
    <div class="card">
        @if(Auth::user()->role == 'manager')
        <div class="card-header">
            <a href="/service/create" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        @endif
        <div class="card-body">
            <table id="list-data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Jasa</th>
                        <th>Biaya</th>
                        @if(Auth::user()->role == 'manager')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $key => $service)
                    <tr>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $service->name }}</td>
                        <td class="align-middle">Rp {{ number_format($service->cost, 0, '', '.') }}</td>
                        @if(Auth::user()->role == 'manager')
                        <td class="text-center">
                            <a href="/service/{{ $service->id }}/edit" class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Edit</a>
                            <form action="/service/{{ $service->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method("DELETE")
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="far fa-trash-alt"></i> Delete</button>
                            </form>
                        </td>
                        @endif
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
