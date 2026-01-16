<?php
require_once "../middleware/auth_middleware.php";
require_once "../config/database.php";

checkAuth();
checkRole(['mahasiswa']);
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Mahasiswa</title>
</head>
<body>
    <h2>Selamat Datang Mahasiswa</h2>

    <ul>
        <li><a href="pengajuan.php">Ajukan Yudisium</a></li>
        <li><a href="status.php">Status Yudisium</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>

</body>
</html>
