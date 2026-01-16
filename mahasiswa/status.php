<?php
require_once "../middleware/auth_middleware.php";
require_once "../config/database.php";

checkAuth();
checkRole(['mahasiswa']);

$user_id = $_SESSION['user_id'];

// Ambil npm mahasiswa
$mhs = mysqli_query($conn, "SELECT npm FROM mahasiswa WHERE user_id = $user_id");
$data = mysqli_fetch_assoc($mhs);

if (!$data) {
    die("Data mahasiswa tidak ditemukan.");
}

$npm = $data['npm'];

// Ambil status yudisium mahasiswa ini
$q = mysqli_query($conn, "
SELECT 
    p.nama_periode,
    y.status_validasi_prodi,
    y.status_final_baa
FROM yudisium y
JOIN periode p ON y.periode_id = p.id
WHERE y.npm = '$npm'
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Status Yudisium</title>
</head>
<body>

<h2>Status Pengajuan Yudisium</h2>

<table border="1" cellpadding="5">
<tr>
    <th>Periode</th>
    <th>Status Prodi</th>
    <th>Status BAA</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($q)) : ?>
<tr>
    <td><?= $row['nama_periode']; ?></td>
    <td>
        <?= $row['status_validasi_prodi'] == 0 ? 'Menunggu' : 'Disetujui'; ?>
    </td>
    <td>
        <?= $row['status_final_baa'] == 0 ? 'Menunggu' : 'Disetujui'; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>

<br>
<a href="dashboard.php">Kembali</a>
<a href="cetak_sk.php" target="_blank">
    <button>Cetak SK Yudisium (PDF)</button>
</a>


</body>
</html>
