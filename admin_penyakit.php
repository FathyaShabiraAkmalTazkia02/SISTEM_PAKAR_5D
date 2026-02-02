<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// proses tambah penyakit
if (isset($_POST['simpan'])) {
    $kode  = mysqli_real_escape_string($koneksi, $_POST['kode_penyakit']);
    $nama  = mysqli_real_escape_string($koneksi, $_POST['nama_penyakit']);
    $desk  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $saran = mysqli_real_escape_string($koneksi, $_POST['saran']);

    $sql_insert = "INSERT INTO penyakit (kode_penyakit, nama_penyakit, deskripsi, saran)
                   VALUES ('$kode', '$nama', '$desk', '$saran')";
    mysqli_query($koneksi, $sql_insert);
    header("Location: admin_penyakit.php");
    exit;
}

// ambil semua penyakit
$sql_p = "SELECT * FROM penyakit ORDER BY id_penyakit ASC";
$res_p = mysqli_query($koneksi, $sql_p);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Penyakit Epilepsi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            margin: 0;
        }
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            padding: 25px 35px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 { margin-top: 0; }
        .btn {
            display: inline-block;
            margin-top: 10px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
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
        textarea {
            width: 100%;
            min-height: 60px;
        }
        input[type="text"] {
            width: 100%;
            padding: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Admin Penyakit Epilepsi</h2>
    <a href="index.php" class="btn">Kembali ke Beranda</a>

    <h3>Tambah Penyakit</h3>
    <form method="post">
        <label>Kode Penyakit</label><br>
        <input type="text" name="kode_penyakit" placeholder="Misal: P04" required><br><br>

        <label>Nama Penyakit</label><br>
        <input type="text" name="nama_penyakit" required><br><br>

        <label>Deskripsi</label><br>
        <textarea name="deskripsi" required></textarea><br><br>

        <label>Saran</label><br>
        <textarea name="saran" required></textarea><br><br>

        <button type="submit" name="simpan" class="btn">Simpan</button>
    </form>

    <h3>Daftar Penyakit</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Penyakit</th>
            <th>Deskripsi</th>
            <th>Saran</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($res_p)) { ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['kode_penyakit']; ?></td>
                <td><?php echo $row['nama_penyakit']; ?></td>
                <td><?php echo nl2br($row['deskripsi']); ?></td>
                <td><?php echo nl2br($row['saran']); ?></td>
                <td>
                    <a href="hapus_penyakit.php?id=<?php echo $row['id_penyakit']; ?>"
                       class="btn btn-danger"
                       onclick="return confirm('Yakin hapus penyakit ini?');">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
