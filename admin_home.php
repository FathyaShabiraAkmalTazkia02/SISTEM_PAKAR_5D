<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7fb url('img/pengguna.png') no-repeat top center;
            background-size: cover;   /* supaya menyesuaikan layar */
        }
        .container {
            max-width:700px; margin:60px auto; background:#ffffffcc;
            padding:30px 40px; border-radius:8px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
            text-align:center;
        }
        h2 { margin-top:0; color:#333; }
        .btn {
            display:block;
            width:220px;
            margin:6px auto;
            padding:8px 16px;
            background:#3498db; color:#fff;
            text-decoration:none;
            border-radius:4px;
        }
        .btn:hover { background:#2980b9; }
    </style>

</head>
<body>
<div class="container">
    <h2>Dashboard Admin</h2>

    <a href="admin_penyakit.php" class="btn">Admin Penyakit</a><br>
    <a href="admin_gejala.php" class="btn">Admin Gejala</a><br>
    <a href="admin_aturan.php" class="btn">Admin Aturan</a><br>
    <a href="riwayat_konsultasi.php" class="btn">Riwayat Konsultasi</a><br>
    <a href="index.php" class="btn">Kembali</a>
</div>
</body>
</html>
