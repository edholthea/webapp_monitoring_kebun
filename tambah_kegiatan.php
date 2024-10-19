<?php
session_start();
require 'inc/config.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Anda harus login terlebih dahulu!";
    header('Location: login.php');
    exit();
}

// Proses tambah kegiatan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $nama_petugas = $_POST['nama_petugas'];
    $kegiatan = $_POST['kegiatan'];
    $keterangan = $_POST['keterangan'];

    // Simpan kegiatan ke database
    $stmt = $conn->prepare("INSERT INTO kegiatan (user_id, tanggal, nama_petugas, kegiatan, keterangan) VALUES (:user_id, :tanggal, :nama_petugas, :kegiatan, :keterangan)");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':tanggal', $tanggal);
    $stmt->bindParam(':nama_petugas', $nama_petugas);
    $stmt->bindParam(':kegiatan', $kegiatan);
    $stmt->bindParam(':keterangan', $keterangan);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Kegiatan berhasil ditambahkan!";
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = "Gagal menambahkan kegiatan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/img/kebunlogo_kecil.png">
    <title>Tambah Kegiatan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'inc/header.php'; ?>

    <div class="container mt-5">
        <h3>Tambah Kegiatan</h3><hr><br>

        <!-- Form tambah kegiatan -->
        <form action="tambah_kegiatan.php" method="POST">
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <div class="mb-3">
                <label for="nama_petugas" class="form-label">Nama Petugas</label>
                <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" required>
            </div>
            <div class="mb-3">
                <label for="kegiatan" class="form-label">Kegiatan</label>
                <input type="text" class="form-control" id="kegiatan" name="kegiatan" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" required></textarea>
            </div>
            <button type="submit" class="fw-bold btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
        </form>
    </div>

    <?php include 'inc/footer.php'; ?>
</body>
</html>
