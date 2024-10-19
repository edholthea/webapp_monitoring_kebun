<?php
session_start();
require 'inc/config.php';
require 'vendor/autoload.php';  // Pastikan Anda sudah mendownload PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Inisialisasi Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set header kolom
$sheet->setCellValue('A1', 'Tanggal');
$sheet->setCellValue('B1', 'Nama Petugas');
$sheet->setCellValue('C1', 'Kegiatan');
$sheet->setCellValue('D1', 'Keterangan');

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM kegiatan WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$kegiatan = $stmt->fetchAll();

// Masukkan data ke dalam file Excel
$row = 2;
foreach ($kegiatan as $item) {
    $sheet->setCellValue('A' . $row, $item['tanggal']);
    $sheet->setCellValue('B' . $row, $item['nama_petugas']);
    $sheet->setCellValue('C' . $row, $item['kegiatan']);
    $sheet->setCellValue('D' . $row, $item['keterangan']);
    $row++;
}

// Set nama file
$filename = 'data_kegiatan.xlsx';

// Download file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
