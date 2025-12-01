<?php
$servername = "localhost";
$username = "root";
$password = "310106";
$database = "db_sistem_penjualan_motor";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>