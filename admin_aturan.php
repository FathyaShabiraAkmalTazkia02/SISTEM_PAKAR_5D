<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// proses simpan aturan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_penyakit = isset($_POST['id_penyakit']) ? (int)$_POST['id_penyakit'] : 0;
    $gejala = isset($_POST['gejala']) ? $_POST['gejala'] : [];

    if ($id_penyakit > 0 && count($gejala) > 0) {
        // hapus aturan lama untuk penyakit ini (supaya tidak dobel)
        mysqli_query($koneksi, "DELETE FROM aturan WHERE id_penyakit = $id_penyakit");

        foreach ($gejala as $idg) {
            $idg = (int)$idg;
            mysqli_query($koneksi,
                "INSERT INTO aturan (id_penyakit, id_gejala) VALUES ($id_penyakit, $idg)"
            );
        }
    }
}

// proses hapus satu aturan (opsional)
if (isset($_GET['hapus']) && ctype_digit($_GET['hapus'])) {
    $id_aturan = (int)$_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM aturan WHERE id_aturan = $id_aturan");
    header("Location: admin_aturan.php");
    exit;
}

// ambil data penyakit & gejala
$q_penyakit = mysqli_query($koneksi, "SELECT * FROM penyakit ORDER BY id_penyakit ASC");
$q_gejala   = mysqli_query($koneksi, "SELECT * FROM gejala ORDER BY id_gejala ASC");

// untuk tabel daftar aturan (join biar kelihatan nama)
$sql_daftar = "
    SELECT a.id_aturan, p.nama_penyakit, g.kode_gejala, g.nama_gejala
    FROM aturan a
    JOIN penyakit p ON a.id_penyakit = p.id_penyakit
    JOIN gejala g   ON a.id_gejala   = g.id_gejala
    ORDER BY p.id_penyakit, g.id_gejala
";
$r_daftar = mysqli_query($koneksi, $sql_daftar);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Aturan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
        }
        .container {
            max-width: 1000px;
            margin: 30px auto;
            background: #fff;
            padding: 25px 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 { margin-top: 0; color: #333; }
        .section {
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        label { font-size: 0.9rem; }
        select {
            padding: 5px 8px;
            margin: 5px 0 10px;
            font-size: 0.9rem;
        }
        .tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            margin-top: 10px;
        }
        .tbl th, .tbl td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        .tbl th {
            background: #f0f0f0;
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
        .btn-danger {
            background: #e74c3c;
        }
        .btn-danger:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
<div class="container">

    <h2>Admin Aturan Penyakit - Gejala</h2>

    <!-- Form input aturan -->
    <div class="section">
        <form method="post">
            <label>Pilih Penyakit:</label><br>
            <select name="id_penyakit" required>
                <option value="">-- pilih penyakit --</option>
                <?php while ($p = mysqli_fetch_assoc($q_penyakit)) { ?>
                    <option value="<?php echo $p['id_penyakit']; ?>">
                        <?php echo $p['id_penyakit'] . " - " . htmlspecialchars($p['nama_penyakit']); ?>
                    </option>
                <?php } ?>
            </select>

            <p><strong>Pilih Gejala (ceklist gejala yang menjadi syarat):</strong></p>
            <table class="tbl">
                <tr>
                    <th>Pilih</th>
                    <th>Kode</th>
                    <th>Nama Gejala</th>
                </tr>
                <?php while ($g = mysqli_fetch_assoc($q_gejala)) { ?>
                    <tr>
                        <td style="text-align:center;">
                            <input type="checkbox" name="gejala[]" value="<?php echo $g['id_gejala']; ?>">
                        </td>
                        <td><?php echo htmlspecialchars($g['kode_gejala']); ?></td>
                        <td><?php echo htmlspecialchars($g['nama_gejala']); ?></td>
                    </tr>
                <?php } ?>
            </table>

            <br>
            <button type="submit" class="btn">Simpan Aturan</button>
            <a href="index.php" class="btn">Kembali</a>
        </form>
        <p style="font-size:0.85rem;color:#666;">
            Catatan: Saat Anda menyimpan, aturan lama untuk penyakit yang dipilih akan dihapus
            dan diganti dengan kombinasi gejala yang baru.
        </p>
    </div>

    <!-- Tabel daftar aturan -->
    <div class="section">
        <h3>Daftar Aturan Yang Ada</h3>
        <table class="tbl">
            <tr>
                <th>No</th>
                <th>Penyakit</th>
                <th>Kode Gejala</th>
                <th>Nama Gejala</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            if ($r_daftar && mysqli_num_rows($r_daftar) > 0) {
                while ($row = mysqli_fetch_assoc($r_daftar)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($row['nama_penyakit']); ?></td>
                        <td><?php echo htmlspecialchars($row['kode_gejala']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_gejala']); ?></td>
                        <td>
                            <a class="btn btn-danger"
                               href="admin_aturan.php?hapus=<?php echo $row['id_aturan']; ?>"
                               onclick="return confirm('Hapus aturan ini?');">
                                Hapus
                            </a>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr>
                    <td colspan="5">Belum ada aturan.</td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>

</div>
</body>
</html>
