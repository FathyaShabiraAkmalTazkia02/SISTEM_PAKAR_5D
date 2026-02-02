<!DOCTYPE html>
<html>
<head>
    <title>Beranda Pengguna</title>
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
            display:block;                  /* satu kolom */
            width:220px;                    /* lebar sama */
            margin:8px auto;                /* tengah horizontal */
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
    <h2>Beranda Pengguna</h2>
    <a href="konsultasi.php" class="btn">Mulai Konsultasi Gejala</a><br>
    <a href="riwayat_konsultasi.php" class="btn">Lihat Riwayat Konsultasi</a><br>
    <a href="konsultasi_bc.php" class="btn">Konsultasi Backward Chaining</a><br>
    <a href="index.php" class="btn">Kembali</a>
</div>
</body>
</html>
