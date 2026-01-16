<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_admin() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        header("Location: ../auth/login.php");
        exit;
    }
}

function require_baa() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'baa') {
        header("Location: ../auth/login.php");
        exit;
    }
}

function require_prodi() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'prodi') {
        header("Location: ../auth/login.php");
        exit;
    }
}

function require_mahasiswa() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'mahasiswa') {
        header("Location: ../auth/login.php");
        exit;
    }
}
