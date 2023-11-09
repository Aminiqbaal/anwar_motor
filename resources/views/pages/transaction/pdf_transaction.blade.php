<?php
use Codedge\Fpdf\Fpdf\Fpdf;

$pdf = new Fpdf();
$pdf->AddPage();
// Judul
$pdf->SetFont('Arial','',24);
$pdf->Text(10,20, $transaction->workshop->name);

// Info
$pdf->SetFontSize(10);
$pdf->Text(10,30, $transaction->workshop->address);
$pdf->Text(10,35, 'Phone: ' . $transaction->workshop->phone);

$pdf->SetFontSize(11);
// Info Transaksi
$pdf->Text(125,20,'Tanggal');
$pdf->Text(160,20,': ' . date_format(date_create($transaction->created_at), 'd F Y'));
$pdf->Text(125,27,'Nama Pelanggan');
$pdf->Text(160,27,': ' . $transaction->customer->name);
$pdf->Text(125,34,'Nomor Telepon');
$pdf->Text(160,34,': ' . $transaction->customer->phone);

$pdf->SetLineWidth(0.7);
$pdf->Line(10, 44, 200, 44);
$pdf->SetLineWidth(0.2);
$pdf->Line(10, 45, 200, 45);
$pdf->SetY(45);

// Data Sparepart
$spareparts = $transaction->spareparts;
$services = $transaction->services;
if($spareparts->count() > 0) {
	$pdf->SetFont('', 'B');
	$pdf->Cell(20, 10, 'Qty', 0, 0, 'C');
	$pdf->Cell(110, 10, 'Nama Barang', 0, 0, 'C');
	$pdf->Cell(30, 10, 'Harga Satuan', 0, 0, 'C');
	$pdf->Cell(0, 10, 'Jumlah', 0, 1, 'C');

	$pdf->SetFont('', '');
	foreach ($spareparts as $item) {
        $sparepart = $item->sparepart;
		$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
		$pdf->Cell(20, 10, $item->quantity.' '.$sparepart->unit, 0, 0, 'C');
		$pdf->Cell(110, 10, strlen($sparepart->name) > 55 ? substr($sparepart->name, 0, 55) . '...' : $sparepart->name);
		$pdf->Cell(30, 10, 'Rp '.number_format($sparepart->selling_price, 0, '', '.'), 0, 0, 'R');
		$pdf->Cell(0, 10, 'Rp '.number_format($sparepart->selling_price * $item->quantity, 0, '', '.'), 0, 1, 'R');
	}

	// Sparepart Total
    $sparepart_total = $transaction->spareparts->sum(function($item) {
        return $item->sparepart->selling_price;
    });
	$pdf->SetLineWidth(0.4);
	$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
	$pdf->SetFont('', 'B');
	$pdf->Cell(160, 10, '', 0, 0, 'R');
	$pdf->Cell(30, 10, 'Rp '.number_format($sparepart_total, 0, '', '.'), 0, 1, 'R');

	if($services->count() > 0) {
		$pdf->SetY($pdf->GetY() + 10);
		$pdf->SetLineWidth(0.7);
		$pdf->Line(10, $pdf->GetY() - 1, 200, $pdf->GetY() - 1);
		$pdf->SetLineWidth(0.2);
		$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
	}
}

// Data Service
if($services->count() > 0) {
    $service_total = $transaction->services->sum(function($item) {
        return $item->service->cost;
    });
	$pdf->SetFont('', 'B');
	$pdf->Cell(110, 10, 'Nama Jasa', 0, 0, 'C');
	$pdf->Cell(0, 10, 'Biaya', 0, 1, 'R');

	$pdf->SetFont('', '');
	foreach ($services as $item) {
        $service = $item->service;
		$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
		$pdf->Cell(110, 10, $service->name);
		$pdf->Cell(0, 10, 'Rp '.number_format($service->cost, 0, '', '.'), 0, 1, 'R');
	}

	// Service Total
	$pdf->SetLineWidth(0.4);
	$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
	$pdf->Cell(160, 10, 'Montir: '.$transaction->mechanic->data->name, 0, 0, 'R');
	$pdf->SetFont('', 'B');
	$pdf->Cell(30, 10, 'Rp '.number_format($service_total, 0, '', '.'), 0, 1, 'R');
}

$pdf->SetY($pdf->GetY() + 7);
$pdf->Cell(0, 10, 'Total Transaksi: Rp '.number_format($transaction->grand_total, 0, '', '.'), 0, 0, 'R');

$pdf->Output('I', 'invoice_'.$transaction->customer->name.'_'.str_replace('-','',$transaction->created_at).'.pdf');
