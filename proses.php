<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// JIKA TIDAK ADA GEJALA DIPILIH
if (!isset($_POST['gejala']) || count($_POST['gejala']) == 0) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Hasil Diagnosa Epilepsi</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f5f7fb;
                margin: 0;
            }
            .container {
                max-width: 700px;
                margin: 60px auto;
                background: #fff;
                padding: 30px 40px;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }
            h2 { margin-top: 0; color: #333; }
            p { line-height: 1.6; }
            .btn {
                display: inline-block;
                margin-top: 15px;
                padding: 8px 16px;
                background: #3498db;
                color: #fff;
                text-decoration: none;
                border-radius: 4px;
            }
            .btn:hover { background: #2980b9; }
        </style>
    </head>
    <body>
    <div class="container">
        <h2>Hasil Diagnosa Awal</h2>
        <p><strong>Informasi:</strong> Anda belum memilih gejala apa pun.</p>
        <p>Silakan kembali ke halaman konsultasi dan pilih minimal satu gejala terlebih dahulu.</p>
        <a href="konsultasi.php" class="btn">Kembali ke Konsultasi</a>
    </div>
    </body>
    </html>
    <?php
    exit;
}

// JIKA ADA GEJALA DIPILIH
$selected_gejala = $_POST['gejala'];

$sql_p = "SELECT * FROM penyakit";
$res_p = mysqli_query($koneksi, $sql_p);

$hasil = [];

while ($p = mysqli_fetch_assoc($res_p)) {
    $idp = $p['id_penyakit'];

    $sql_syarat = "SELECT id_gejala FROM aturan WHERE id_penyakit = $idp";
    $res_syarat = mysqli_query($koneksi, $sql_syarat);

    $total_syarat = 0;
    $terpenuhi   = 0;

    while ($s = mysqli_fetch_assoc($res_syarat)) {
        $total_syarat++;
        if (in_array($s['id_gejala'], $selected_gejala)) {
            $terpenuhi++;
        }
    }

    $persen = ($total_syarat > 0) ? ($terpenuhi / $total_syarat * 100) : 0;

    $hasil[] = [
        'nama_penyakit' => $p['nama_penyakit'],
        'deskripsi'     => $p['deskripsi'],
        'saran'         => $p['saran'],
        'persen'        => $persen
    ];
}

// urutkan dari persen terbesar ke terkecil
if (count($hasil) > 0) {
    usort($hasil, function($a, $b) {
        return $b['persen'] <=> $a['persen'];
    });
    $terbaik = $hasil[0];
} else {
    // fallback kalau tidak ada penyakit sama sekali
    $terbaik = [
        'nama_penyakit' => '',
        'deskripsi'     => '',
        'saran'         => '',
        'persen'        => 0
    ];
}

// simpan riwayat konsultasi ke database
$gejala_ids  = $selected_gejala;              // array id_gejala
$gejala_list = implode(',', $gejala_ids);     // "1,2,4"
$tanggal       = date('Y-m-d H:i:s');
$nama_penyakit = $terbaik['nama_penyakit'];
$persen        = $terbaik['persen'];

$sql_riwayat = "INSERT INTO riwayat_konsultasi
    (tanggal_konsultasi, gejala_dipilih, hasil_penyakit, persen_kecocokan)
    VALUES ('$tanggal', '$gejala_list', '$nama_penyakit', $persen)";
mysqli_query($koneksi, $sql_riwayat);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Hasil Diagnosa Epilepsi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
        }
        .container {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 { margin-top: 0; color: #333; }
        p { line-height: 1.6; }
        .warning {
            color: #c0392b;
            font-size: 0.95rem;
        }
        .btn {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 16px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover { background: #2980b9; }
    </style>
</head>
<body>
<div class="container">
    <h2>Hasil Diagnosa Awal</h2>

    <?php if ($terbaik['persen'] <= 0) { ?>
        <p>Belum cukup gejala untuk memberikan indikasi epilepsi tertentu.</p>
    <?php } else { ?>
        <p><strong>Kemungkinan:</strong>
            <?php echo htmlspecialchars($terbaik['nama_penyakit']); ?>
            (<?php echo round($terbaik['persen']); ?>%)
        </p>
        <p><strong>Deskripsi:</strong><br><?php echo nl2br(htmlspecialchars($terbaik['deskripsi'])); ?></p>
        <p><strong>Saran:</strong><br><?php echo nl2br(htmlspecialchars($terbaik['saran'])); ?></p>
    <?php } ?>

    <p class="warning">
        Hasil ini hanya informasi awal dan bukan pengganti diagnosis dokter.
        Tetap konsultasi ke dokter saraf untuk pemeriksaan lebih lanjut.
    </p>

    <a href="konsultasi.php" class="btn">Kembali ke Konsultasi</a>
</div>
</body>
</html>
