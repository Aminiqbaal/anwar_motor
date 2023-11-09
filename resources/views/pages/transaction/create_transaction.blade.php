@extends('layouts.master')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('title', 'Tambah Transaksi Penjualan')

@section('content')
    <div class="card">
        <div class="card-header d-flex">
            <form id="tambah-sparepart" method="POST" class="form-inline flex-grow-1">
                <input type="text" class="form-control mr-3 w-50" name="sparepart" placeholder="Nama Suku Cadang" list="spareparts" required>
                <datalist id="spareparts">
                    @foreach ($spareparts as $sparepart)
                    <option value="{{ $sparepart->name }}">{{ $sparepart->name }}</option>
                    @endforeach
                </datalist>
                <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i></button>
            </form>
            <button class="btn btn-danger" id="clear-sparepart"><i class="far fa-trash-alt"></i></button>
        </div>
        <div class="card-body">
            <table id="list-sparepart" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Suku Cadang</th>
                        <th>Jumlah</th>
                        <th>Harga Satuan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody id="data-list-sparepart"></tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right">Total</th>
                        <td>Rp <span id="sparepart-subtotal">0</span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex">
            <form id="tambah-service" method="POST" class="form-inline flex-grow-1">
                <input type="text" class="form-control mr-3 w-50" name="service" placeholder="Nama Jasa" list="services" required>
                <datalist id="services">
                    @foreach ($services as $service)
                    <option value="{{ $service->name }}">{{ $service->name }}</option>
                    @endforeach
                </datalist>
                <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i></button>
            </form>
            <button class="btn btn-danger" id="clear-service"><i class="far fa-trash-alt"></i></button>
        </div>
        <div class="card-body">
            <table id="list-service" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Jasa</th>
                        <th>Biaya</th>
                    </tr>
                </thead>
                <tbody id="data-list-service"></tbody>
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-right">Total</th>
                        <td>Rp <span id="service-subtotal">0</span></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <select id="mechanic_id" class="form-control">
                <option value="" hidden>Pilih Mekanik</option>
                @foreach ($mechanics as $mechanic)
                <option value="{{ $mechanic->id }}" {{ old('mechanic_id') == $mechanic->id ? 'selected':'' }}>{{ $mechanic->data->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="card-footer d-flex">
        <form action="/transaction" method="POST" class="form-inline">
            @csrf
            <input type="hidden" name="grand_total">
            <input type="hidden" name="mechanic_id">
            Total Bayar : <input type="text" class="font-weight-bold form-control-lg form-control-plaintext w-25 money" id="grand_total" readonly>
            <label for="created_at">Tanggal Transaksi</label>&nbsp;<input type="date" class="form-control" name="created_at" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
            <select name="customer_id" class="form-control mx-3" required>
                <option value="" hidden>Nama Pelanggan</option>
                @foreach ($customers as $customer)
                <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected':'' }}>{{ $customer->name . ' (' . $customer->phone . ')' }}</option>
                @endforeach
            </select>
            <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Submit</button>
        </form>
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif
    </div>
@endsection

@push('js')
    <script>
        $(".money").inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            autoGroup: true,
            prefix: 'Rp ',
            rightAlign:false
        })

        window.onload = function(){
            refresh()
        }

        var SparepartTable = $('#list-sparepart').dataTable({
            searching: false,
            paging: false,
            lengthChange: false,
            info: false
        })

        var ServiceTable = $('#list-service').dataTable({
            searching: false,
            paging: false,
            lengthChange: false,
            info: false
        })

        function refresh() {
            $.get('/transaction/get_sparepart', function(data) {
				$('#data-list-sparepart').html(data)
			})
            $.get('/transaction/sparepart_subtotal', function(data) {
				$('#sparepart-subtotal').html(data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))
			})

            $.get('/transaction/get_service', function(data) {
				$('#data-list-service').html(data)
			})
            $.get('/transaction/service_subtotal', function(data) {
				$('#service-subtotal').html(data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."))
                if(data == 0) {
                    $('#mechanic_id').prop('selectedIndex', 0)
                    $('#mechanic_id').prop('disabled', 1)
                } else
                    $('#mechanic_id').prop('disabled', 0)
			})

            $.get('/transaction/grand_total', function(data) {
				$('input[name=grand_total]').val(data)
				$('#grand_total').val($('input[name=grand_total]').val())
			})

            @if(Session::has('toast'))
                Toast.fire({
                    icon: "{{ Session::get('toast')[0] }}",
                    title: "{{ Session::get('toast')[1] }}"
                })
            @endif
        }

        $('#mechanic_id').change(function(){
            $('input[name=mechanic_id]').val($(this).val())
        })

        let _token = $('meta[name="csrf-token"]').attr('content')

		$('#tambah-sparepart').submit(function(e) {
			$.ajax({
				method: 'POST',
				url: '/transaction/add_sparepart',
				data: {
                    sparepart: $('input[name=sparepart]').val(),
                    _token: _token
                }
			})
			.done(function(data) {
				document.getElementById('tambah-sparepart').reset()
                refresh()
                if(data != '') {
                    data = JSON.parse(data)
                    Toast.fire({
                        icon: data[0],
                        title: data[1]
                    })
                }
			})

			e.preventDefault()
		})

        $(document).on('click', '.increment', function() {
			$.ajax({
				method: 'POST',
				url: '/transaction/inc_sparepart',
				data: {
                    key: $(this).attr('data-key'),
                    _token: _token
                }
			})
			.done(function(data) {
				refresh()
			})
		})

        $(document).on('click', '.decrement', function() {
			$.ajax({
				method: 'POST',
				url: '/transaction/dec_sparepart',
				data: {
                    key: $(this).attr('data-key'),
                    _token: _token
                }
			})
			.done(function(data) {
				refresh()
			})
		})

        $(document).on('click', '.hapus-sparepart', function() {
			$.ajax({
				method: "POST",
				url: '/transaction/delete_sparepart',
                data:{
                    key: $(this).attr('data-key'),
                    _token: _token
                }
			})
			.done(function(data) {
				refresh()
			})
		})

        $('#clear-sparepart').click(function() {
			if(confirm('Kosongkan keranjang suku cadang?'))
				$.ajax({
					method: 'POST',
    				url: '/transaction/clear_spareparts',
                    data:{
                        _token: _token
                    }
				})
				.done(function(data) {
					refresh()
				})
		})

        $('#tambah-service').submit(function(e) {
            $.ajax({
                method: 'POST',
                url: '/transaction/add_service',
                data: {
                    service: $('input[name=service]').val(),
                    _token: _token
                }
            })
            .done(function(data) {
                document.getElementById('tambah-service').reset()
                refresh()
                if(data != '') {
                    data = JSON.parse(data)
                    Toast.fire({
                        icon: data[0],
                        title: data[1]
                    })
                }
            })

            e.preventDefault()
        })

        $(document).on('click', '.hapus-service', function() {
            $.ajax({
                method: "POST",
                url: '/transaction/delete_service',
                data:{
                    key: $(this).attr('data-key'),
                    _token: _token
                }
            })
            .done(function(data) {
                refresh()
            })
        })

        $('#clear-service').click(function() {
            if(confirm('Kosongkan keranjang jasa?'))
                $.ajax({
                    method: 'POST',
                    url: '/transaction/clear_services',
                    data:{
                        _token: _token
                    }
                })
                .done(function(data) {
                    refresh()
                })
        })
    </script>
@endpush
