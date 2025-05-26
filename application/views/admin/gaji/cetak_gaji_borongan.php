<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2><?php echo $title; ?></h2>
    <?php
    $bulan_list = [
        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
    ];
    $bulan_nama = isset($bulan_list[$bulan]) ? $bulan_list[$bulan] : $bulan;
    ?>
    <p style="text-align: center;">Periode: <?php echo $bulan_nama . ' ' . $tahun . ' (Minggu ke-' . $minggu . ')'; ?></p>

    <table>
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
            <?php foreach ($potongan as $p) {
                $alpha = $p->jml_potongan;
            } ?>
            <?php $no = 1; foreach ($cetak_gaji as $g) : ?>
                <?php 
                    $potongan = $g->alpha * $alpha;
                    $total_produksi = isset($g->total_produksi) ? $g->total_produksi : 0;
                    $gaji_borongan = $total_produksi * $g->tarif_borongan;
                    $total_gaji = $gaji_borongan + $g->tj_transport + $g->uang_makan - $potongan;
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
                    <td>Rp. <?php echo number_format($potongan, 0, ',', '.'); ?></td>
                    <td>Rp. <?php echo number_format($total_gaji, 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        window.print();
    </script>
</body>
</html>