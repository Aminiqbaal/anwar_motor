@extends('layouts.master')

@section('title', 'Pelanggan')

@section('content')
    <div class="card">
        <div class="card-header">
            <a href="/customer/create" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        <div class="card-body">
            <table id="list-data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Pelanggan</th>
                        <th>Nomor Telepon</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $key => $customer)
                    <tr>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $customer->name }}</td>
                        <td class="align-middle">{{ $customer->phone }}</td>
                        <td class="text-center">
                            <a href="/customer/{{ $customer->id }}/edit" class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Edit</a>
                            <form action="/customer/{{ $customer->id }}" method="POST" style="display: inline;">
                                @csrf
                                @method("DELETE")
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"><i class="far fa-trash-alt"></i> Delete</button>
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
    </script>
@endpush
