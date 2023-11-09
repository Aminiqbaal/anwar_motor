@extends('layouts.master')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@if (Auth::user()->role == 'mechanic')
    @section('title', 'Gaji')
@elseif (Auth::user()->role == 'manager')
    @section('title', 'Ambil Gaji - ' . $mechanic->data->name)
@endif

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-6 form-inline">
                    <label for="datepicker">Filter bulan</label>
                    <input type="text" class="form-control ml-3 w-50" id="datepicker" name="date" value="">
                </div>
                <div class="col-6 text-right align-middle">
                    Belum diambil: <strong>Rp <span id="untaken-salary">{{ number_format($untaken_salary, 0, ',', '.') }}</span></strong>
                    @if (Auth::user()->role == 'manager')
                    <span id="info">(filter bulan untuk mengambil)</span>
                    <span id="salary" class="d-none">
                        <button class="btn btn-info" id="take"><i class="fas fa-donate"></i> Ambil</button>
                        <a class="btn btn-warning" id="slip"><i class="fas fa-file-invoice-dollar"></i> Slip</a>
                    </span>
                    @endif
                    <form method="POST" id="data">
                        @csrf
                        <input type="hidden" name="date">
                        <input type="hidden" name="mechanic_id" value="{{ $mechanic->id }}">
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped list-data">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Transaksi</th>
                        <th>Nominal Gaji</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list-salary">
                    @foreach($transactions as $key => $transaction)
                    <?php $service_total = $transaction->services->sum(function($item){
                        return $item->service->cost;
                    })?>
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ date_format(date_create($transaction->created_at), 'd M Y') }}</td>
                        <td>Rp{{ number_format($service_total, 0, ',', '.') }}</td>
                        <td class="text-center text-center">
                            <button class="btn btn-success btn-sm detail-salary"  data-id="{{ $transaction->id }}"><i class="fa fa-search"></i> Detail</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.card-body -->
    </div>

    @if (Auth::user()->role == 'manager')
    <div class="card">
        <div class="card-header">
            <h4>Bayar Pinjaman</h4>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped list-data">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Nominal Pinjaman</th>
                        <th>Sisa Pinjaman</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="data-list-loan">
                    @foreach($loans as $key => $loan)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ date_format(date_create($loan->created_at), 'd M Y') }}</td>
                        <td>Rp{{ number_format($loan->amount, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($loan->remaining, 0, ',', '.') }}</td>
                        <td class="text-center align-middle">
                            <button class="btn btn-primary btn-sm pay"  data-id="{{ $loan->id }}"><i class="fa fa-dollar-sign"></i> Bayar</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div><!-- /.card-body -->
    </div>
    @endif
@endsection

@push('js')
    <script>
        let _token = $('meta[name="csrf-token"]').attr('content')
        var MyTable = $('.list-data').dataTable()

        $('div.dataTables_filter input').focus()

        function refresh() {
            $.ajax({
				method: 'POST',
				url: '/salary/filter',
				data: $('#data').serialize()
			})
			.done(function(data) {
				$('#data-list-salary').html(data)
			})

            $.ajax({
				method: 'POST',
				url: '/salary/untaken_salary',
				data: $('#data').serialize()
			})
			.done(function(data) {
				$('#untaken-salary').html(data)
			})

            @if (Auth::user()->role == 'manager')
            $.ajax({
				method: 'POST',
				url: '/salary/id',
				data: $('#data').serialize()
			})
			.done(function(data) {
                if(data.id)
				    $('#slip').attr('href', "/salary/"+data.id+"/pdf")
                else
                    $('#slip').attr('href','javascript:')
			})
            @endif
        }

        function is_taken() {
            $.ajax({
				method: 'POST',
				url: '/salary/is_taken',
				data: $('#data').serialize()
			})
			.done(function(data) {
				return data
			})
        }

        $('#datepicker').datepicker({
            minViewMode: 1,
            autoclose: true,
            clearBtn: true,
            format: 'MM yyyy',
        })

        $('#datepicker').datepicker()
            .on('changeDate', function(e) {
                $('input[name=date]').val($(this).val())

                if($(this).val() == '') {
                    $('#salary').addClass('d-none')
                    $('#info').removeClass('d-none')
                } else {
                    $('#salary').removeClass('d-none')
                    $('#info').addClass('d-none')
                }

                refresh()
        })

        $('#take').click(function(){
            if(is_taken()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Filter bulan terlebih dahulu',
                })
            } else {
                $.ajax({
                    method: 'POST',
                    url: '/salary/take/',
                    data: $('#data').serialize()
                })
                .done(function(data) {
                    if(data == 'success'){
                        Swal.fire({
                            icon: 'success',
                        }).then(function (result) {
                            window.location.href = '{{ url("/") }}/salary?id={{ $mechanic->id }}';
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                        })
                    }
                })
            }
        })

        $(document).on('click', '.detail-salary', function(){
            var id = $(this).attr('data-id')

            $.ajax({
                method: 'GET',
                url: '/salary/' + id
            })
            .done(function(data) {
                $('#modals').html(data)
                $('#detail').modal('show')
            })
        })

        $(document).on('click', '.pay', function(){
            var id = $(this).attr('data-id')

            $.ajax({
                method: 'GET',
                url: '/loan/pay/' + id
            })
            .done(function(data) {
                $('#modals').html(data)
                $('#pay').modal('show')
            })
        })
    </script>
@endpush
