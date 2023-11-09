@extends('layouts.master')

@section('title', 'Profil Pengguna')

@section('content')
    <div class="card col-6">
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th>Username</th>
                    <td>{{ $user->username }}</td>
                </tr>
                <tr>
                    <th>Bengkel</th>
                    <td>{{ $user->workshop->name }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $user->data->name }}</td>
                </tr>
                @if(Auth::user()->role == 'mechanic')
                <tr>
                    <th>Tahun Masuk</th>
                    <td>{{ $user->data->worked_since }}</td>
                </tr>
                <tr>
                    <th>Umur</th>
                    <td>{{ !is_null($user->data->age) ? $user->data->age . ' tahun' : '-' }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $user->data->address ?? '-' }}</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <a href="/user/{{ Auth::id() }}/edit" class="btn btn-primary btn-block"><i class="fas fa-edit"></i> Ubah Profil</a>
                    </td>
                </tr>
                @endif
            </table>
        </div>
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
