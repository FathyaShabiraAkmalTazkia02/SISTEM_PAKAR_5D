<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ambil daftar penyakit untuk dipilih sebagai hipotesis
$q_penyakit = mysqli_query($koneksi, "SELECT * FROM penyakit ORDER BY id_penyakit ASC");

// jika user sudah memilih penyakit (via GET), ambil gejala2 yang terkait
$id_penyakit = isset($_GET['id_penyakit']) ? (int)$_GET['id_penyakit'] : 0;
$gejala_penyakit = [];

if ($id_penyakit > 0) {
    $sql = "SELECT g.* FROM aturan a 
            JOIN gejala g ON a.id_gejala = g.id_gejala
            WHERE a.id_penyakit = $id_penyakit
            ORDER BY g.id_gejala ASC";
    $res = mysqli_query($koneksi, $sql);
    while ($row = mysqli_fetch_assoc($res)) {
        $gejala_penyakit[] = $row;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Konsultasi Backward Chaining</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7fb url('img/adminH.png') no-repeat top center;
            background-size: cover;
        }
        .container {
            max-width: 800px;
            margin: 60px auto;
            background: #ffffffcc;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 { margin-top: 0; color: #333; text-align:center; }
        p { line-height: 1.6; }
        .section { margin-bottom: 20px; }
        select {
            padding: 6px 8px;
            font-size: 0.95rem;
            margin-top: 5px;
        }
        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 0.9rem;
        }
        .btn:hover { background:#2980b9; }
        .tbl-gejala {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 0.95rem;
        }
        .tbl-gejala th, .tbl-gejala td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        .tbl-gejala th {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Konsultasi Backward Chaining</h2>

    <div class="section">
        <form method="get" action="konsultasi_bc.php">
            <label>Pilih penyakit (hipotesis):</label><br>
            <select name="id_penyakit" required onchange="this.form.submit()">
                <option value="">-- pilih penyakit --</option>
                <?php while ($p = mysqli_fetch_assoc($q_penyakit)) { ?>
                    <option value="<?php echo $p['id_penyakit']; ?>"
                        <?php if ($id_penyakit == $p['id_penyakit']) echo 'selected'; ?>>
                        <?php echo $p['id_penyakit'] . ' - ' . htmlspecialchars($p['nama_penyakit']); ?>
                    </option>
                <?php } ?>
            </select>
            <noscript>
                <button type="submit" class="btn">Tampilkan Gejala</button>
            </noscript>
        </form>
    </div>

    <?php if ($id_penyakit > 0 && count($gejala_penyakit) > 0) { ?>
        <div class="section">
            <p>
                Jawab gejala berikut untuk menguji apakah hipotesis penyakit tersebut
                <strong>benar</strong> untuk kondisi pengguna.
            </p>
            <form method="post" action="proses_bc.php">
                <input type="hidden" name="id_penyakit" value="<?php echo $id_penyakit; ?>">
                <table class="tbl-gejala">
                    <tr>
                        <th>Pilih</th>
                        <th>Kode</th>
                        <th>Gejala</th>
                    </tr>
                    <?php foreach ($gejala_penyakit as $g) { ?>
                        <tr>
                            <td style="text-align:center;">
                                <input type="checkbox" name="gejala[]" value="<?php echo $g['id_gejala']; ?>">
                            </td>
                            <td><?php echo htmlspecialchars($g['kode_gejala']); ?></td>
                            <td><?php echo htmlspecialchars($g['nama_gejala']); ?></td>
                        </tr>
                    <?php } ?>
                </table>
                <button type="submit" class="btn">Uji Hipotesis</button>
                <a href="pengguna_home.php" class="btn">Kembali</a>
            </form>
        </div>
    <?php } elseif ($id_penyakit > 0 && count($gejala_penyakit) == 0) { ?>
        <p>Penyakit ini belum memiliki aturan gejala.</p>
    <?php } ?>

</div>
</body>
</html>
