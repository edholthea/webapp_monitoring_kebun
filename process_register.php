<?php
session_start();
require 'inc/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama_lengkap = $_POST['nama_lengkap'];
    $nomor_wa = $_POST['nomor_wa'];
    $nama_kebun = $_POST['nama_kebun'];
    $kode_kebun = $_POST['kode_kebun'];
    $role = 'user';
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Username sudah ada!";
        header('Location: register.php');
        exit();
    }

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
        $_SESSION['success'] = "Pendaftaran Berhasil! Silahkan Login.";
        header('Location: index.php');
    } else {
        $_SESSION['error'] = "Failed to register. Please try again.";
        header('Location: register.php');
    }
}
?>
