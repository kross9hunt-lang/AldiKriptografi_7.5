<?php
require_once "../middleware/auth_middleware.php";
require_once "../config/database.php";

checkAuth();
checkRole(['mahasiswa']);

$user_id = $_SESSION['user_id'];

// Ambil data mahasiswa
$query = "SELECT * FROM mahasiswa WHERE user_id = $user_id";
$result = mysqli_query($conn, $query);
$mhs = mysqli_fetch_assoc($result);

if (!$mhs) {
    die("Data mahasiswa tidak ditemukan. Hubungi admin.");
}

$npm = $mhs['npm'];
$prodi_id = $mhs['prodi_id'];

if (!$npm || !$prodi_id) {
    die("Data mahasiswa belum lengkap. Hubungi admin.");
}

// Cek apakah sudah pernah daftar yudisium
$cek = mysqli_query($conn, "SELECT * FROM yudisium WHERE npm = '$npm'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Kamu sudah mengajukan yudisium!');window.location='status.php';</script>";
    exit;
}

// Ambil periode aktif (ambil yang pertama saja)
$periode = mysqli_query($conn, "SELECT id FROM periode ORDER BY id DESC LIMIT 1");
$p = mysqli_fetch_assoc($periode);

if (!$p) {
    die("Belum ada periode yudisium. Hubungi admin.");
}

$periode_id = $p['id'];

// Proses form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tgl_mulai = $_POST['tgl_mulai_kuliah'];
    $tgl_ujian = $_POST['tgl_ujian'];
    $ipk = $_POST['ipk'];
    $predikat = $_POST['predikat'];
    $peringkat = $_POST['peringkat'];

    // Update data mahasiswa
    mysqli_query($conn, "
        UPDATE mahasiswa SET 
            tgl_mulai_kuliah='$tgl_mulai',
            tgl_ujian='$tgl_ujian',
            ipk='$ipk',
            predikat='$predikat',
            peringkat='$peringkat'
        WHERE npm='$npm'
    ");

    // Insert ke yudisium
    mysqli_query($conn, "
        INSERT INTO yudisium(npm, periode_id, status_validasi_prodi, status_final_baa)
        VALUES('$npm', $periode_id, 0, 0)
    ");

    echo "<script>alert('Pengajuan berhasil! Menunggu verifikasi Prodi.');window.location='status.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengajuan Yudisium</title>
</head>
<body>

<h2>Form Pengajuan Yudisium</h2>

<form method="POST">
    <label>Tanggal Mulai Kuliah</label><br>
    <input type="date" name="tgl_mulai_kuliah" required><br><br>

    <label>Tanggal Ujian Terakhir</label><br>
    <input type="date" name="tgl_ujian" required><br><br>

    <label>IPK</label><br>
    <input type="number" step="0.01" name="ipk" required><br><br>

    <label>Predikat</label><br>
    <input type="text" name="predikat" required><br><br>

    <label>Peringkat</label><br>
    <input type="text" name="peringkat" required><br><br>

    <button type="submit">Ajukan</button>
</form>

<br>
<a href="dashboard.php">Kembali</a>

</body>
</html>
