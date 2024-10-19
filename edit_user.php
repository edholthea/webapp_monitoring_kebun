<?php
session_start();
require 'inc/config.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk mengakses halaman ini!";
    header('Location: dashboard.php');
    exit();
}

// Ambil data pengguna berdasarkan ID
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch();
    
    if (!$user) {
        $_SESSION['error'] = "Pengguna tidak ditemukan!";
        header('Location: kelola_user.php');
        exit();
    }
} else {
    header('Location: kelola_user.php');
    exit();
}

// Update data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $nomor_wa = $_POST['nomor_wa'];
    $nama_kebun = $_POST['nama_kebun'];
    $kode_kebun = $_POST['kode_kebun'];
    $role = $_POST['role'];

    $sql = "UPDATE users SET username = :username, nama_lengkap = :nama_lengkap, nomor_wa = :nomor_wa, nama_kebun = :nama_kebun, kode_kebun = :kode_kebun, role = :role WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':nama_lengkap', $nama_lengkap);
    $stmt->bindParam(':nomor_wa', $nomor_wa);
    $stmt->bindParam(':nama_kebun', $nama_kebun);
    $stmt->bindParam(':kode_kebun', $kode_kebun);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':id', $user_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Pengguna berhasil diperbarui!";
        header('Location: kelola_user.php');
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui pengguna!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'inc/header.php'; ?>

    <div class="container mt-5">
        <h3>Edit Pengguna</h3>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <form action="edit_user.php?id=<?= $user['id']; ?>" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $user['username']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?= $user['nama_lengkap']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nomor_wa" class="form-label">Nomor WA</label>
                <input type="text" class="form-control" id="nomor_wa" name="nomor_wa" value="<?= $user['nomor_wa']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="nama_kebun" class="form-label">Nama Kebun</label>
                <input type="text" class="form-control" id="nama_kebun" name="nama_kebun" value="<?= $user['nama_kebun']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="kode_kebun" class="form-label">Kode Kebun</label>
                <input type="text" class="form-control" id="kode_kebun" name="kode_kebun" value="<?= $user['kode_kebun']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Pengguna</button>
        </form>
    </div>

    <?php include
    'inc/footer.php'; ?>
    </body>
    </html>
    