<!-- Begin Page Content -->
<div class="container-fluid mt-4">
  <div class="row">
    <!-- Sidebar sudah diasumsikan fixed di luar grid ini -->
    <div class="col-md-10 offset-md-2">
      <div class="card">
        <div class="card-header">
          <h4 class="text-center">Data Gaji Pegawai</h4>
        </div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th>Periode</th>
							<?php if ($jenis_gaji === 'Borongan'): ?>
								<th>Total Produksi</th>
								<th>Tarif Borongan</th>
							<?php else : ?>
								<th>Gaji Pokok</th>
								<th>Tunjangan Transportasi</th>
								<th>Uang Makan</th>
								<th>Potongan</th>
							<?php endif; ?>
							<th>Total Gaji</th>
							<th>Cetak Slip</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($gaji)) : ?>
							<?php foreach ($gaji as $g) : ?>
								<tr>
									<td>
										<?php if ($jenis_gaji === 'Borongan'): ?>
											<?= $g->periode ?>
										<?php else : ?>
											<?php
											$nama_bulan = [
												'012025' => 'Januari 2025',
												'022025' => 'Februari 2025',
												'032025' => 'Maret 2025',
												'042025' => 'April 2025',
												'052025' => 'Mei 2025',
												'062025' => 'Juni 2025',
												'072025' => 'Juli 2025',
												'082025' => 'Agustus 2025',
												'092025' => 'September 2025',
												'102025' => 'Oktober 2025',
												'112025' => 'November 2025',
												'122025' => 'Desember 2025'
											];
											echo $nama_bulan[$g->bulan] ?? $g->bulan;
											?>
										<?php endif; ?>
									</td>
									<?php if ($jenis_gaji === 'Borongan'): ?>
										<td><?php echo $g->total_produksi ?? 0; ?> unit</td>
										<td>Rp <?= number_format($g->tarif, 0, ',', '.') ?></td>
									<?php else : ?>
										<td>Rp. <?php echo number_format($g->gaji_pokok ?? 0, 0, ',', '.'); ?></td>
										<td>Rp. <?php echo number_format($g->tj_transport ?? 0, 0, ',', '.'); ?></td>
										<td>Rp. <?php echo number_format($g->uang_makan ?? 0, 0, ',', '.'); ?></td>
										<td>
											<?php
											$potongan_gaji = 0;
											if (!empty($potongan) && isset($potongan[0]->jml_potongan)) {
												$potongan_gaji = ($g->alpha ?? 0) * $potongan[0]->jml_potongan;
											}
											echo 'Rp. ' . number_format($potongan_gaji, 0, ',', '.');
											?>
										</td>
									<?php endif; ?>
									<td>
										<?php
										if ($jenis_gaji === 'Borongan') {
											$total_gaji = ($g->total_produksi) * ($g->tarif);
										} else {
											$total_gaji = ($g->gaji_pokok ?? 0) + ($g->tj_transport ?? 0) + ($g->uang_makan ?? 0) - $potongan_gaji;
										}
										echo 'Rp. ' . number_format($total_gaji, 0, ',', '.');
										?>
									</td>
									<td>
										<!-- <?php var_dump($g); ?> -->
										<a href="<?php echo base_url('hrd/data_gaji/cetak_slip/' . $g->id_kehadiran); ?>" class="btn btn-sm btn-primary"><i class="fas fa-print"></i> Cetak</a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="<?php echo $jenis_gaji === 'Borongan' ? 6 : 7; ?>" class="text-center">Data gaji tidak ditemukan. Pastikan data produksi telah diinput oleh admin.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<!-- /.container-fluid -->
