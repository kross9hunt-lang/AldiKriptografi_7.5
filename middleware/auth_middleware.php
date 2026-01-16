<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkAuth() {
    if (!isset($_SESSION['role'])) {
        header("Location: ../auth/login.php");
        exit;
    }
}

function checkRole($allowedRoles = []) {
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: ../auth/login.php");
        exit;
    }
}
