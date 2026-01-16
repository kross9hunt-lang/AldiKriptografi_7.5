<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

include '../config/database.php';

// CREATE user
if (isset($_POST['add'])) {
    $username = $_POST['username'];
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $prodi_id = isset($_POST['prodi_id']) && $_POST['prodi_id'] != '' 
        ? $_POST['prodi_id'] 
        : NULL;

    // Insert ke users
    $query = "INSERT INTO users(username, password_hash, nama, role, prodi_id)
              VALUES('$username', '$pass', '$nama', '$role', " . 
              ($prodi_id === NULL ? "NULL" : $prodi_id) . ")";

    if (mysqli_query($conn, $query)) {

        // Ambil ID user baru
        $user_id = mysqli_insert_id($conn);

        // Kalau role mahasiswa → wajib buat data mahasiswa
        if ($role === 'mahasiswa') {
            $npm = $_POST['username']; // Pakai username sebagai NPM sementara
            mysqli_query($conn, "
                INSERT INTO mahasiswa(npm, user_id, nama, prodi_id)
                VALUES('$npm', $user_id, '$nama', $prodi_id)
            ");
        }

    }

    header("Location: user_management.php");
    exit;
}

// DELETE user
// DELETE user (aman dengan foreign key)
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Cek apakah user ini punya data mahasiswa
    $getMhs = mysqli_query($conn, "SELECT npm FROM mahasiswa WHERE user_id=$id");
    $mhs = mysqli_fetch_assoc($getMhs);

    if ($mhs) {
        $npm = $mhs['npm'];

        // Hapus yudisium dulu
        mysqli_query($conn, "DELETE FROM yudisium WHERE npm='$npm'");

        // Hapus mahasiswa
        mysqli_query($conn, "DELETE FROM mahasiswa WHERE user_id=$id");
    }

    // Baru hapus user
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");

    header("Location: user_management.php");
    exit;
}


// READ DATA
$users = mysqli_query($conn, "SELECT users.*, prodi.nama_prodi
                              FROM users
                              LEFT JOIN prodi ON users.prodi_id = prodi.id");

$prodi_list = mysqli_query($conn, "SELECT * FROM prodi");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kelola User</title>
<style>
    body { font-family: Arial; padding: 20px; }
    table { border-collapse: collapse; width: 100%; margin-top: 15px; }
    th, td { padding: 8px; border: 1px solid #555; text-align: left; }
</style>
</head>
<body>

<h2>Kelola Data User</h2>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required>
    <input type="text" name="nama" placeholder="Nama Lengkap" required>
    <input type="password" name="password" placeholder="Password" required>

    <select name="role" id="roleDropdown" onchange="toggleProdi()" required>
        <option value="">Pilih Role</option>
        <option value="admin">Admin</option>
        <option value="baa">BAA</option>
        <option value="prodi">Prodi</option>
        <option value="mahasiswa">Mahasiswa</option>
    </select>

    <select name="prodi_id" id="prodiSelect" style="display:none;">
        <option value="">Pilih Prodi</option>
        <?php while ($p = mysqli_fetch_assoc($prodi_list)) : ?>
            <option value="<?= $p['id']; ?>"><?= $p['nama_prodi']; ?></option>
        <?php endwhile; ?>
    </select>

    <button type="submit" name="add">Tambah User</button>
</form>

<script>
function toggleProdi() {
    var role = document.getElementById("roleDropdown").value;
    var prodi = document.getElementById("prodiSelect");
    prodi.style.display = (role === "prodi" || role === "mahasiswa") ? "inline-block" : "none";
}
</script>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Nama</th>
        <th>Role</th>
        <th>Prodi</th>
        <th>Aksi</th>
    </tr>

    <?php $no = 1; while ($u = mysqli_fetch_assoc($users)) : ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $u['username']; ?></td>
        <td><?= $u['nama']; ?></td>
        <td><?= strtoupper($u['role']); ?></td>
        <td><?= $u['nama_prodi'] ?? '-'; ?></td>
        <td><a href="?delete=<?= $u['id']; ?>" onclick="return confirm('Yakin hapus user?')">Hapus</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<br>
<a href="dashboard.php">Kembali</a>

</body>
</html>
