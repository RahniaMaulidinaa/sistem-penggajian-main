<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $title ?></title>
	<link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
	<style>
		#sidebar {
			width: 220px;
			position: fixed;
			top: 0;
			left: 0;
			height: 100vh;
			background-color: #0056b3;
			color: white;
			padding-top: 80px;
		}

		#content {
			margin-left: 230px;
			padding: 20px;
		}
	</style>
</head>

<body>

	<!-- Sidebar -->
	<div id="sidebar" class="position-fixed">
		<ul class="nav flex-column pt-4">
			<li class="nav-item"><a href="<?= base_url('hrd/dashboard') ?>" class="nav-link text-white"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard HRD</a></li>
			<li class="nav-item"><a href="<?= base_url('hrd/data_gaji') ?>" class="nav-link text-white"><i class="fas fa-users-cog mr-2"></i> Data Gaji</a></li>
			<li class="nav-item"><a href="<?= base_url('hrd/laporan_upah') ?>" class="nav-link text-white"><i class="fas fa-file-invoice-dollar mr-2"></i> Laporan Upah Bulanan</a></li>
			<li class="nav-item"><a href="<?= base_url('hrd/laporan_upah_borongan') ?>" class="nav-link text-white"><i class="fas fa-clipboard-list mr-2"></i> Laporan Upah Borongan</a></li>
			<li class="nav-item"><a href="<?= base_url('hrd/ganti_password') ?>" class="nav-link text-white"><i class="fas fa-key mr-2"></i> Ganti Password</a></li>
			<li class="nav-item"><a href="<?= base_url('login/logout') ?>" class="nav-link text-white"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a></li>
		</ul>
	</div>

	<!-- Content -->
	<div id="content">
		<div class="container-fluid">
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
				<div id="date"></div>
				<script>
					const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
					const today = new Date();
					document.getElementById("date").innerHTML = ${today.getDate()} ${bulan[today.getMonth()]} ${today.getFullYear()};
				</script>
			</div>

			<!-- Info Boxes -->
			<div class="row">
				<?php
				$box_data = [
					['Jumlah Pegawai', $pegawai, 'info', 'users'],
					['Jumlah HRD', $hrd, 'success', 'user-cog'],
					['Jumlah Jabatan', $jabatan, 'primary', 'briefcase'],
					['Data Kehadiran', $kehadiran, 'warning', 'calendar-check']
				];
				foreach ($box_data as [$label, $value, $color, $icon]) :
				?>
					<div class="col-xl-3 col-md-6 mb-4">
						<div class="card border-left-<?= $color ?> shadow h-100 py-2">
							<div class="card-body">
								<div class="row no-gutters align-items-center">
									<div class="col mr-2">
										<div class="text-xs font-weight-bold text-<?= $color ?> text-uppercase mb-1"><?= $label ?></div>
										<div class="h5 mb-0 font-weight-bold text-gray-800"><?= $value ?></div>
									</div>
									<div class="col-auto">
										<i class="fas fa-<?= $icon ?> fa-2x text-gray-300"></i>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Charts -->
			<div class="row">
				<!-- Bar Chart: Jenis Kelamin -->
				<div class="col-xl-8 col-lg-7">
					<div class="card shadow mb-4">
						<div class="card-header py-3 d-flex justify-content-between align-items-center">
							<h6 class="m-0 font-weight-bold text-primary">Data Pegawai Berdasarkan Jenis Kelamin</h6>
						</div>
						<div class="card-body" style="height: 350px;">
							<canvas id="barGenderChart"></canvas>
						</div>
					</div>
				</div>

				<!-- Pie Chart: Status Pegawai -->
				<div class="col-xl-4 col-lg-5">
					<div class="card shadow mb-4">
						<div class="card-header py-3 d-flex justify-content-between align-items-center">
							<h6 class="m-0 font-weight-bold text-primary">Status Pegawai</h6>
						</div>
						<div class="card-body">
							<div style="height: 300px;">
								<canvas id="pieStatusChart"></canvas>
							</div>
							<div class="mt-4 text-center small">
								<span class="mr-2"><i class="fas fa-circle text-primary"></i> Pegawai Tetap</span>
								<span class="mr-2"><i class="fas fa-circle text-success"></i> Tidak Tetap</span>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<!-- Chart.js -->
	<script src="<?= base_url('assets/vendor/chart.js/Chart.min.js') ?>"></script>
	<script>
		// Bar Chart: Gender
		const barGenderChart = new Chart(document.getElementById('barGenderChart').getContext('2d'), {
			type: 'bar',
			data: {
				labels: ['Laki-laki', 'Perempuan'],
				datasets: [{
					label: 'Jumlah Pegawai',
					data: [<?= $jml_laki ?>, <?= $jml_perempuan ?>],
					backgroundColor: ['rgba(54, 162, 235, 0.7)', 'rgba(255, 99, 132, 0.7)'],
					borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)'],
					borderWidth: 1
				}]
			},
			options: {
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true,
							stepSize: 1
						}
					}]
				}
			}
		});

		// Pie Chart: Status Pegawai
		const pieStatusChart = new Chart(document.getElementById('pieStatusChart').getContext('2d'), {
			type: 'doughnut',
			data: {
				labels: ['Pegawai Tetap', 'Tidak Tetap'],
				datasets: [{
					data: [<?= $pegawai_tetap ?>, <?= $pegawai_tidak_tetap ?>],
					backgroundColor: ['#4e73df', '#1cc88a'],
					hoverBackgroundColor: ['#2e59d9', '#17a673'],
					hoverBorderColor: 'rgba(234, 236, 244, 1)'
				}]
			},
			options: {
				maintainAspectRatio: false,
				responsive: true
			}
		});
	</script>
</body>

</html>