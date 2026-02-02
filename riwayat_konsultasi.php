<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ambil parameter filter
$tgl_awal  = isset($_GET['tgl_awal'])  ? $_GET['tgl_awal']  : '';
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : '';
$penyakit  = isset($_GET['penyakit'])  ? $_GET['penyakit']  : '';

$where = [];

// filter tanggal (jika dua-duanya diisi)
if ($tgl_awal != '' && $tgl_akhir != '') {
    $where[] = "DATE(tanggal_konsultasi) BETWEEN '$tgl_awal' AND '$tgl_akhir'";
}

// filter nama penyakit (LIKE agar bisa sebagian)
if ($penyakit != '') {
    $penyakit_safe = mysqli_real_escape_string($koneksi, $penyakit);
    $where[] = "hasil_penyakit LIKE '%$penyakit_safe%'";
}

$sql = "SELECT * FROM riwayat_konsultasi";
if (count($where) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY tanggal_konsultasi DESC";

$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Konsultasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7fb url('img/riwayat.png') no-repeat top center;
            background-size: cover;   /* supaya menyesuaikan layar */
        }
        .container {
            max-width:700px; margin:60px auto; background:#ffffffcc;
            padding:30px 40px; border-radius:8px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
            text-align:center;
        }
        h2 { margin-top: 0; color: #333; }
            form.filter {
            margin-bottom: 15px;
            padding: 10px 15px;
            background: #f9fafc;
            border-radius: 6px;
            display: flex;
            flex-wrap: wrap;          /* turun ke bawah jika sempit */
            align-items: center;
            justify-content: center;  /* rata tengah */
            gap: 8px 16px;            /* jarak antar blok */
        }
        .filter-group {
            display: flex;
            align-items: center;
            gap: 6px;                 /* jarak label–input */
        }
        label { font-size: 0.9rem; }
        input[type="date"], input[type="text"] {
            padding: 4px 6px;
            font-size: 0.9rem;
        }
        .btn {
            display: inline-block;
            padding: 6px 12px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .btn:hover { background: #2980b9; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background: #f0f0f0;
        }
        .small {
            font-size: 0.8rem;
            color: #666;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Riwayat Konsultasi</h2>

    <form method="get" class="filter">
        <label>Dari tanggal:</label>
        <input type="date" name="tgl_awal" value="<?php echo htmlspecialchars($tgl_awal); ?>">
        <label>Sampai:</label>
        <input type="date" name="tgl_akhir" value="<?php echo htmlspecialchars($tgl_akhir); ?>">
        <label>Penyakit:</label>
        <input type="text" name="penyakit" placeholder="Nama penyakit" value="<?php echo htmlspecialchars($penyakit); ?>">
        <button type="submit" class="btn">Filter</button>
        <a href="riwayat_konsultasi.php" class="btn">Reset</a>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Penyakit</th>
            <th>Persen</th>
            <th>Gejala dipilih (id)</th>
        </tr>
        <?php
        $no = 1;
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['tanggal_konsultasi']; ?></td>
                    <td><?php echo htmlspecialchars($row['hasil_penyakit']); ?></td>
                    <td><?php echo round($row['persen_kecocokan']); ?>%</td>
                    <td class="small"><?php echo htmlspecialchars($row['gejala_dipilih']); ?></td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr>
                <td colspan="5">Belum ada data riwayat yang sesuai filter.</td>
            </tr>
            <?php
        }
        ?>
    </table>

    <br>
    <a href="index.php" class="btn">Kembali ke Beranda</a>
</div>
</body>
</html>
