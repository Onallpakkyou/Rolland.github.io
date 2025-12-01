<?php
$conn = new mysqli("localhost", "root", "310106", "db_sistem_penjualan_motor");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$tabel = $_POST['tabel'];
$id = $_POST['id'];
$kolom = $_POST['kolom'];
$nilai = $_POST['nilai'];

// Kolom pertama dianggap sebagai primary key (id)
$id_field = $conn->query("SHOW KEYS FROM $tabel WHERE Key_name = 'PRIMARY'")->fetch_assoc()['Column_name'];

$sql = "UPDATE $tabel SET $kolom = ? WHERE $id_field = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nilai, $id);

if ($stmt->execute()) {
    echo "✅ Data berhasil diperbarui";
} else {
    echo "❌ Gagal memperbarui data: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
