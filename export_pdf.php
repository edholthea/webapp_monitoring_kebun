<?php
session_start();
require 'inc/config.php';
require 'inc/fpdf.php';

class PDF extends FPDF
{
    // Membuat Header
    function Header()
    {
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Data Kegiatan', 0, 1, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(30, 10, 'Tanggal', 1);
        $this->Cell(50, 10, 'Nama Petugas', 1);
        $this->Cell(50, 10, 'Kegiatan', 1);
        $this->Cell(60, 10, 'Keterangan', 1);
        $this->Ln();
    }

    // Membuat Footer
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Inisialisasi PDF
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM kegiatan WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$kegiatan = $stmt->fetchAll();

foreach ($kegiatan as $item) {
    $pdf->Cell(30, 10, $item['tanggal'], 1);
    $pdf->Cell(50, 10, $item['nama_petugas'], 1);
    $pdf->Cell(50, 10, $item['kegiatan'], 1);
    $pdf->Cell(60, 10, $item['keterangan'], 1);
    $pdf->Ln();
}

$pdf->Output();
