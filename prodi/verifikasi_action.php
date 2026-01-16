<?php
require_once "../middleware/auth_middleware.php";
require_once "../config/database.php";

checkAuth();
checkRole(['prodi']);

if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    die("Parameter tidak lengkap");
}

$id = intval($_GET['id']);
$aksi = $_GET['aksi'];

if ($aksi === 'approve') {
    $query = "UPDATE yudisium 
              SET status_validasi_prodi = 1 
              WHERE id = $id";
} elseif ($aksi === 'reject') {
    $query = "UPDATE yudisium 
              SET status_validasi_prodi = 2 
              WHERE id = $id";
} else {
    die("Aksi tidak valid");
}

mysqli_query($conn, $query);

header("Location: verifikasi.php");
exit;
