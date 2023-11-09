@foreach($transactions as $key => $transaction)
<?php $service_total = $transaction->services->sum(function($item){
    return $item->service->cost;
})?>
<tr>
    <td class="align-middle">{{ $key + 1 }}</td>
    <td class="align-middle">{{ date_format(date_create($transaction->created_at), 'd M Y') }}</td>
    <td class="align-middle">Rp{{ number_format($service_total, 0, ',', '.') }}</td>
    <td class="align-middle text-center">
        <button class="btn btn-success btn-sm detail"  data-id="{{ $transaction->id }}"><i class="fa fa-search"></i> Detail</button>
    </td>
</tr>
@endforeach
