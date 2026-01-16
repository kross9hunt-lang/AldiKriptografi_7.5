<?php
require_once '../config/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username/npm'];
    $nama = $_POST['nama'];
    $role = $_POST['role'];
    $prodi_id = $_POST['prodi_id'] ?: null;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password_hash, nama, role, prodi_id)
            VALUES (?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $username, $password, $nama, $role, $prodi_id);

    if ($stmt->execute()) {
        header("Location: user_management.php?success=1");
    } else {
        echo "Gagal menyimpan user: " . $conn->error;
    }
}
