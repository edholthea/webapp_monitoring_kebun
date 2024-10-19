<?php
session_start();
require 'inc/config.php';

// Pastikan user yang login memiliki hak untuk menghapus kegiatan
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu!";
    header('Location: login.php');
    exit();
}

// Cek apakah ada parameter ID di URL
if (isset($_GET['id'])) {
    $kegiatan_id = $_GET['id'];

    // Ambil data kegiatan untuk memastikan kegiatan milik user yang sedang login
    $stmt = $conn->prepare("SELECT * FROM kegiatan WHERE id = :id AND user_id = :user_id");
    $stmt->bindParam(':id', $kegiatan_id);
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    $kegiatan = $stmt->fetch();

    // Jika kegiatan ditemukan dan milik user yang login, lakukan penghapusan
    if ($kegiatan) {
        $stmt = $conn->prepare("DELETE FROM kegiatan WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $kegiatan_id);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Kegiatan berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus kegiatan!";
        }
    } else {
        $_SESSION['error'] = "Kegiatan tidak ditemukan atau Anda tidak memiliki hak akses!";
    }
} else {
    $_SESSION['error'] = "ID kegiatan tidak ditemukan!";
}

// Redirect ke halaman kegiatan setelah proses selesai
header('Location: dashboard.php');
exit();
