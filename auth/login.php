<?php
session_start();
include '../config/database.php';



$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$username' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['prodi_id'] = $user['prodi_id'] ?? null;

            switch ($user['role']) {
                case 'admin':
                    header("Location: ../admin/dashboard.php");
                    exit;

                case 'baa':
                    header("Location: ../baa/dashboard.php");
                    exit;

                case 'prodi':
                    header("Location: ../prodi/dashboard.php");
                    exit;

                case 'mahasiswa':
                    header("Location: ../mahasiswa/dashboard.php");
                    exit;
            }
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<style>
body { font-family: Arial; padding: 20px; }
</style>
</head>
<body>

<h2>Login</h2>
<br>
<a href="register.php">Daftar Mahasiswa Baru</a>

<?php if ($error != "") : ?>
    <p style="color:red;"><?= $error; ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button type="submit" name="login">Masuk</button>
</form>

</body>
</html>
