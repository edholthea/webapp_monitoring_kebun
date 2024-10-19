<?php
session_start();
require 'inc/config.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    $_SESSION['error'] = "Anda tidak memiliki izin untuk mengakses halaman ini!";
    header('Location: dashboard.php');
    exit();
}

// Menambah pengguna baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $nama_lengkap = $_POST['nama_lengkap'];
    $nomor_wa = $_POST['nomor_wa'];
    $nama_kebun = $_POST['nama_kebun'];
    $kode_kebun = $_POST['kode_kebun'];
    $role = $_POST['role'];

    $sql = "INSERT INTO users (username, password, nama_lengkap, nomor_wa, nama_kebun, kode_kebun, role) 
            VALUES (:username, :password, :nama_lengkap, :nomor_wa, :nama_kebun, :kode_kebun, :role)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':nama_lengkap', $nama_lengkap);
    $stmt->bindParam(':nomor_wa', $nomor_wa);
    $stmt->bindParam(':nama_kebun', $nama_kebun);
    $stmt->bindParam(':kode_kebun', $kode_kebun);
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Pengguna berhasil ditambahkan!";
        header('Location: kelola_user.php');
        exit();
    } else {
        $_SESSION['error'] = "Gagal menambahkan pengguna!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'inc/header.php'; ?>

    <div class="container mt-5">
        <h3>Tambah Pengguna</h3>

        <!-- Pesan error atau success -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Form tambah pengguna -->
        <form action="tambah_pengguna.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
            </div>
            <div class="mb-3">
                <label for="nomor_wa" class="form-label">Nomor WA</label>
                <input type="text" class="form-control" id="nomor_wa" name="nomor_wa" required>
            </div>
            <div class="mb-3">
                <label for="nama_kebun" class="form-label">Nama Kebun</label>
                <input type="text" class="form-control" id="nama_kebun" name="nama_kebun" required>
            </div>
            <div class="mb-3">
                <label for="kode_kebun" class="form-label">Kode Kebun</label>
                <input type="text" class="form-control" id="kode_kebun" name="kode_kebun" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Tambah Pengguna</button>
        </form>
    </div>

    <?php include 'inc/footer.php'; ?>
</body>
</html>
