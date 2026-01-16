<?php
require_once "../middleware/auth_middleware.php";
require_once "../config/database.php";

checkAuth();
checkRole(['baa']);

$query = "
SELECT 
    y.id,
    m.npm,
    m.nama,
    p.nama_prodi,
    y.nomor_sk,
    y.tgl_sk
FROM yudisium y
JOIN mahasiswa m ON y.npm = m.npm
JOIN prodi p ON m.prodi_id = p.id
WHERE y.status_validasi_prodi = 1
AND y.status_final_baa = 0
";

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Finalisasi Yudisium - BAA</title>
</head>
<body>

<h2>Finalisasi Yudisium</h2>

<table border="1" cellpadding="5">
<tr>
    <th>NPM</th>
    <th>Nama</th>
    <th>Prodi</th>
    <th>Nomor SK</th>
    <th>Tanggal SK</th>
    <th>Aksi</th>
</tr>

<?php if (mysqli_num_rows($result) == 0): ?>
<tr>
    <td colspan="6">Belum ada yang lolos Prodi</td>
</tr>
<?php endif; ?>

<?php while ($row = mysqli_fetch_assoc($result)): ?>
<tr>
<form method="POST" action="verifikasi_action.php">
    <td><?= $row['npm'] ?></td>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['nama_prodi'] ?></td>

    <td>
        <input type="text" name="nomor_sk" required>
    </td>

    <td>
        <input type="date" name="tgl_sk" required>
    </td>

    <td>
        <input type="hidden" name="id" value="<?= $row['id'] ?>">
        <button type="submit">Finalisasi</button>
    </td>
</form>
</tr>
<?php endwhile; ?>

</table>

<br>
<a href="dashboard.php">Kembali</a>

</body>
</html>
