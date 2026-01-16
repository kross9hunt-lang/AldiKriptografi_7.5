<?php
require_once "../middleware/auth_middleware.php";
require_once "../config/database.php";

checkAuth();
checkRole(['baa']);

if (!isset($_POST['id'], $_POST['nomor_sk'], $_POST['tgl_sk'])) {
    die("Data tidak lengkap");
}

$id = intval($_POST['id']);
$nomor_sk = $_POST['nomor_sk'];
$tgl_sk = $_POST['tgl_sk'];

$query = "
UPDATE yudisium SET
    nomor_sk = '$nomor_sk',
    tgl_sk = '$tgl_sk',
    status_final_baa = 1
WHERE id = $id
";

mysqli_query($conn, $query);

header("Location: verifikasi.php");
exit;
