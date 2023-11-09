@extends('layouts.master')

@section('title', 'Edit Suku Cadang')

@section('content')
    <div class="card col-xl-8 col-lg-12">
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif
        <form action="/sparepart/{{ $sparepart->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">Nama Suku Cadang</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ $sparepart->name }}" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Kategori</label>
                        <input type="text" class="form-control" id="category" name="category" value="{{ $sparepart->category->name }}" list="categories" required>
                        <datalist id="categories">
                            @foreach ($categories as $category)
                            <option>{{ $category->name }}</option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="quantity">Jumlah</label>
                            <input type="text" class="form-control number" id="quantity" name="quantity" value="{{ $sparepart->stock->quantity }}" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="unit">Unit</label>
                            <select name="unit" class="form-control" required>
                                <option value="" hidden>Pilih Satuan</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit }}" {{ $unit == $sparepart->unit ? 'selected':'' }}>{{ $unit }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="purchase_price">Harga Beli</label>
                            <input type="text" class="form-control money" id="purchase_price" name="purchase_price" value="{{ $sparepart->purchase_price }}" required>
                        </div>
                        <div class="form-group col-6">
                            <label for="selling_price">Harga Jual</label>
                            <input type="text" class="form-control money" id="selling_price" name="selling_price" value="{{ $sparepart->selling_price }}" required>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="input-group">
                        <div class="form-control text-center h-auto">
                            <img src="{{ asset('uploads/product/' . $sparepart->photo) }}" alt="Foto Sparepart" class="img-thumbnail" id="ct-sparepart" style="max-height: 150px;"/>
                            <input type="file" class="form-control imgInput mt-1 border-0" data-img="ct-sparepart" name="photo">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success" type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection

@push('js')
    <script>
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

        $('#purchase_price').keyup(function() {
            selling_price = parseInt($(this).val().substr(3).replaceAll('.', '')) * 1.19
            $('#selling_price').val(Math.ceil(selling_price))
        })

        $(document).on("change", ".imgInput", function() {
            var target = '#' + $(this).attr("data-img")
            loadPreview(this, target)
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
    </script>
@endpush
