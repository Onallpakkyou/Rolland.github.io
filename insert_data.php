<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "310106", "db_sistem_penjualan_motor");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$tabel = $_POST['tabel'];
$data = $_POST['data'];

if (!$tabel || !$data) {
    die("Data tidak lengkap.");
}

// Ambil kolom dan nilai dari data POST
$kolom = implode(", ", array_keys($data));
$nilai = "'" . implode("', '", array_map([$conn, 'real_escape_string'], array_values($data))) . "'";

$sql = "INSERT INTO $tabel ($kolom) VALUES ($nilai)";

if ($conn->query($sql) === TRUE) {
    echo "✅ Data berhasil ditambahkan!";
} else {
    echo "❌ Gagal menambah data: " . $conn->error . " | SQL: " . $sql;
}

$conn->close();
?>
