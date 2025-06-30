<!-- Navbar Atas -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow">
  <div class="container-fluid">
    <a class="navbar-brand font-weight-bold" href="#">KONVEKSI KAMPOENG BUSANA</a>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item dropdown no-arrow">
        <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown">
          <span class="mr-2 d-none d-lg-inline text-white small">Selamat Datang, <?= $this->session->userdata('nama_pegawai') ?></span>
          <img class="img-profile rounded-circle" style="width: 32px; height: 32px; object-fit: cover;" src="<?= base_url('photo/') . $this->session->userdata('photo') ?>">
        </a>
      </li>
    </ul>
  </div>
</nav>

<!-- Sidebar -->
<div id="sidebar" class="position-fixed">
  <ul class="nav flex-column pt-4">
    <li class="nav-item">
      <a href="<?= base_url('hrd/dashboard') ?>" class="nav-link text-white">
        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard HRD
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= base_url('hrd/data_gaji') ?>" class="nav-link text-white">
      <i class="fas fa-tachometer-alt mr-2"></i> Data Gaji
      <a href="<?= base_url('hrd/laporan_upah') ?>" class="nav-link text-white">
        <i class="fas fa-file-invoice-dollar mr-2"></i> Laporan Gaji Bulanan
      </a>
    </li>
    <li class="nav-item">
      <a href="<?= base_url('hrd/laporan_upah_borongan') ?>" class="nav-link text-white">
        <i class="fas fa-clipboard-list mr-2"></i> Laporan Gaji Borongan
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

<!-- Konten Utama -->
<div id="main-content" class="p-4">
  <!-- Konten halaman lain akan ditampilkan di sini -->
</div>

<!-- Style CSS -->
<style>
  body {
    margin: 0;
    padding: 0;
    background-color: #f8f9fa;
  }

  /* Sidebar */
  #sidebar {
    width: 220px;
    height: 100vh;
    top: 56px; /* tinggi navbar */
    left: 0;
    padding-top: 20px;
    background-color: rgb(30, 103, 185); /* Warna navy blue tua */
    z-index: 1000;
  }

  /* Sidebar link hover & active effect */
  .nav-link {
    transition: background-color 0.2s ease;
  }

  .nav-link:hover {
    background-color: #2c3e50;
    color: #ffffff;
  }

  /* Konten utama */
  #main-content {
    margin-left: 220px;
    margin-top: 56px;
    padding: 20px;
  }

  /* Navbar custom color */
  .navbar.bg-primary {
    background-color: #2e86de !important; /* Warna biru cerah */
  }

  .img-profile {
    border-radius: 50%;
  }

  .navbar-brand {
    font-size: 18px;
  }

  @media (max-width: 768px) {
    #sidebar {
      display: none;
    }

    #main-content {
      margin-left: 0;
    }

    .navbar-brand {
      font-size: 16px;
    }
  }
</style>
