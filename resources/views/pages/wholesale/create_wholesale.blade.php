@extends('layouts.master')

@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('title', 'Pembelian Suku Cadang')

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            <div class="row">
                <div class="col-8">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="date">Tanggal Pembelian</label>
                                <input type="date" class="form-control" id="date" value="{{ old('date') }}" required>
                            </div>
                        </div>
                    </div>
                    <hr class="m-0 mb-3">
                    <form id="tambah-item" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="name">Nama Suku Cadang</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="name" name="name" list="spareparts" value="{{ old('name') }}" required>
                                        <datalist id="spareparts">
                                            @foreach ($spareparts as $sparepart)
                                            <option>{{ $sparepart->name }}</option>
                                            @endforeach
                                        </datalist>
                                        <div class="input-group-append">
                                            <a id="search" class="btn btn-info text-white"><i class="fas fa-search"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="category">Kategori</label>
                                    <input type="text" class="form-control" id="category" name="category" list="categories" value="{{ old('category') }}" required>
                                    <datalist id="categories">
                                        @foreach ($categories as $category)
                                        <option>{{ $category->name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="form-group">
                                    <label for="supplier">Supplier</label>
                                    <input type="text" class="form-control" id="supplier" name="supplier" list="suppliers" value="{{ old('supplier') }}" required>
                                    <datalist id="suppliers">
                                        @foreach ($suppliers as $supplier)
                                        <option>{{ $supplier->name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="quantity">Jumlah</label>
                                        <input type="text" class="form-control number" id="quantity" name="quantity" value="{{ old('quantity') }}" required>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="unit">Satuan</label>
                                        <select name="unit" class="form-control" required>
                                            <option value="" hidden>Pilih Satuan</option>
                                            @foreach ($units as $unit)
                                            <option value="{{ $unit }}" {{ old('unit') == $unit ? 'selected':'' }}>{{ $unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="purchase_price">Harga Beli</label>
                                        <input type="text" class="form-control money" id="purchase_price" name="purchase_price" value="{{ old('purchase_price') }}" required>
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="selling_price">Harga Jual</label>
                                        <input type="text" class="form-control money" id="selling_price" name="selling_price" value="{{ old('selling_price') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <div class="form-control text-center h-auto">
                                        <img src="" alt="Foto Sparepart" class="img-thumbnail" id="ct-sparepart" style="max-height: 150px;"/>
                                        <input type="file" class="form-control imgInput mt-1 border-0" data-img="ct-sparepart" name="photo" accept="image/*">
                                    </div>
                                </div>
                                <button class="btn btn-success btn-block mt-3" type="submit"><i class="fas fa-plus"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-4">
                    <label for="list-data">Suku Cadang Rendah</label>
                    <table id="list-data" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Suku Cadang</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reports as $key => $report)
                            <tr>
                                <td class="align-middle">{{ $report->sparepart->name }}</td>
                                <td class="align-middle">{{ $report->sparepart->stock->quantity . ' ' . $report->sparepart->unit }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <button class="btn btn-danger float-right mb-1" id="clear-item"><i class="far fa-trash-alt"></i></button>
                    <table id="list-item" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Suku Cadang</th>
                                <th>Jumlah</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody id="data-list-item"></tbody>
                    </table>
                    <hr class="">
                </div>
            </div>
            <form action="/wholesale" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="supplier" value="{{ old('supplier') }}">
                <input type="hidden" name="date" value="{{ old('date') }}">
                <input type="hidden" name="grand_total">
                <div class="row justify-content-center">
                    <div class="col-4">
                        Total Pembelian
                        <input type="text" class="font-weight-bold form-control-lg form-control-plaintext money" id="grand_total" readonly>
                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <div class="form-control text-center h-auto">
                                <img src="" alt="Foto Nota" class="img-thumbnail" id="ct-receipt" style="max-height: 250px;"/>
                                <input type="file" class="form-control imgInput mt-1 border-0" data-img="ct-receipt" name="receipt" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary btn-block mt-3" type="submit"><i class="fas fa-save"></i> Submit</button>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('#list-data').dataTable({
            "lengthChange": false,
            "searching": false,
        })

        $('.number').inputmask("numeric", {
            rightAlign: false
        })

        $(".money").inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            autoGroup: true,
            prefix: 'Rp ',
            rightAlign:false
        })

        $(document).on("change", ".imgInput", function() {
            var target = '#' + $(this).attr("data-img")
            loadPreview(this, target)
        })

        window.onload = function(){
            // $('#sidebar').removeClass('c-sidebar-lg-show')
            refresh()
        }

        $('#supplier').change(function() {
            $('input[name=supplier]').val($(this).val())
        })
        $('#date').change(function() {
            $('input[name=date]').val($(this).val())
        })

        $('#purchase_price').keyup(function() {
            selling_price = parseInt($(this).val().substr(3).replaceAll('.', '')) * 1.19
            $('#selling_price').val(Math.ceil(selling_price))
        })

        function loadPreview(input, target) {
            if (input.files && input.files[0]) {
                var reader = new FileReader()

                reader.onload = function (e) {
                    $(target).attr('src', e.target.result)
                }

                reader.readAsDataURL(input.files[0])
            }
        }

        function refresh() {
            $.get('/wholesale/get_item', function(data) {
				$('#data-list-item').html(data)
			})
            $.get('/wholesale/grand_total', function(data) {
				$('#grand_total').val(data)
				$('input[name=grand_total]').val(data)
			})
        }

        let _token = $('meta[name="csrf-token"]').attr('content')

        function search() {
            if($('input[name=name]').val() != '') {
                $.ajax({
                    method: 'POST',
                    url: '/sparepart/search',
                    data: {
                        name: $('input[name=name]').val(),
                        _token: _token
                    }
                })
                .done(function(data) {
                    data = jQuery.parseJSON(data)
                    if(data[0] == 'ok') {
                        var sparepart = data[1]
                        $('input[name=id]').val(sparepart.id)
                        $('input[name=category]').val(sparepart.category.name)
                        $('input[name=supplier]').val(sparepart.supplier.name)
                        $('select[name=unit]').val(sparepart.unit)
                        $('input[name=purchase_price]').val(sparepart.purchase_price)
                        $('input[name=selling_price]').val(sparepart.selling_price)
                        $('#ct-sparepart').attr('src', sparepart.photo)
                        $('input[name=photo]').prop('required', false)
                    } else {
                        var name = $('input[name=name]').val()
                        document.getElementById('tambah-item').reset()
                        $('input[name=id]').val(data[2])
                        $('#ct-sparepart').attr('src','')
                        $('input[name=photo]').prop('required', true)
                        $('input[name=name]').val(name)
                    }
                })
            }
        }

        $('#search').click(function() {
            search()
        })

        $('input[name=name]').blur(function(){
            search()
        })

        function image_upload() {
            var data = new FormData()
            var files = $('input[name=photo]')[0].files
            data.append('_token', _token)
            data.append('name', $('input[name=name]').val())
            data.append('id', $('input[name=id]').val())

            if(files.length > 0){
                data.append('photo', files[0])

                $.ajax({
                    method: 'POST',
                    url: '/wholesale/upload',
                    data: data,
                    contentType: false,
                    processData: false,
                    success: function(response){
                        if(response == 0)
                            alert('file not uploaded')
                    }
                })
            } else alert("Please select a file.")
        }

        $('#tambah-item').submit(function(e) {
            if($('input[name=photo]').val() != '') {
                image_upload()
                var split = $('input[name=photo]').val().split('.');
                var ext = '.' + split[split.length - 1];
            } else {
                var split = $('#ct-sparepart').attr('src').split('.');
                var ext = '.' + split[split.length - 1];
            }

            $.ajax({
				method: 'POST',
				url: '/wholesale/add_item',
				data: {
                    id: $('input[name=id]').val(),
                    name: $('input[name=name]').val(),
                    category: $('input[name=category]').val(),
                    supplier: $('input[name=supplier]').val(),
                    quantity: $('input[name=quantity]').val(),
                    unit: $('select[name=unit]').val(),
                    purchase_price: $('input[name=purchase_price]').val(),
                    selling_price: $('input[name=selling_price]').val(),
                    ext: ext,
                    _token: _token
                }
            })
			.done(function(data) {
				document.getElementById('tambah-item').reset()
                $('#ct-sparepart').attr('src','')
                refresh()
			})

			e.preventDefault()
		})

        $(document).on('click', '.hapus-item', function() {
			$.ajax({
				method: "POST",
				url: '/wholesale/delete_item',
                data:{
                    key: $(this).attr('data-key'),
                    _token: _token
                }
			})
			.done(function(data) {
				refresh()
			})
		})

        $('#clear-item').click(function() {
			if(confirm('Kosongkan suku cadang?'))
				$.ajax({
					method: 'POST',
    				url: '/wholesale/clear_items',
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
