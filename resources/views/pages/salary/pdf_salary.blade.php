<?php
use Codedge\Fpdf\Fpdf\Fpdf;

$pdf = new Fpdf();
$pdf->AddPage(); //A4 - width:210
// Judul
$pdf->SetFont('Arial','',24);
$pdf->Text(10,20, $salary->user->workshop->name);

// Info
$pdf->SetFontSize(10);
$pdf->Text(10,30, $salary->user->workshop->address);
$pdf->Text(10,35, 'Phone: ' . $salary->user->workshop->phone);

$pdf->SetFontSize(16);
$pdf->SetFont('', 'BU');
$pdf->Text(125,27,'SLIP GAJI KARYAWAN');

$pdf->SetFontSize(11);
$pdf->SetFont('','');
$pdf->SetY(50);

$pdf->text(10, 45, 'Tanggal Pengambilan');
$pdf->text(50, 45, ': '.date_format(date_create($salary->taken_at), 'd F Y'));
$pdf->text(10, 51, 'Nama Mekanik');
$pdf->text(50, 51, ': '.$salary->user->data->name);
$pdf->text(10, 57, 'Alamat');
$pdf->text(50, 57, ': '.$salary->user->data->address);

// Data Gaji
$pdf->setY(60);
$pdf->SetFont('', 'B');
$pdf->Cell(95, 6, 'PENERIMAAN', 1, 0, 'C');

$pdf->SetFont('', '');
foreach($transactions as $transaction) {
    $pdf->SetY($pdf->getY() + 6);
    $service_total = $transaction->services->sum(function($item){
        return $item->service->cost;
    });

    $content = 'Transaksi '. date_format(date_create($salary->taken_at), 'd F Y') .': Rp'. number_format($service_total, 0, '', '.');
    $pdf->Cell(95, 6, $content, 1);
}
$pdf->SetY($pdf->getY() + 6);
$y_transaction = $pdf->GetY();
$pdf->Cell(95, 6, 'TOTAL PENERIMAAN: Rp'. number_format($salary->salary, 0, '', '.'), 1);

// Data Potongan
$pdf->setY(60);
$pdf->SetFont('', 'B');
$pdf->Cell(95, 6);
$pdf->Cell(95, 6, 'PEMOTONGAN', 1, 0, 'C');

$pdf->SetFont('', '');
foreach($loans as $loan) {
    $pdf->SetY($pdf->getY() + 6);

    $content = $loan->info .': Rp'. number_format($loan->amount, 0, '', '.');
    $pdf->Cell(95, 6);
    $pdf->Cell(95, 6, $content, 1);
}
$pdf->SetY($pdf->getY() + 6);
$y_loan = $pdf->GetY();
$pdf->Cell(95, 6);
$pdf->Cell(95, 6, 'TOTAL POTONGAN: Rp'. number_format($salary->cuts, 0, '', '.'), 1);

$pdf->SetY(max($y_loan, $y_transaction)+15);
$pdf->Text(10, $pdf->GetY(), 'GAJI BERSIH: Rp'. number_format($salary->salary -$salary->cuts, 0, '', '.'));

$pdf->SetY($pdf->GetY()+20);
$pdf->Text(30, $pdf->GetY(), 'Mengetahui,');
$pdf->Text(150, $pdf->GetY(), 'Penerima,');
$pdf->SetY($pdf->GetY()+20);
$pdf->Text(30, $pdf->GetY(), 'Manager '.substr($salary->user->workshop->name,-1));
$pdf->Text(150, $pdf->GetY(), $salary->user->data->name);

$pdf->Output('I', 'nota_gaji_'.$salary->user->data->name.'_'.date_format(date_create($salary->date), 'mY').'.pdf');
