<?php
require_once "../config/database.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nama = $_POST['nama'];
    $prodi_id = $_POST['prodi_id'];

    // Cek username
    $cek = mysqli_query($conn, "SELECT id FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        die("Username sudah dipakai.");
    }

    // Insert users
    mysqli_query($conn, "
        INSERT INTO users(username, password_hash, nama, role, prodi_id)
        VALUES('$username', '$password', '$nama', 'mahasiswa', $prodi_id)
    ");

    $user_id = mysqli_insert_id($conn);

    // Insert mahasiswa
    mysqli_query($conn, "
        INSERT INTO mahasiswa(npm, user_id, nama, prodi_id)
        VALUES('$username', $user_id, '$nama', $prodi_id)
    ");

    echo "<script>alert('Registrasi berhasil, silakan login');window.location='login.php';</script>";
    exit;
}

$prodi = mysqli_query($conn, "SELECT * FROM prodi");
?>

<!DOCTYPE html>
<html>
<head>
<title>Registrasi Mahasiswa</title>
</head>
<body>

<h2>Registrasi Mahasiswa</h2>

<form method="POST">
    <input type="text" name="username" placeholder="NPM / Username" required><br><br>
    <input type="text" name="nama" placeholder="Nama Lengkap" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <select name="prodi_id" required>
        <option value="">Pilih Prodi</option>
        <?php while ($p = mysqli_fetch_assoc($prodi)) : ?>
            <option value="<?= $p['id']; ?>"><?= $p['nama_prodi']; ?></option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <button type="submit">Daftar</button>
</form>

</body>
</html>
