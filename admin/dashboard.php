<?php
include '../middleware/auth_middleware.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Selamat datang, <?= htmlspecialchars($_SESSION['nama']) ?> (Admin)</h2>
    <ul>
        <li><a href="prodi.php">Kelola Program Studi</a></li>
        <li><a href="user_management.php">Kelola User</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</body>
</html>
