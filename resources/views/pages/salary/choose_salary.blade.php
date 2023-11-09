@extends('layouts.master')

@section('title', 'Ambil Gaji')

@section('content')
    <div class="card col-6">
        <form action="/salary" method="GET">
            <div class="card-body">
                <div class="form-group">
                    <label for="mechanic">Nama Mekanik</label>
                    <select name="id" id="mechanic" class="form-control">
                        <option value="" hidden>Pilih Mekanik</option>
                        @foreach ($mechanics as $mechanic)
                        <option value="{{ $mechanic->id }}">{{ $mechanic->data->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success" type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection
