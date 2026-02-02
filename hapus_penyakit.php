<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!isset($_GET['id'])) {
    header("Location: admin_penyakit.php");
    exit;
}

$id = (int) $_GET['id'];

// hapus dari tabel penyakit
$sql = "DELETE FROM penyakit WHERE id_penyakit = $id";
mysqli_query($koneksi, $sql);

header("Location: admin_penyakit.php");
exit;
