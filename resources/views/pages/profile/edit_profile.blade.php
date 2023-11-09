@extends('layouts.master')

@section('title', 'Ubah Profil Pengguna')

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
            <form action="/user/{{ $user->id }}" method="POST">
                @csrf
                @method('PUT')
                <table class="table table-borderless">
                    <tr>
                        <th class="align-middle">Username</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th class="align-middle">Bengkel</th>
                        <td>{{ $user->workshop->name }}</td>
                    </tr>
                    <tr>
                        <th class="align-middle"><label for="name">Nama</label></th>
                        <td>
                            <input type="text" class="form-control" name="name" value="{{ $user->data->name }}" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle"><label for="worked_since">Tahun Masuk</label></th>
                        <td>
                            <input type="text" class="form-control" name="worked_since" value="{{ $user->data->worked_since }}" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle"><label for="age">Umur</label></th>
                        <td>
                            <input type="text" class="form-control" name="age" value="{{ $user->data->age }}" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle"><label for="address">Alamat</label></th>
                        <td>
                            <input type="text" class="form-control" name="address" value="{{ $user->data->address }}" required>
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle"><label for="new_password">Password Baru</label></th>
                        <td>
                            <small class="text-muted">Isi hanya jika ingin mengubah password</small>
                            <input type="password" class="form-control" id="new_password" name="new_password">
                        </td>
                    </tr>
                    <tr>
                        <th class="align-middle"><label for="old_password">Password Lama</label></th>
                        <td>
                            <small class="text-muted"></small>
                            <input type="password" class="form-control" id="old_password" name="old_password" disabled>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button class="btn btn-warning btn-block" type="button" onclick="javascript:location.href='{{ url('user/' . Auth::id()) }}';"><i class="fas fa-arrow-left"></i> Kembali</button>
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
        $(".money").inputmask("numeric", {
            radixPoint: ",",
            groupSeparator: ".",
            autoGroup: true,
            prefix: 'Rp ',
            rightAlign:false
        })

        $('#new_password').keyup(function() {
            if($(this).val() == '') {
                $(this).prop('required', 0)
                $('#old_password').prop('disabled', 1)
                $('#old_password').prop('required', 0)
            } else {
                $(this).prop('required', 1)
                $('#old_password').prop('disabled', 0)
                $('#old_password').prop('required', 1)
            }
        })
    </script>
@endpush
