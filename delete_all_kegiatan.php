<?php
session_start();
require 'inc/config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu!";
    header('Location: login.php');
    exit();
}

// Menghapus semua kegiatan berdasarkan user yang login
$stmt = $conn->prepare("DELETE FROM kegiatan WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $_SESSION['user_id']);

if ($stmt->execute()) {
    $_SESSION['success'] = "Semua kegiatan berhasil dihapus!";
} else {
    $_SESSION['error'] = "Gagal menghapus semua kegiatan!";
}

// Redirect kembali ke halaman dashboard
header('Location: dashboard.php');
exit();
?>
