<?php
session_start();
require_once "../middleware/auth_middleware.php";
require_once "../config/database.php";

checkAuth();
checkRole(['prodi']);

if (!isset($_GET['npm'])) {
    header("Location: verifikasi.php");
    exit;
}

$npm = $_GET['npm'];
$query = "SELECT m.*, y.id AS y_id, y.status_validasi_prodi
          FROM mahasiswa m 
          JOIN yudisium y ON m.npm = y.npm
          WHERE m.npm='$npm' LIMIT 1";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data tidak ditemukan.");
}

// Proses update data
if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $ipk = $_POST['ipk'];
    $predikat = $_POST['predikat'];

    mysqli_query($conn, "UPDATE mahasiswa SET 
        nama='$nama',
        ipk='$ipk',
        predikat='$predikat'
        WHERE npm='$npm'");

    echo "<script>alert('Data diperbarui'); window.location='edit_mahasiswa.php?npm=$npm';</script>";
}

// Validasi
if (isset($_POST['validasi'])) {
    mysqli_query($conn, "UPDATE yudisium SET status_validasi_prodi=1 WHERE npm='$npm'");
    echo "<script>alert('Divalidasi.'); window.location='verifikasi.php';</script>";
}

// Tolak
if (isset($_POST['tolak'])) {
    mysqli_query($conn, "UPDATE yudisium SET status_validasi_prodi=0 WHERE npm='$npm'");
    echo "<script>alert('Ditolak.'); window.location='verifikasi.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Mahasiswa</title>
    <link rel="stylesheet" href="../public/assets/css/tailwind.css">
</head>
<body class="p-6">
<h2 class="text-xl font-bold mb-4">Detail Mahasiswa</h2>

<form method="POST">
    <label>Nama</label>
    <input type="text" name="nama" class="border p-2 w-full mb-3" value="<?= $data['nama'] ?>">

    <label>IPK</label>
    <input type="text" name="ipk" class="border p-2 w-full mb-3" value="<?= $data['ipk'] ?>">

    <label>Predikat</label>
    <input type="text" name="predikat" class="border p-2 w-full mb-3" value="<?= $data['predikat'] ?>">

    <button type="submit" name="update" class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    <button type="submit" name="validasi" class="bg-green-600 text-white px-4 py-2 rounded">Validasi</button>
    <button type="submit" name="tolak" class="bg-red-600 text-white px-4 py-2 rounded">Tolak</button>
</form>

<br>
<a href="verifikasi.php" class="text-gray-700 font-semibold">Kembali</a>
</body>
</html>
