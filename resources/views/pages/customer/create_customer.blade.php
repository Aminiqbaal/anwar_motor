@extends('layouts.master')

@section('title', 'Tambah Data Pelanggan')

@section('content')
    <div class="card col-6">
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif
        <form action="/customer" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Pelanggan</label>
                    <input type="text" class="form-control text-capitalize" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="text" class="form-control number text-left" id="phone" name="phone" required>
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
        $('.number').on('keypress', function(evt) {
			var charCode = (evt.which) ? evt.which : evt.keyCode
			if (charCode > 31 && (charCode < 48 || charCode > 57))
				return false;
			return true;
		})
    </script>
@endpush
