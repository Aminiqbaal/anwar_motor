@extends('layouts.master')

@section('title', 'Tambah Mekanik')

@section('content')
    <div class="card col-6">
        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                @foreach ($errors->all() as $error)
                {{ $error }}
                @endforeach
            </div>
            @endif
            <form action="/mechanic" method="POST">
                @csrf
                <table class="table table-borderless">
                    <tr>
                        <th><label for="name">Nama</label></th>
                        <td>
                            <input type="text" class="form-control text-capitalize" id="name" name="name" required>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="worked_since">Tahun Masuk</label></th>
                        <td>
                            <input type="text" class="form-control number" id="worked_since" name="worked_since" required>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="age">Umur</label></th>
                        <td>
                            <input type="text" class="form-control number" id="age" name="age" required>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="address">Alamat</label></th>
                        <td>
                            <input type="text" class="form-control text-capitalize" id="address" name="address" required>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button class="btn btn-warning btn-block" type="button" onclick="javascript:location.href='{{ url('mechanic') }}';"><i class="fas fa-arrow-left"></i> Kembali</button>
                        </td>
                        <td>
                            <button class="btn btn-success btn-block" type="submit"><i class="fas fa-save"></i> Simpan</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $('.number').inputmask("numeric", {
            rightAlign: false,
        })
    </script>
@endpush
