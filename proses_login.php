<?php
// proses_login.php
session_start();

// KONFIGURASI LOGIN ADMIN (bisa diubah sesuai kebutuhan)
$admin_username = "Onalld";
$admin_password = "310106";

// CEK FORM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_login'] = true;
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['error'] = "Username atau password salah!";
        header("Location: admin_login.php");
        exit;
    }
}
?>
