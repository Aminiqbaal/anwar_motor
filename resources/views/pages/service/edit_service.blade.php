@extends('layouts.master')

@section('title', 'Edit Data Jasa')

@section('content')
    <div class="card col-6">
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif
        <form action="/service/{{ $service->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Nama Jasa</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $service->name }}" required>
                </div>
                <div class="form-group">
                    <label for="cost">Biaya</label>
                    <input type="text" class="form-control money" id="cost" name="cost" value="{{ $service->cost }}" required>
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
        $(".money").inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            autoGroup: true,
            prefix: 'Rp ',
            rightAlign:false
        })
    </script>
@endpush
