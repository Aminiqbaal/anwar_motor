@foreach($loans as $key => $loan)
<tr>
    <td>{{ $key + 1 }}</td>
    <td>{{ date_format(date_create($loan->created_at), 'd M Y') }}</td>
    <td>Rp{{ number_format($loan->amount, 0, ',', '.') }}</td>
    <td>Rp{{ number_format($loan->remaining, 0, ',', '.') }}</td>
    <td class="text-center align-middle">
        <button class="btn btn-primary btn-sm pay"  data-id="{{ $loan->id }}"><i class="fa fa-dollar-sign"></i> Bayar</button>
    </td>
</tr>
@endforeach
