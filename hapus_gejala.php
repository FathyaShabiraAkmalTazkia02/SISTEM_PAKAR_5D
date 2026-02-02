<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_epilepsi");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (!isset($_GET['id'])) {
    header("Location: admin_gejala.php");
    exit;
}

$id = (int) $_GET['id'];

// hati-hati: kalau gejala dipakai di tabel aturan, baris aturan itu sebaiknya dihapus juga
mysqli_query($koneksi, "DELETE FROM aturan WHERE id_gejala = $id");
mysqli_query($koneksi, "DELETE FROM gejala WHERE id_gejala = $id");

header("Location: admin_gejala.php");
exit;
