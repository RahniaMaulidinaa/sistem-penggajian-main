<!-- Begin Page Content -->
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800"><?php echo $title; ?></h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Data Gaji Pegawai</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Periode</th>
                            <?php if ($is_borongan) : ?>
                                <th>Total Produksi</th>
                                <th>Tarif Borongan</th>
                            <?php else : ?>
                                <th>Gaji Pokok</th>
                                <th>Tunjangan Transportasi</th>
                                <th>Uang Makan</th>
                            <?php endif; ?>
                            <th>Potongan</th>
                            <th>Total Gaji</th>
                            <th>Cetak Slip</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($gaji)) : ?>
                            <?php foreach ($gaji as $g) : ?>
                                <tr>
                                    <td>
                                        <?php if ($is_borongan) : ?>
                                            <?php
                                            $nama_bulan = [
                                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                            ];
                                            $bulan = str_pad($g->bulan_target, 2, '0', STR_PAD_LEFT);
                                            $bulan_nama = $nama_bulan[$bulan] ?? $bulan;
                                            echo "$bulan_nama $g->tahun_target (Minggu ke-$g->mingguke)";
                                            ?>
                                        <?php else : ?>
                                            <?php
                                            $nama_bulan = [
                                                '012025' => 'Januari 2025', '022025' => 'Februari 2025', '032025' => 'Maret 2025', '042025' => 'April 2025',
                                                '052025' => 'Mei 2025', '062025' => 'Juni 2025', '072025' => 'Juli 2025', '082025' => 'Agustus 2025',
                                                '092025' => 'September 2025', '102025' => 'Oktober 2025', '112025' => 'November 2025', '122025' => 'Desember 2025'
                                            ];
                                            echo $nama_bulan[$g->bulan] ?? $g->bulan;
                                            ?>
                                        <?php endif; ?>
                                    </td>
                                    <?php if ($is_borongan) : ?>
                                        <td><?php echo $g->total_produksi ?? 0; ?> unit</td>
                                        <td>Rp. <?php echo number_format($g->tarif_borongan ?? 0, 0, ',', '.'); ?></td>
                                    <?php else : ?>
                                        <td>Rp. <?php echo number_format($g->gaji_pokok ?? 0, 0, ',', '.'); ?></td>
                                        <td>Rp. <?php echo number_format($g->tj_transport ?? 0, 0, ',', '.'); ?></td>
                                        <td>Rp. <?php echo number_format($g->uang_makan ?? 0, 0, ',', '.'); ?></td>
                                    <?php endif; ?>
                                    <td>
                                        <?php
                                        $potongan_gaji = 0;
                                        if (!empty($potongan) && isset($potongan[0]->jml_potongan)) {
                                            $potongan_gaji = ($g->alpha ?? 0) * $potongan[0]->jml_potongan;
                                        }
                                        echo 'Rp. ' . number_format($potongan_gaji, 0, ',', '.');
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if ($is_borongan) {
                                            $total_gaji = (($g->total_produksi ?? 0) * ($g->tarif_borongan ?? 0)) + ($g->tj_transport ?? 0) + ($g->uang_makan ?? 0) - $potongan_gaji;
                                        } else {
                                            $total_gaji = ($g->gaji_pokok ?? 0) + ($g->tj_transport ?? 0) + ($g->uang_makan ?? 0) - $potongan_gaji;
                                        }
                                        echo 'Rp. ' . number_format($total_gaji, 0, ',', '.');
                                        ?>
                                    </td>
                                    <td>
                                        <?php if ($is_borongan) : ?>
                                            <a href="<?php echo base_url('pegawai/data_gaji/cetak_slip/' . $g->id); ?>" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Cetak</a>
                                        <?php else : ?>
                                            <a href="<?php echo base_url('pegawai/data_gaji/cetak_slip/' . $g->id_kehadiran); ?>" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Cetak</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="<?php echo $is_borongan ? 6 : 7; ?>" class="text-center">Data gaji tidak ditemukan. Pastikan data produksi telah diinput oleh admin.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- /.container-fluid -->