<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <style type="text/css">
        body {
            font-family: Arial;
            color: black;
        }
        table.table-bordered {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table.table-bordered th, table.table-bordered td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        hr {
            width: 50%;
            border-width: 5px;
            color: black;
        }
        h1, h2 {
            text-align: center;
        }
        .error-message {
            text-align: center;
            color: red;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <center>
        <h1>KONVEKSI KAMPOENG BUSANA</h1>
        <h2><?php echo $title; ?></h2>
        <hr>
    </center>

    <?php
    $nama_bulan = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $bulan_nama = isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan;
    ?>

    <table>
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td><?php echo $bulan_nama . ' ' . $tahun . ' (Minggu ke-' . $minggu . ')'; ?></td>
        </tr>
    </table>

    <?php if (!empty($cetak_gaji)) { ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIK</th>
                    <th>Nama Pegawai</th>
                    <th>Jenis Kelamin</th>
                    <th>Jabatan</th>
                    <th>Gaji Pokok</th>
                    <th>Tarif Borongan</th>
                    <th>Total Produksi Mingguan</th>
                    <th>Tj. Transport</th>
                    <th>Uang Makan</th>
                    <th>Potongan</th>
                    <th>Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                $potongan = !empty($potongan) ? $potongan[0]->jml_potongan : 0;
                foreach ($cetak_gaji as $g) :
                    $potongan_gaji = $g->alpha * $potongan;
                    $total_produksi = isset($g->total_produksi) ? $g->total_produksi : 0;
                    $gaji_borongan = $total_produksi * $g->tarif_borongan;
                    $total_gaji = $gaji_borongan + $g->tj_transport + $g->uang_makan - $potongan_gaji;
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $g->nik; ?></td>
                        <td><?php echo $g->nama_pegawai; ?></td>
                        <td><?php echo $g->jenis_kelamin; ?></td>
                        <td><?php echo $g->nama_jabatan; ?></td>
                        <td>Rp. <?php echo number_format($g->gaji_pokok, 0, ',', '.'); ?></td>
                        <td>Rp. <?php echo number_format($g->tarif_borongan, 0, ',', '.'); ?> / Pcs</td>
                        <td><?php echo $total_produksi; ?></td>
                        <td>Rp. <?php echo number_format($g->tj_transport, 0, ',', '.'); ?></td>
                        <td>Rp. <?php echo number_format($g->uang_makan, 0, ',', '.'); ?></td>
                        <td>Rp. <?php echo number_format($potongan_gaji, 0, ',', '.'); ?></td>
                        <td>Rp. <?php echo number_format($total_gaji, 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="error-message">
            Data gaji borongan untuk periode <?php echo $bulan_nama . ' ' . $tahun . ' (Minggu ke-' . $minggu . ')'; ?> tidak ditemukan.
        </p>
    <?php } ?>

    <script type="text/javascript">
        window.print();
    </script>
</body>
</html>