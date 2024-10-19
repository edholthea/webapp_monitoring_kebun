<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../assets/img/kebunlogo_kecil.png">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <script>
        function generateCaptcha() {
            let num1 = Math.floor(Math.random() * 10);
            let num2 = Math.floor(Math.random() * 10);
            document.getElementById('num1').value = num1;
            document.getElementById('num2').value = num2;
            document.getElementById('captcha-display').innerHTML = num1 + " + " + num2 + " = ?";
        }

        function validateCaptcha() {
            let num1 = parseInt(document.getElementById('num1').value);
            let num2 = parseInt(document.getElementById('num2').value);
            let captchaResult = num1 + num2;
            let userCaptcha = parseInt(document.getElementById('captcha').value);

            if (captchaResult !== userCaptcha) {
                alert("CAPTCHA salah. Coba lagi.");
                return false;
            }
            return true;
        }

        window.onload = generateCaptcha;
    </script>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
              	<div>
                <center><img src="../assets/img/kebunlogo_kecil.png" alt="logo kebun data"></center>
                </div>
                <h3 class="text-center">Daftar Pengguna Baru</h3><hr><br>
                <form action="process_register.php" method="POST" onsubmit="return validateCaptcha();">
                    <div class="mb-3">
                        <label for="username" class="form-label"><i class="fas fa-user"></i> Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username"required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label"><i class="far fa-address-card"></i> Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Contoh : Edi Nurhadi"required>
                    </div>
                    <div class="mb-3">
                        <label for="nomor_wa" class="form-label"><i class="fab fa-whatsapp"></i> Nomor WA</label>
                        <input type="text" class="form-control" id="nomor_wa" name="nomor_wa" placeholder="Contoh : 0123456789"required>
                    </div>
                    <div class="mb-3">
                        <label for="nama_kebun" class="form-label"><i class="fas fa-spa"></i> Nama Kebun</label>
                        <input type="text" class="form-control" id="nama_kebun" name="nama_kebun" placeholder="Contoh : Kebun Data"required>
                    </div>
                    <div class="mb-3">
                        <label for="kode_kebun" class="form-label"><i class="fas fa-code"></i> Kode Kebun</label>
                        <input type="text" class="form-control" id="kode_kebun" name="kode_kebun" placeholder="Contoh : KBD01"required>
                    </div>
                    <div class="mb-3">
                        <label for="captcha" class="form-label">
                        <label for="captcha" class="form-label">CAPTCHA</label>
                        <div id="captcha-display" class="mb-3"></div>
                        <input type="text" class="form-control" id="captcha" name="captcha" placeholder="Masukan hasil" required>
                        <input type="hidden" id="num1" name="num1">
                        <input type="hidden" id="num2" name="num2">
                        </div>
                        <button type="submit" class="fw-bold btn btn-primary w-100">DAFTAR</button>
                </form>
                        <div class="mt-3 text-center">
                            <a href="index.php">Sudah punya akun? Login Disini!.</a>
                        </div>
            </div>
        </div>
    </div>

    <?php include 'inc/footer.php'; ?>
</body>
</html>
