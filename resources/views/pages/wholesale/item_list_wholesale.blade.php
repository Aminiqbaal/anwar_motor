@foreach($items as $key => $item)
<tr>
    <td class="align-middle text-center">
        <button class="btn btn-sm btn-danger hapus-item" data-key="{{ $key }}"><i class="fa fa-times"></i></button>
    </td>
    <td class="align-middle">{{ $item->name }}</td>
    <td class="align-middle">{{ $item->quantity . ' ' . $item->unit }}</td>
    <td class="align-middle">Rp {{ number_format($item->purchase_price, 0, '', '.') }}</td>
    <td class="align-middle">Rp {{ number_format($item->selling_price, 0, '', '.') }}</td>
    <td class="align-middle text-center">
        <img src="{{ asset('uploads/product/' . $item->photo) }}" alt="Foto Sparepart" class="img-thumbnail" style="max-height: 150px;max-width: 250px;"/>
    </td>
</tr>
@endforeach
