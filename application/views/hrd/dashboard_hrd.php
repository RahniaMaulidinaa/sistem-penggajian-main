<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Tambahkan CSS Bootstrap dan custom CSS -->
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
        .card {
            /* Hapus cursor: pointer dan transition */
        }
        .card:hover {
            /* Hapus transform: scale */
            transform: none; /* Memastikan tidak ada efek hover */
        }
    </style>
</head>
<body>

   <!-- Sidebar -->
<div id="sidebar" class="position-fixed">
    <ul class="nav flex-column pt-4">
        <li class="nav-item">
            <a href="<?= base_url('hrd/dashboard') ?>" class="nav-link text-white">
                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard HRD
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('hrd/laporan_upah') ?>" class="nav-link text-white">
                <i class="fas fa-file-invoice-dollar mr-2"></i> Laporan Upah Bulanan
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('hrd/laporan_upah_borongan') ?>" class="nav-link text-white">
                <i class="fas fa-clipboard-list mr-2"></i> Laporan Upah Borongan
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('hrd/ganti_password') ?>" class="nav-link text-white">
                <i class="fas fa-key mr-2"></i> Ganti Password
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('login/logout') ?>" class="nav-link text-white">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </li>
    </ul>
</div>
    <!-- Content -->
    <div id="content">
           
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Begin Page Content -->
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800" style="margin-top: -20px;"><?= $title ?></h1>                <div id="date"></div>
                <script>
                    var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    var date = new Date();
                    var day = date.getDate();
                    var month = date.getMonth();
                    var year = date.getFullYear();
                    document.getElementById("date").innerHTML = " " + day + " " + months[month] + " " + year;
                </script>
            </div>

            <!-- Content Row -->
            <div class="row">
                <!-- Earnings (Monthly) Card Example - Jumlah Pegawai -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Jumlah Pegawai</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $pegawai ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example - Jumlah HRD -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Jumlah HRD</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $hrd ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-user-cog fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earnings (Monthly) Card Example - Jumlah Jabatan -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Jumlah Jabatan</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $jabatan ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Requests Card Example - Data Kehadiran -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Data Kehadiran</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $kehadiran ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-comments fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                
                <!-- Area Chart -->
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Data Pegawai Berdasarkan Jenis Kelamin</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-bar">
                                <canvas id="myBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Status Pegawai</h6>
                            <div class="dropdown no-arrow">
                                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                </a>
                            </div>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="chart-pie pt-4 pb-2">
                                <canvas id="myPieChart"></canvas>
                            </div>
                            <div class="mt-4 text-center small">
                                <span class="mr-2">
                                    <i class="fas fa-circle text-primary"></i> pegawai Tetap
                                </span>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-success"></i> pegawai Tidak Tetap
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>

    <!-- Scripts for Charts -->
    <script src="<?= base_url(); ?>assets/vendor/chart.js/Chart.min.js"></script>
    <script>
        // Bar Chart
        var ctx = document.getElementById('myBarChart').getContext('2d');
        var myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    label: 'Jumlah Pegawai',
                    data: [
                        <?= $this->db->query("SELECT COUNT(*) as jml FROM data_pegawai WHERE jenis_kelamin='Laki-laki'")->row()->jml ?>,
                        <?= $this->db->query("SELECT COUNT(*) as jml FROM data_pegawai WHERE jenis_kelamin='Perempuan'")->row()->jml ?>
                    ],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',  // laki-laki
                         'rgba(235, 54, 139, 0.6)',  // perempuan (pink)
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(235, 54, 139, 1)',
                ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 0.5,
                            stepSize: 0.1,
                            precision: 1
                        },
                        suggestedMax: 5
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Pie Chart
        var ctxPie = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: ["pegawai Tetap", "pegawai Tidak Tetap"],
                datasets: [{
                    data: [
                        <?= $this->db->query("SELECT status FROM data_pegawai WHERE status='pegawai Tetap'")->num_rows(); ?>,
                        <?= $this->db->query("SELECT status FROM data_pegawai WHERE status='pegawai Tidak Tetap'")->num_rows(); ?>
                    ],
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                responsive: true
            }
        });
    </script>
</body>
</html>