@extends('layouts.master')

@section('title', 'Tambah Data Pinjaman')

@section('content')
    <div class="card col-6">
        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            @foreach ($errors->all() as $error)
            {{ $error }}
            @endforeach
        </div>
        @endif
        <form action="/loan" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="created_at">Tanggal Pengajuan</label>
                    <input type="date" class="form-control" name="created_at" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                </div>
                <div class="form-group">
                    <label for="amount">Nominal</label>
                    <input type="text" class="form-control money" name="amount" required>
                </div>
                <div class="form-group">
                    <label for="percentage">Persentase Potongan Gaji</label>
                    <select name="percentage" class="form-control" required>
                        <option value="" hidden>Pilih Persentase</option>
                        @foreach ($percentages as $percentage)
                        <option value="{{ $percentage }}">{{ $percentage }}%</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="info">Catatan</label>
                    <input type="text" class="form-control" name="info" required>
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
