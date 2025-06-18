<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title?></title>
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
    </style>
</head>
<body>
    <center>
        <h1>KONVEKSI KAMPOENG BUSANA</h1>
        <h2>Laporan Gaji Pegawai Borongan</h2>
        <hr>
    </center>

    <table>
        <tr>
            <td>Bulan</td>
            <td>:</td>
            <td>
                <?php
                $nama_bulan = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                $bulan = isset($bulan) ? $bulan : date('m');
                echo isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : $bulan;
                ?>
            </td>
        </tr>
        <tr>
            <td>Tahun</td>
            <td>:</td>
            <td><?php echo isset($tahun) ? $tahun : date('Y')?></td>
        </tr>
        <tr>
            <td>Minggu</td>
            <td>:</td>
            <td><?php echo isset($minggu) ? $minggu : 1?></td>
        </tr>
    </table>

    <?php if (!empty($cetak_gaji)) { ?>
        <table class="table table-bordered table-striped">
            <tr>
                <th class="text-center">No</th>
                <th class="text-center">NIK</th>
                <th class="text-center">Nama Pegawai</th>
                <th class="text-center">Jenis Kelamin</th>
                <th class="text-center">Jabatan</th>
                <th class="text-center">Gaji Pokok</th>
                <th class="text-center">Tj. Transport</th>
                <th class="text-center">Uang Makan</th>
                <th class="text-center">Total Produksi</th>
                <th class="text-center">Tarif Borongan</th>
                <th class="text-center">Potongan (Alpha)</th>
                <th class="text-center">Total Upah</th>
            </tr>
			<?php 
$no = 1;
foreach ($cetak_gaji as $g) : 
    $potongan_gaji = 0;
    if (!empty($potongan) && isset($potongan[0]->jml_potongan)) {
        $potongan_gaji = ($g->alpha ?? 0) * $potongan[0]->jml_potongan;
    }
    $total_produksi = $g->total_produksi ?? 0;
    $tarif_borongan = $g->tarif_borongan ?? 0;
    $gaji_pokok = $g->gaji_pokok ?? 0;
    $tj_transport = $g->tj_transport ?? 0;
    $uang_makan = $g->uang_makan ?? 0;

    $total_upah = ($total_produksi * $tarif_borongan) + $gaji_pokok + $tj_transport + $uang_makan - $potongan_gaji;

    // Hanya tampilkan jika total gaji lebih dari 0
    if ($total_upah > 0):
?>
<tr>
    <td><?php echo $no++ ?></td>
    <td><?php echo $g->nik ?></td>
    <td><?php echo $g->nama_pegawai ?></td>
    <td><?php echo $g->jenis_kelamin ?></td>
    <td><?php echo $g->nama_jabatan ?></td>
    <td>Rp. <?php echo number_format($gaji_pokok, 0, ',', '.') ?></td>
    <td>Rp. <?php echo number_format($tj_transport, 0, ',', '.') ?></td>
    <td>Rp. <?php echo number_format($uang_makan, 0, ',', '.') ?></td>
    <td><?php echo $total_produksi ?> unit</td>
    <td>Rp. <?php echo number_format($tarif_borongan, 0, ',', '.') ?></td>
    <td>Rp. <?php echo number_format($potongan_gaji, 0, ',', '.') ?></td>
    <td>Rp. <?php echo number_format($total_upah, 0, ',', '.') ?></td>
</tr>
<?php 
    endif;
endforeach; 
?>
		</table>
	<?php } ?>

    <script type="text/javascript">
        window.print();
    </script>
</body>
</html>
