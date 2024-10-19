<?php
session_start();
require 'inc/config.php';

// Pastikan user yang login dapat mengedit kegiatan
if (isset($_GET['id'])) {
    $kegiatan_id = $_GET['id'];

    // Ambil data kegiatan berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM kegiatan WHERE id = :id");
    $stmt->bindParam(':id', $kegiatan_id);
    $stmt->execute();
    $kegiatan = $stmt->fetch();
    
    if (!$kegiatan || $kegiatan['user_id'] != $_SESSION['user_id']) {
        $_SESSION['error'] = "Kegiatan tidak ditemukan atau Anda tidak memiliki akses!";
        header('Location: kegiatan.php');
        exit();
    }
} else {
    header('Location: kegiatan.php');
    exit();
}

// Proses update data kegiatan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tanggal = $_POST['tanggal'];
    $nama_petugas = $_POST['nama_petugas'];
    $kegiatan = $_POST['kegiatan'];
    $keterangan = $_POST['keterangan'];

    $stmt = $conn->prepare("UPDATE kegiatan SET tanggal = :tanggal, nama_petugas = :nama_petugas, kegiatan = :kegiatan, keterangan = :keterangan WHERE id = :id");
    $stmt->bindParam(':tanggal', $tanggal);
    $stmt->bindParam(':nama_petugas', $nama_petugas);
    $stmt->bindParam(':kegiatan', $kegiatan);
    $stmt->bindParam(':keterangan', $keterangan);
    $stmt->bindParam(':id', $kegiatan_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Kegiatan berhasil diperbarui!";
        header('Location: dashboard.php');
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui kegiatan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kegiatan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'inc/header.php'; ?>

    <div class="container mt-5">
        <h3>Edit Kegiatan</h3>

        <form action="edit_kegiatan.php?id=<?= $kegiatan['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="tanggal" class="form-label">Tanggal</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?= $kegiatan['tanggal']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama_petugas" class="form-label">Nama Petugas</label>
                <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" value="<?= $kegiatan['nama_petugas']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="kegiatan" class="form-label">Kegiatan</label>
                <input type="text" class="form-control" id="kegiatan" name="kegiatan" value="<?= $kegiatan['kegiatan']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <textarea class="form-control" id="keterangan" name="keterangan" required><?= $kegiatan['keterangan']; ?></textarea>
            </div>
            <button type="submit" class="fw-bold btn btn-primary"><i class="fas fa-edit"></i> Update</button>
        </form>
    </div>

    <?php include 'inc/footer.php'; ?>
</body>
</html>
