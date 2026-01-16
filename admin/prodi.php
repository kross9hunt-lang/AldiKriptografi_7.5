<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/database.php';

// Insert Data
if (isset($_POST['add'])) {
    $nama = $_POST['nama_prodi'];
    $jenjang = $_POST['jenjang'];

    $query = "INSERT INTO prodi(nama_prodi, jenjang) VALUES ('$nama', '$jenjang')";
    mysqli_query($conn, $query);
    header("Location: prodi.php");
    exit;
}

// Delete Data
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM prodi WHERE id=$id");
    header("Location: prodi.php");
    exit;
}

// Ambil Data
$result = mysqli_query($conn, "SELECT * FROM prodi");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Prodi</title>
</head>
<body>
    <h2>Kelola Program Studi</h2>

    <form action="" method="POST">
        <input type="text" name="nama_prodi" placeholder="Nama Prodi" required>
        <select name="jenjang" required>
            <option value="S1">S1</option>
            <option value="S2">S2</option>
        </select>
        <button type="submit" name="add">Tambah</button>
    </form>

    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Nama Prodi</th>
            <th>Jenjang</th>
            <th>Aksi</th>
        </tr>
        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)) : ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $row['nama_prodi']; ?></td>
            <td><?= $row['jenjang']; ?></td>
            <td>
                <a href="?delete=<?= $row['id']; ?>" onclick="return confirm('Hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="dashboard.php">Kembali</a>

</body>
</html>
