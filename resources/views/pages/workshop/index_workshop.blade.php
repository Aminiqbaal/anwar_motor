@extends('layouts.master')

@section('title', 'List Bengkel')

@section('content')
    <div class="card">
        <div class="card-body">
            <table id="list-data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Bengkel</th>
                        <th>Alamat</th>
                        <th>Nomor Telepon</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($workshops as $key => $workshop)
                    <tr>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $workshop->name }}</td>
                        <td class="align-middle">{{ $workshop->address }}</td>
                        <td class="align-middle">{{ $workshop->phone }}</td>
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
