<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// proses tambah gejala
if (isset($_POST['simpan'])) {
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode_gejala']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_gejala']);

    $sql_insert = "INSERT INTO gejala (kode_gejala, nama_gejala)
                   VALUES ('$kode', '$nama')";
    mysqli_query($koneksi, $sql_insert);
    header("Location: admin_gejala.php");
    exit;
}

// ambil semua gejala
$sql_g = "SELECT * FROM gejala ORDER BY id_gejala ASC";
$res_g = mysqli_query($koneksi, $sql_g);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Gejala Epilepsi</title>
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
        th { background: #f0f0f0; }
        input[type="text"] {
            width: 100%;
            padding: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Admin Gejala Epilepsi</h2>
    <a href="index.php" class="btn">Kembali ke Beranda</a>
    &nbsp;
    <a href="admin_penyakit.php" class="btn">Kelola Penyakit</a>

    <h3>Tambah Gejala</h3>
    <form method="post">
        <label>Kode Gejala</label><br>
        <input type="text" name="kode_gejala" placeholder="Misal: G09" required><br><br>

        <label>Nama Gejala</label><br>
        <input type="text" name="nama_gejala" required><br><br>

        <button type="submit" name="simpan" class="btn">Simpan</button>
    </form>

    <h3>Daftar Gejala</h3>
    <table>
        <tr>
            <th>No</th>
            <th>Kode</th>
            <th>Nama Gejala</th>
            <th>Aksi</th>
        </tr>
        <?php
        $no = 1;
        while ($row = mysqli_fetch_assoc($res_g)) { ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['kode_gejala']; ?></td>
                <td><?php echo $row['nama_gejala']; ?></td>
                <td>
                    <a href="hapus_gejala.php?id=<?php echo $row['id_gejala']; ?>"
                       class="btn btn-danger"
                       onclick="return confirm('Yakin hapus gejala ini?');">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
