<div id="sidebar" class="sidebar">
    <h3 class="text-center" style="color: white;">PENGGAJIAN</h3>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="<?= base_url('hrd/dashboard') ?>" class="nav-link text-white">
                <i class="fas fa-tachometer-alt"></i> Dashboard HRD
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('hrd/upah/laporan_upah') ?>" class="nav-link text-white">
                <i class="fas fa-file-alt"></i> Laporan Upah 
            </a>
        </li>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('hrd/cetak_upah') ?>" class="nav-link text-white">
                <i class="fas fa-print"></i> Cetak Upah 
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('ganti_password') ?>" class="nav-link text-white">
                <i class="fas fa-lock"></i> Ubah Password
            </a>
        </li>
        <!-- Nav Item - Tables -->
      <li class="nav-item">
        <a class="nav-link" href="<?php echo base_url('login/logout')?>">
          <i class="fas fa-fw fa-sign-out-alt"></i>
          <span>Logout</span></a>
      </li>
    </ul>
</div>