<?php
// wajib di paling atas
session_start();
require_once "../middleware/auth_middleware.php";
require_once "../config/database.php";

// cek login & role
checkAuth();
checkRole(['prodi']);

// ambil prodi dari session
$prodi_id = $_SESSION['prodi_id'] ?? null;
if (!$prodi_id) {
    die("Prodi tidak terdeteksi");
}

// QUERY WAJIB LEFT JOIN
$prodi_id = $_SESSION['prodi_id'];

$query = "
SELECT 
    m.npm,
    m.nama,
    y.id AS yudisium_id,
    y.status_validasi_prodi,
    y.status_final_baa
FROM yudisium y
JOIN mahasiswa m ON y.npm = m.npm
WHERE m.prodi_id = $prodi_id
";



$result = mysqli_query($conn, $query);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Verifikasi Mahasiswa</title>
</head>
<body>

<h2>Verifikasi Pengajuan Yudisium</h2>

<table border="1" cellpadding="6" cellspacing="0">
    <tr>
        <th>NPM</th>
        <th>Nama</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>

<?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= htmlspecialchars($row['npm']) ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td>
                <?php
                if ($row['yudisium_id'] === null) {
                    echo "Belum daftar";
                } elseif ($row['status_validasi_prodi'] == 1) {
                    echo "Disetujui Prodi";
                } else {
                    echo "Menunggu Validasi";
                }
                ?>
            </td>
            <td>
                <?php if ($row['yudisium_id'] && $row['status_validasi_prodi'] == 0): ?>
                    <a href="verifikasi_action.php?id=<?= $row['yudisium_id'] ?>&aksi=approve"
                       onclick="return confirm('Setujui mahasiswa ini?')">
                        Setujui
                    </a>
                <?php else: ?>
                    -
                <?php endif; ?>
            </td>
        </tr>
    <?php endwhile; ?>
<?php else: ?>
    <tr>
        <td colspan="4" align="center">Belum ada pengajuan mahasiswa</td>
    </tr>
<?php endif; ?>

</table>

<br>
<a href="dashboard.php">← Kembali ke Dashboard</a>

</body>
</html>
