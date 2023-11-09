@foreach($services as $key => $service)
<tr>
    <td class="align-middle text-center">
        <button class="btn btn-sm btn-danger hapus-service" data-key="{{ $key }}"><i class="fa fa-times"></i></button>
    </td>
    <td class="align-middle">{{ $service->name }}</td>
    <td class="align-middle">Rp {{ number_format($service->cost, 0, '', '.') }}</td>
</tr>
@endforeach
