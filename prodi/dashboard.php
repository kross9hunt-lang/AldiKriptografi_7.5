<?php
include '../middleware/auth_middleware.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Prodi</title>
    <link rel="stylesheet" href="../public/assets/css/tailwind.css">
</head>
<body class="p-6">
    <h2 class="text-xl font-bold mb-4">Dashboard Prodi</h2>

    <nav class="space-x-4">
        <a href="verifikasi.php" class="text-blue-600 font-semibold">Verifikasi Mahasiswa</a>
        <a href="../auth/logout.php" class="text-red-600 font-semibold">Logout</a>
    </nav>
</body>
</html>
