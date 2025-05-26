<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Notifikasi -->
  <?php if ($this->session->flashdata('pesan')): ?>
    <div class="row">
      <div class="col-md-12">
        <?php echo $this->session->flashdata('pesan'); ?>
      </div>
    </div>
  <?php endif; ?>

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800"><?php echo $title; ?></h1>

  <!-- Form Tambah Data -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Tambah Data Produksi Harian</h6>
    </div>
    <div class="card-body">
      <form action="<?php echo base_url('admin/produksi_harian/tambah'); ?>" method="post">
        <div class="form-group">
          <label for="id_pegawai">Nama Pegawai</label>
          <select class="form-control" id="id_pegawai" name="id_pegawai" required>
            <option value="">Pilih Pegawai</option>
            <?php 
              $borongan_jabatan = ['Tukang Obras', 'Cutting', 'Operator Overdeck', 'Cutting Assistant', 'Finishing'];
              foreach ($pegawai as $peg) {
                if (in_array($peg['jabatan'], $borongan_jabatan)) { ?>
                  <option value="<?php echo $peg['id_pegawai']; ?>">
                    <?php echo $peg['nama_pegawai']; ?> (<?php echo $peg['jabatan']; ?> - ID: <?php echo $peg['id_pegawai']; ?> - NIK: <?php echo $peg['nik']; ?>)
                  </option>
            <?php } } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="tanggal">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="tanggal" value="2025-05-26" required>
        </div>
        <div class="form-group">
          <label for="jumlah_unit">Jumlah Unit</label>
          <input type="number" class="form-control" id="jumlah_unit" name="jumlah_unit" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="<?php echo base_url('admin/produksi_harian'); ?>" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Data Produksi Harian</h6>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>ID Pegawai</th>
              <th>Nama Pegawai</th>
              <th>Tanggal</th>
              <th>Jumlah Unit</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach($produksi as $p) { ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $p->id_pegawai; ?></td>
                <td>
                  <?php 
                    $nama_pegawai = 'Tidak Diketahui';
                    foreach($pegawai as $peg) {
                      if($peg['id_pegawai'] == $p->id_pegawai) {
                        $nama_pegawai = $peg['nama_pegawai'] . ' (' . $peg['jabatan'] . ')';
                        break;
                      }
                    }
                    echo $nama_pegawai;
                  ?>
                </td>
                <td><?php echo date('d-m-Y', strtotime($p->tanggal)); ?></td>
                <td><?php echo $p->jumlah_unit; ?></td>
                <td>
                  <a href="<?php echo base_url('admin/produksi_harian/edit/' . $p->id_produksi); ?>" class="btn btn-warning btn-sm">Edit</a>
                  <a href="<?php echo base_url('admin/produksi_harian/hapus/' . $p->id_produksi); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?');">Hapus</a>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->