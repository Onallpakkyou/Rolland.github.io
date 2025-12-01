<?php
// Koneksi database
$conn = new mysqli("localhost", "root", "310106", "db_sistem_penjualan_motor");
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil nama tabel dari URL
$tabel = isset($_GET['tabel']) ? $_GET['tabel'] : '';
if ($tabel == '') {
    die("Tabel tidak ditemukan.");
}

// Ambil data dari tabel
$result = $conn->query ("SELECT * FROM $tabel");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Data - <?= htmlspecialchars($tabel) ?></title>

    <!-- ====== CSS & JS DataTables + Buttons ====== -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.3/css/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.1.1/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.3/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.1.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- ====== Style ====== -->
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap');

    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: #ffffff;
        color: #222;
    }

    /* ===== Container Utama ===== */
    .container {
        width: 90%;
        margin: 40px auto;
        text-align: center;
    }

    /* ===== Judul ===== */
    h1 {
        color: #007bff;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 3px solid #00b7ff;
        padding-bottom: 10px;
        width: fit-content;
        margin: 0 auto 25px auto;
    }

    /* ===== Area Tombol ===== */
    .top-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .left-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: flex-start;
    }

    /* ===== Tombol ===== */
    .btn-refresh,
    .btn-add,
    .btn-back {
        font-size: 13px;
        padding: 7px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: 0.3s;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-refresh { background: #17a2b8; color: #fff; }
    .btn-refresh:hover { background: #00c8ff; }

    .btn-add { background: #28a745; color: white; }
    .btn-add:hover { background: #34d058; }

    .btn-back {
        background: #007bff;
        color: #fff;
        display: inline-block;
        margin: 25px auto 0 auto;
    }
    .btn-back:hover { background: #00b7ff; }

    /* ===== Wrapper Tabel ===== */
    .table-wrapper {
        width: fit-content;
        max-width: 100%;
        margin: 0 auto;
        max-height: 480px;
        overflow: auto;
        border-radius: 10px;
        border: 1px solid #ddd;
        background-color: #ffffff;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 8px;
        position: relative;
    }

    /* ===== DataTables Buttons Sticky ===== */
    .dt-buttons {
        position: sticky;
        top: 0;
        background: #ffffff;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
        z-index: 10;
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    /* ===== Tombol Ekspor ===== */
    .buttons-excel {
        background: #28a745 !important;
        color: white !important;
        border-radius: 6px !important;
        border: none !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
    }
    .buttons-excel:hover { background: #34d058 !important; }

    .buttons-pdf {
        background: #dc3545 !important;
        color: white !important;
        border-radius: 6px !important;
        border: none !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
    }
    .buttons-pdf:hover { background: #ff4b5c !important; }

    .buttons-print {
        background: #007bff !important;
        color: white !important;
        border-radius: 6px !important;
        border: none !important;
        padding: 6px 12px !important;
        font-size: 13px !important;
    }
    .buttons-print:hover { background: #00b7ff !important; }

    /* ===== Tabel ===== */
    table {
        border-collapse: collapse;
        font-size: 13px;
        background: #fff;
        margin: 0 auto;
        width: 100%;
        table-layout: auto;
    }

    th {
        background: linear-gradient(135deg, #007bff, #00b7ff);
        color: #fff;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 13px;
        text-align: center;   /* Semua header rata tengah */
        vertical-align: middle;
        position: sticky;
        top: 0;
        z-index: 5;
        padding: 8px 10px;
    }

    td {
        border: 1px solid #ddd;
        padding: 8px 10px;
        text-align: center;  /* Semua isi sel rata tengah */
        vertical-align: middle;
        white-space: nowrap;
    }

    tr:nth-child(even) { background: #f8faff; }
    tr:hover { background: rgba(0, 183, 255, 0.15); transition: 0.3s; }

    /* ===== Filter Kolom ===== */
    tfoot input {
        width: 100%;
        padding: 4px;
        box-sizing: border-box;
        border-radius: 4px;
        border: 1px solid #aaa;
        background-color: #f5f5f5;
        color: #000;
        font-size: 12px;
    }

    /* Scrollbar */
    .table-wrapper::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }
    .table-wrapper::-webkit-scrollbar-thumb {
        background: #00b7ff;
        border-radius: 10px;
    }
    .table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #007bff;
    }

    /* Semua isi td termasuk input, select, button, textarea */
    td, td * {
        text-align: center !important;
        margin: 0 auto;       /* untuk elemen block seperti select atau button */
        vertical-align: middle !important;
    }

    /* Header tetap rata tengah */
    th {
        text-align: center !important;
        vertical-align: middle !important;
    }

    </style>


</head>
<body>

<div class="container">
    <h1>📊 Data Tabel: <?= htmlspecialchars($tabel) ?></h1>

    <div class="top-controls">
        <div class="left-buttons sticky-buttons">
            <a href="lihat_tabel.php?tabel=<?= urlencode($tabel) ?>" class="btn-refresh">🔄 Refresh Tabel</a>
            <button id="addRowBtn" class="btn-add">➕ Tambah Data Baru</button>
        </div>
        <div id="info-row-count"></div>
    </div>

    <div class="table-wrapper">
        <table id="dataTabel">
            <thead>
                <tr>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $fields = $result->fetch_fields();
                        foreach ($fields as $field) {
                            echo "<th>" . htmlspecialchars($field->name) . "</th>";
                        }
                        echo "</tr></thead><tfoot><tr>";
                        foreach ($fields as $field) {
                            echo "<th><input type='text' placeholder='Cari " . htmlspecialchars($field->name) . "' /></th>";
                        }
                        echo "</tr></tfoot><tbody>";
                        $result->data_seek(0);
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            foreach ($row as $val) {
                                echo "<td>" . htmlspecialchars($val) . "</td>";
                            }
                            echo "</tr>";
                        }
                    } else {
                        echo "<th>Tidak ada data di tabel ini.</th>";
                    }
                    ?>
                </tbody>
        </table>
        </div>

    <div style="margin-top: 20px; text-align: center;">
        <a href="index.php" class="btn">⬅️ Kembali ke Daftar Tabel</a>
    </div>
</div>

<!-- ✅ Tambahkan di bawah ini -->
<!-- CDN DataTables dan Buttons -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
        // === Tambah Data Baru ===
    $('#addRowBtn').on('click', function() {
        let tabel = '<?= $tabel ?>';
        let kolomCount = $('#dataTabel thead th').length;

        // Buat baris input baru
        let newRow = '<tr id="newRow">';
        $('#dataTabel thead th').each(function() {
            let kolom = $(this).text();
            newRow += `<td contenteditable="true" data-column="${kolom}"></td>`;
        });
        newRow += `<td><button id="saveNewRow" class="btn-add">✔ Simpan</button></td></tr>`;
        $('#dataTabel tbody').prepend(newRow);

        $(this).prop('disabled', true); // Nonaktifkan tombol tambah sementara
    });

    // === Simpan Data Baru ke Database ===
    $(document).on('click', '#saveNewRow', function() {
        let newData = {};
        $('#newRow td[contenteditable="true"]').each(function() {
            let kolom = $(this).data('column');
            let nilai = $(this).text().trim();
            newData[kolom] = nilai;
        });

        $.ajax({
            url: 'insert_data.php',
            method: 'POST',
            data: {
                tabel: '<?= $tabel ?>',
                data: newData
            },
            success: function(response) {
                alert(response);
                location.reload(); // Refresh tabel setelah disimpan
            },
            error: function() {
                alert("Gagal menyimpan data!");
            }
        });
    });

    // Aktifkan DataTables pada tabel kamu
    $('table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Data_<?= htmlspecialchars($tabel) ?>',
                text: '💾 Download Excel',
                className: 'buttons-excel'
            },
            {
                extend: 'pdfHtml5',
                title: 'Data_<?= htmlspecialchars($tabel) ?>',
                text: '📄 Download PDF',
                className: 'buttons-pdf',
                orientation: 'landscape',
                pageSize: 'A4',
                customize: function (doc) {
                    // 🔹 Supaya seluruh isi tabel muat di halaman PDF
                    doc.defaultStyle.fontSize = 7;
                    doc.styles.tableHeader.fontSize = 8;
                    doc.pageMargins = [10, 10, 10, 10];
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                }
            },
            {
                extend: 'print',
                text: '🖨️ Print Data',
                className: 'buttons-print',
                customize: function (win) {
                    // 🔹 Supaya print terlihat lebih kecil dan rapi
                    $(win.document.body).find('table')
                        .css('font-size', '9px')
                        .css('width', '100%');
                    $(win.document.body).find('h1')
                        .css('text-align', 'center')
                        .css('font-size', '16px');
                }
            }
        ],
        language: {
            search: "🔍 Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            zeroRecords: "Tidak ada data ditemukan",
            infoEmpty: "Tidak ada data tersedia"
        }
    });

    // 🔹 Tambahkan warna pada tombol
    $('button.buttons-excel').css({
        'background': '#00c853',
        'color': 'white',
        'border-radius': '8px',
        'border': 'none',
        'margin-right': '6px',
        'padding': '8px 15px'
    });
    $('button.buttons-pdf').css({
        'background': '#d32f2f',
        'color': 'white',
        'border-radius': '8px',
        'border': 'none',
        'margin-right': '6px',
        'padding': '8px 15px'
    });
    $('button.buttons-print').css({
        'background': '#0288d1',
        'color': 'white',
        'border-radius': '8px',
        'border': 'none',
        'margin-right': '6px',
        'padding': '8px 15px'
    });
    // === Inline Edit - Update ke Database ===
$('#dataTabel').on('blur', 'td[contenteditable="true"]', function() {
    let id = $(this).data('id');
    let kolom = $(this).data('column');
    let nilai = $(this).text().trim();

    $.ajax({
        url: 'update_data.php',
        method: 'POST',
        data: {
            tabel: '<?= $tabel ?>',
            id: id,
            kolom: kolom,
            nilai: nilai
        },
        success: function(response) {
            console.log(response);
            // Notifikasi kecil di browser
            let alertBox = $('<div>')
                .text(response)
                .css({
                    position: 'fixed',
                    top: '20px',
                    right: '20px',
                    background: '#00b7ff',
                    color: '#000',
                    padding: '10px 15px',
                    borderRadius: '6px',
                    boxShadow: '0 0 10px rgba(0,0,0,0.3)'
                })
                .appendTo('body');
            setTimeout(() => alertBox.fadeOut(400, () => alertBox.remove()), 2000);
        }
    });
});

});
</script>