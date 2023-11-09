<?php $installment_amount = ($loan->amount * $loan->percentage / 100); ?>
<div class="modal fade" id="pay">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <div class="modal-title text-center">Bayar Pinjaman</div>
            </div>
            <div class="modal-body">
                <table class="table table-borderless table-hover col-10">
                    <tr>
                        <th>Sisa Pinjaman</th>
                        <td>Rp{{ number_format($loan->remaining, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Persentase Potongan</th>
                        <td>{{ $loan->percentage }}%</td>
                    </tr>
                    <tr>
                        <th>Nominal cicilan</th>
                        <td>Rp{{ number_format($installment_amount, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $loan->info }}</td>
                    </tr>
                </table>
                @if(($salary->salary - $salary->cuts) > $installment_amount)
                <form action="/salary/cut" method="POST">
                    @csrf
                    <input type="hidden" name="loan_id" value="{{ $loan->id }}">
                    <input type="hidden" name="salary_id" value="{{ $salary->id }}">
                    <div class="form-group">
                        <label for="installment">Angsur sebanyak</label>
                        <select name="installment" id="installment" class="form-control">
                            <option value="" hidden>Pilih Nominal Angsur</option>
                            @for ($i = 1; $i <= $loan->installment; $i++)
                            <option value="{{ $i }}">
                                {{ $installment_amount * $i != $loan->remaining ? $i.'x' : 'Langsung Lunasi' }} (Rp{{ number_format($installment_amount * $i, 0, ',', '.') }})
                            </option>
                            @endfor
                            @if ($installment_amount * ($i - 1) != $loan->remaining && ($salary->salary - $salary->cuts) > $loan->remaining)
                            <option value="full">Langsung lunasi (Rp{{ number_format($loan->remaining, 0, ',', '.') }})</option>
                            @endif
                        </select>
                    </div>
                    <button class="btn btn-success" type="submit">Submit</button>
                </form>
                @else
                <div class="alert alert-warning text-center">Sisa gaji tidak cukup untuk mengangsur</div>
                @endif
            </div>
        </div>
    </div>
</div>
