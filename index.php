<?php
// Koneksi ke database
$conn = new mysqli("localhost", "root", "310106", "db_sistem_penjualan_motor");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Daftar tabel dan kolom
$tabels = [
    'Pelanggan' => ['id_pelanggan', 'nama', 'nik', 'alamat', 'kota', 'provinsi', 'kode_pos', 'no_telepon', 'email', 'tanggal_daftar', 'tipe_pelanggan'],
    'Merek' => ['id_merek', 'nama_merek', 'negara_asal', 'keterangan'],
    'ModelMotor' => ['id_model', 'id_merek', 'nama_model', 'jenis_motor', 'kapasitas_cc', 'bahan_bakar', 'warna_standaar', 'harga_dasar'],
    'Motor' => ['id_motor', 'id_model', 'no_rangka', 'no_mesin', 'warna', 'tahun_produksi', 'status_unit', 'lokasi_gudang', 'harga_jual', 'tanggal_masuk'],
    'Pemasok' => ['id_pemasok', 'nama_pemasok', 'nama_kontak', 'no_telepon', 'email', 'alamat'],
    'PesananPembelian' => ['id_pesanan', 'id_pemasok', 'tanggal_pesanan', 'status_pesanan', 'total_pesanan', 'dibuat_oleh'],
    'ItemPembelian' => ['id_item', 'id_barang', 'id_model', 'jumlah', 'harga_satuan'],
    'Penjualan' => ['id_penjualan', 'no_faktur', 'id_pelanggan', 'id_sales', 'tanggal_pesan', 'tanggal_kirim', 'status_pesanan', 'total_harga', 'diskon', 'pajak'],
    'ItemPenjualan' => ['id_item_penjualan', 'id_penjualan', 'id_motor', 'id_model', 'jumlah', 'harga_satuan', 'subtotal'],
    'Karyawan' => ['id_karyawan', 'nama', 'nik', 'no_telepon', 'email', 'tanggal_masuk'],
    'Pembayaran' => ['id_pembayaran', 'id_penjualan', 'id_metode', 'tanggal_pembayaran', 'jumlah_bayar', 'no_referensi', 'status_pembayaran'],
    'MetodePembayaran' => ['id_metode', 'nama_metode', 'keterangan'],
    'Faktur' => ['id_faktur', 'id_penjualan', 'tanggal_faktur', 'jatuh_tempo', 'total_faktur', 'status_faktur'],
    'TukarTambah' => ['id_tukar', 'id_penjualan', 'motor_lama', 'nilai_tukar'],
    'RiwayatStok' => ['id_log', 'id_motor', 'jenis_perubahan', 'jumlah', 'tanggal', 'sumber', 'keterangan'],
    'Pengguna' => ['id_pengguna', 'nama_pengguna', 'kata_sandi', 'peran', 'id_karyawan']
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Penjualan Motor</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Daftar Tabel Database </h1>
            <p>Sistem Penjualan Motor - By Onalld Adonara Tengah</p>
        </header>

        <div class="card">
            <h2>Tabel yang tersedia:</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Tabel</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (!empty($tabels)) {
                        $no = 1;
                        foreach ($tabels as $nama_tabel => $kolom) {
                            echo "<tr>
                                <td>{$no}</td>
                                <td>{$nama_tabel}</td>
                                <td><a href='lihat_tabel.php?tabel={$nama_tabel}' class='btn'>Lihat Data</a></td>
                            </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='3' class='empty'>Tidak ada tabel ditemukan di database.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
