@extends('layouts.master')

@section('title', 'List Mekanik')

@section('content')
    <div class="card">
        @if(Auth::user()->role == 'manager')
        <div class="card-header">
            <a href="/mechanic/create" class="btn btn-primary btn-block"><i class="fas fa-plus"></i> Tambah</a>
        </div>
        @endif
        <div class="card-body">
            <table id="list-data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Mekanik</th>
                        <th>Tahun Masuk</th>
                        <th>Umur</th>
                        <th>Alamat</th>
                        @if(Auth::user()->role == 'manager')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($mechanics as $key => $mechanic)
                    <tr>
                        <td class="align-middle">{{ $key + 1 }}</td>
                        <td class="align-middle">{{ $mechanic->data->name }}</td>
                        <td class="align-middle">{{ $mechanic->data->worked_since }}</td>
                        <td class="align-middle">
                            {{ !is_null($mechanic->data->age) ? $mechanic->data->age . ' tahun' : '-' }}
                        </td>
                        <td class="align-middle">
                            {{ $mechanic->data->address ?? '-' }}
                        </td>
                        @if(Auth::user()->role == 'manager')
                        <td class="text-center">
                            <button class="btn btn-success btn-sm detail" data-toggle="modal" data-target="#detail" data-id="{{ $mechanic->id }}"><i class="fa fa-search-dollar"></i> Gaji</button>
                            <a href="/mechanic/{{ $mechanic->id }}/edit" class="btn btn-primary btn-sm"><i class="far fa-edit"></i> Edit</a>
                            <form action="/mechanic/{{ $mechanic->id }}" method="POST" style="display: inline;">
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

@section('modal')
    <div class="modal fade" id="detail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header justify-content-center">
                    <div class="modal-title text-center">Gaji <strong id="mechanic_name"></strong></div>
                </div>
                <div class="modal-body">
                    <div id="chartContainer" style="height: 400px; width: 100%;"></div>
                    <button class="btn btn-primary btn-sm btn-block mt-3" id="pay_salary">Ambil Gaji</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        var MyTable = $('#list-data').dataTable()

        $('div.dataTables_filter input').focus()

        var chart = new CanvasJS.Chart("chartContainer", {
            animationEnabled: true,
            axisY: {
                prefix:'Rp'
            }
        })

        $('.detail').click(function(){
            var id = $(this).attr('data-id')

            $.ajax({
                method: 'GET',
                url: '/mechanic/' + id
            })
            .done(function(data) {
                data = JSON.parse(data)
                chart.options.data = [{
                    type: "column",
                    dataPoints: data[0]
                }]
                $('#mechanic_name').html(data[1].name)
                $('#pay_salary').attr('data-id', data[1].id)
                chart.render()
                $('#detail').modal('show')
            })
        })

        $('#pay_salary').click(function() {
            window.location.href = '{{ url('/salary?id=') }}' + $(this).attr('data-id')
        })
</script>
@endpush
