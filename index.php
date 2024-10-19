<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta name="description" content="Kebun Data | Integrasi Teknologi dan Pertanian. Platform Monitoring Kegiatan.">
    <link rel="icon" type="image/x-icon" href="../assets/img/kebunlogo_kecil.png">
    <title>Login</title>
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
            <div class="col-md-4">
                <div>
                <center><img src="../assets/img/kebunlogo_kecil.png" alt="logo kebun data"></center>
                </div>
                <h5 class="text-center">SISTEM MONITORING KEGIATAN</h5>
                <h6 class="text-center">by Kebun Data</h6>
                <center><a href="https://kebundata.my.id" target="_blank">www.kebundata.my.id</a></center><br>
                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <form action="process_login.php" method="POST" onsubmit="return validateCaptcha();">
                    <div class="mb-3">
                        <label for="username" class="fw-bold form-label"><i class="fas fa-user"></i> Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username"required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="fw-bold form-label"><i class="fas fa-lock"></i> Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password"required>
                    </div>
                    <div class="mb-3">
                        <label for="captcha" class="fw-bold form-label">CAPTCHA</label>
                        <div id="captcha-display" class="mb-3"></div>
                        <input type="text" class="form-control" id="captcha" name="captcha" placeholder="Masukan hasil" required>
                        <input type="hidden" id="num1" name="num1">
                        <input type="hidden" id="num2" name="num2">
                    </div>
                    <button type="submit" class="fw-bold btn btn-primary w-100">Login</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="register.php">Belum punya akun? Daftar Disini!</a>
                </div>
              	<div><br>
                  <center><a class="fw-bold btn btn-success" href="https://wa.me/6283865948003" role="button" target ="_blank"><i class="fab fa-whatsapp"></i> Hubungi Admin</a></center>
                </div>
            </div>
        </div>
    </div>
    <?php include 'inc/footer.php'; ?>
</body>
</html>
