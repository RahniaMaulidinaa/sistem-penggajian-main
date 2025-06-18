<!DOCTYPE html>
<html>

<head>
	<title>Slip Gaji Borongan</title>
	<style type="text/css">
		body {
			font-family: Arial, Helvetica, sans-serif;
			color: #333;
			margin: 20px;
		}

		.container {
			max-width: 800px;
			margin: 0 auto;
		}

		h1 {
			font-size: 24px;
			text-align: center;
			color: #003087;
			margin-bottom: 5px;
		}

		h2 {
			font-size: 18px;
			text-align: center;
			color: #003087;
			margin-top: 0;
		}

		hr {
			width: 60%;
			border: 2px solid #003087;
			margin: 15px auto;
		}

		.info-table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 20px;
		}

		.info-table td {
			padding: 8px;
			border: 1px solid #ddd;
		}

		.info-table td:first-child {
			width: 30%;
			font-weight: bold;
			background-color: #f8f9fa;
		}

		.main-table {
			width: 100%;
			border-collapse: collapse;
			margin-top: 20px;
		}

		.main-table th {
			background-color: #e3f2fd;
			color: #003087;
			padding: 10px;
			border: 1px solid #bcd4e6;
			font-weight: bold;
			text-align: left;
		}

		.main-table td {
			padding: 10px;
			border: 1px solid #bcd4e6;
			text-align: left;
		}

		.main-table .total {
			background-color: #f0f7ff;
			font-weight: bold;
			color: #003087;
		}

		.signature-table {
			width: 100%;
			margin-top: 50px;
			border-collapse: collapse;
		}

		.signature-table td {
			vertical-align: top;
			padding: 10px;
			text-align: center;
		}

		.signature-table .pegawai,
		.signature-table .hrd {
			width: 50%;
		}

		.signature-line {
			border-bottom: 1px solid #333;
			width: 150px;
			margin: 10px auto;
		}

		@media print {
			body {
				margin: 0;
			}

			.container {
				max-width: 100%;
			}
		}
	</style>
</head>

<body>
	<div class="container">
		<h1>KONVEKSI KAMPOENG BUSANA</h1>
		<h2>Slip Gaji Pegawai Borongan</h2>
		<hr>

		<table class="info-table">
			<tr>
				<td>NIK</td>
				<td><?= $gaji->nik ?></td>
			</tr>
			<tr>
				<td>Nama Pegawai</td>
				<td><?= $gaji->nama_pegawai ?></td>
			</tr>
			<tr>
				<td>Jabatan</td>
				<td><?= $gaji->jabatan ?></td>
			</tr>
			<tr>
				<td>Jenis Gaji</td>
				<td><?= $gaji->jenis_gaji ?></td>
			</tr>
			<tr>
				<td>Periode</td>
				<td>
					<?php
					$periode = explode('-', $gaji->periode);
					$bulan = $periode[1] ?? '';
					$tahun = $periode[0] ?? '';
					$nama_bulan = [
						'01' => 'Januari',
						'02' => 'Februari',
						'03' => 'Maret',
						'04' => 'April',
						'05' => 'Mei',
						'06' => 'Juni',
						'07' => 'Juli',
						'08' => 'Agustus',
						'09' => 'September',
						'10' => 'Oktober',
						'11' => 'November',
						'12' => 'Desember'
					];
					echo $nama_bulan[$bulan] . ' ' . $tahun;
					?>
				</td>
			</tr>
		</table>

		<table class="main-table">
			<tr>
				<th>Total Produksi</th>
				<th>Tarif per Unit</th>
				<th>Total Gaji</th>
			</tr>
			<tr>
				<td><?= $gaji->total_produksi ?> Unit</td>
				<td>Rp. <?= number_format($gaji->tarif, 0, ',', '.') ?></td>
				<td>Rp. <?= number_format($gaji->total_gaji, 0, ',', '.') ?></td>
			</tr>
			<tr class="total">
				<td colspan="2">Total Gaji Diterima</td>
				<td>Rp. <?= number_format($gaji->total_gaji, 0, ',', '.') ?></td>
			</tr>
		</table>

		<table class="signature-table">
			<tr>
				<td class="pegawai">
					<p>Pegawai</p>
					<br><br>
					<p><strong><?= $gaji->nama_pegawai ?></strong></p>
				</td>
				<td class="hrd">
					<p>Bandung, <?= date('d M Y') ?> <br> Finance</p>
					<br><br>
					<div class="signature-line"></div>
				</td>
			</tr>
		</table>
	</div>

	<script>
		window.print();
	</script>
</body>

</html>
