@extends('layouts.master')

@section('title', 'Edit Data Pinjaman')

@section('content')
    <div class="card col-6">
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif
        <form action="/loan/{{ $loan->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="created_at">Tanggal Pengajuan</label>
                    <input type="date" class="form-control" id="created_at" name="created_at" value="{{ $loan->created_at }}" disabled required>
                </div>
                <div class="form-group">
                    <label for="amount">Nominal</label>
                    <input type="text" class="form-control money" id="amount" name="amount" value="{{ $loan->amount }}" required>
                </div>
                <div class="form-group">
                    <label for="percentage">Persentase Potongan Gaji</label>
                    <select name="percentage" class="form-control" required>
                        <option value="" hidden>Pilih Persentase</option>
                        @foreach ($percentages as $percentage)
                        <option value="{{ $percentage }}" {{ $percentage == $loan->percentage ? "selected" : "" }}>{{ $percentage }}%</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="info">Catatan</label>
                    <input type="text" class="form-control" id="info" name="info" value="{{ $loan->info }}" required>
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
