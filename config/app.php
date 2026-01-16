<?php
// config/app.php

// Mulai session untuk autentikasi user
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// URL dasar aplikasi
$base_url = "http://localhost/root/public/";

// Timezone biar tanggal gak kejam ke mahasiswa
date_default_timezone_set('Asia/Jakarta');

// Proteksi basic biar file ini hanya dipanggil lewat include
defined('APP_RUNNING') or define('APP_RUNNING', true);
