<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!isset($_POST['id_penyakit'])) {
    header("Location: konsultasi_bc.php");
    exit;
}

$id_penyakit = (int)$_POST['id_penyakit'];
$selected_gejala = isset($_POST['gejala']) ? $_POST['gejala'] : [];

// ambil info penyakit
$q_p = mysqli_query($koneksi, "SELECT * FROM penyakit WHERE id_penyakit = $id_penyakit");
$penyakit = mysqli_fetch_assoc($q_p);

// ambil semua gejala yang menjadi syarat penyakit (aturan)
$sql_syarat = "SELECT id_gejala FROM aturan WHERE id_penyakit = $id_penyakit";
$res_syarat = mysqli_query($koneksi, $sql_syarat);

$total_syarat = 0;
$terpenuhi   = 0;

while ($s = mysqli_fetch_assoc($res_syarat)) {
    $total_syarat++;
    if (in_array($s['id_gejala'], $selected_gejala)) {
        $terpenuhi++;
    }
}

// hitung persentase pemenuhan aturan (BC: seberapa kuat hipotesis didukung)
$persen = ($total_syarat > 0) ? ($terpenuhi / $total_syarat * 100) : 0;

// simpulkan hipotesis
if ($total_syarat > 0 && $terpenuhi == $total_syarat) {
    $kesimpulan = "Hipotesis DITERIMA: semua gejala syarat terpenuhi.";
} elseif ($total_syarat > 0 && $terpenuhi > 0) {
    $kesimpulan = "Hipotesis SEBAGIAN didukung: hanya " . $terpenuhi . " dari " . $total_syarat . " gejala terpenuhi.";
} else {
    $kesimpulan = "Hipotesis DITOLAK: tidak ada gejala syarat yang terpenuhi.";
}

// simpan ke riwayat_konsultasi (opsional, kita simpan juga)
$gejala_list = implode(',', $selected_gejala);
$tanggal     = date('Y-m-d H:i:s');
$nama_penyakit = $penyakit ? $penyakit['nama_penyakit'] : '';

// tandai bahwa ini hasil backward chaining (bisa ditambah teks)
$sql_riwayat = "INSERT INTO riwayat_konsultasi
    (tanggal_konsultasi, gejala_dipilih, hasil_penyakit, persen_kecocokan)
    VALUES ('$tanggal', '$gejala_list', '$nama_penyakit (BC)', $persen)";
mysqli_query($koneksi, $sql_riwayat);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Hasil Backward Chaining</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7fb url('img/G1.png') no-repeat top center;
            background-size: cover;
        }
        .container {
            max-width: 700px;
            margin: 60px auto;
            background: #ffffffcc;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 { margin-top: 0; color: #333; }
        p { line-height: 1.6; }
        .highlight { font-weight:bold; }
        .btn {
            display:inline-block;
            margin-top:15px;
            padding:8px 16px;
            background:#3498db;
            color:#fff;
            text-decoration:none;
            border-radius:4px;
        }
        .btn:hover { background:#2980b9; }
        ul { line-height:1.5; }
    </style>
</head>
<body>
<div class="container">
    <h2>Hasil Uji Hipotesis (Backward Chaining)</h2>

    <p><span class="highlight>Penyakit yang diuji:</span>
        <?php echo htmlspecialchars($nama_penyakit); ?></p>

    <p><span class="highlight">Persentase pemenuhan gejala:</span>
        <?php echo round($persen); ?>%</p>

    <p><span class="highlight">Kesimpulan:</span><br>
        <?php echo htmlspecialchars($kesimpulan); ?>
    </p>

    <?php if ($penyakit) { ?>
        <p><span class="highlight">Deskripsi singkat:</span><br>
            <?php echo nl2br(htmlspecialchars($penyakit['deskripsi'])); ?>
        </p>
        <p><span class="highlight">Saran:</span><br>
            <?php echo nl2br(htmlspecialchars($penyakit['saran'])); ?>
        </p>
    <?php } ?>

    <p>
        Hasil backward chaining ini digunakan untuk menguji satu hipotesis penyakit
        berdasarkan gejala yang menjadi syaratnya, dan tetap tidak menggantikan
        diagnosis dokter yang sebenarnya.
    </p>

    <a href="konsultasi_bc.php" class="btn">Uji Hipotesis Lain</a>
    <a href="pengguna_home.php" class="btn">Kembali ke Beranda Pengguna</a>
</div>
</body>
</html>
