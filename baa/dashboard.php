<?php
require_once "../middleware/auth_middleware.php";
checkAuth();
checkRole(['baa']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard BAA</title>
</head>
<body>

<h2>Dashboard BAA</h2>

<ul>
    <li><a href="verifikasi.php">Finalisasi Yudisium</a></li>
    <li><a href="../auth/logout.php">Logout</a></li>
</ul>

</body>
</html>
