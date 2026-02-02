<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "SELECT * FROM gejala ORDER BY id_gejala ASC";
$result = mysqli_query($koneksi, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Konsultasi Epilepsi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f5f7fb url('img/konsultasi.png') no-repeat top center;
            background-size: cover;   /* supaya menyesuaikan layar */
        }
        .container {
            max-width:700px; margin:60px auto; background:#ffffffcc;
            padding:30px 40px; border-radius:8px;
            box-shadow:0 2px 8px rgba(0,0,0,0.1);
            text-align:center;
        }
        h1 {
            margin-top: 0;
            color: #333;
        }
        p { line-height: 1.6; }
        .note {
            color: #c0392b;
            font-size: 0.95rem;
        }
        .btn {
            display: inline-block;
            margin-top: 20px; /* misalnya 20px */
            padding: 8px 16px;
            background: #3498db;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover {
            background: #2980b9;
        }
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
        <h2>Konsultasi Gejala Epilepsi</h2>
        <form method="post" action="proses.php">
            <table class="tbl-gejala">
                <tr>
                    <th>Pilih</th>
                    <th>Kode</th>
                    <th>Gejala</th>
                </tr>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td style="text-align:center;">
                            <input type="checkbox" name="gejala[]" value="<?php echo $row['id_gejala']; ?>">
                        </td>
                        <td><?php echo htmlspecialchars($row['kode_gejala']); ?></td>
                        <td><?php echo htmlspecialchars($row['nama_gejala']); ?></td>
                    </tr>
                <?php } ?>
            </table>
            <button type="submit" class="btn">Proses Diagnosa</button>
        </form>
        <br>
        <a href="index.php" class="btn">Kembali ke Beranda</a>
    </div>
</body>
</html>
