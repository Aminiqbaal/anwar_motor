@foreach($spareparts as $key => $sparepart)
<tr>
    <td class="align-middle text-center">
        <button class="btn btn-sm btn-danger hapus-sparepart" data-key="{{ $key }}"><i class="fa fa-times"></i></button>
    </td>
    <td class="align-middle">{{ $sparepart->name }}</td>
    <td class="align-middle text-center">
        <button class="btn btn-sm btn-primary decrement" data-key="{{ $key }}" <?= $sparepart->qty < 2 ? 'disabled' : '' ?>><i class="fas fa-minus"></i></button>
        {{ $sparepart->qty }}
        <button class="btn btn-sm btn-primary increment" data-key="{{ $key }}" <?= $sparepart->qty == $sparepart->current_stock ? 'disabled' : '' ?>><i class="fas fa-plus"></i></button>
        <br>stock:{{ $sparepart->current_stock }}
    </td>
    <td class="align-middle">Rp {{ number_format($sparepart->selling_price, 0, '', '.') }}</td>
    <td class="align-middle">Rp {{ number_format($sparepart->selling_price * $sparepart->qty, 0, '', '.') }}</td>
</tr>
@endforeach
