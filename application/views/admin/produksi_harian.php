<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800"><?php echo $title; ?></h1>

  <!-- Form Tambah Data -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Tambah Data Produksi Harian</h6>
    </div>
    <div class="card-body">
    <form action="<?php echo base_url('produksi_harian/tambah'); ?>" method="post">
    
        <div class="form-group">
          <label for="id_pegawai">Nama Pegawai</label>
          <select class="form-control" id="id_pegawai" name="id_pegawai" required>
            <option value="">Pilih Pegawai</option>
            <?php 
              $borongan_jabatan = ['Tukang Obras', 'Cutting', 'Operator Overdeck', 'Cutting Assistant', 'Finishing'];
              foreach ($pegawai as $peg) {
                if (in_array($peg['jabatan'], $borongan_jabatan)) { ?>
                  <option value="<?php echo $peg['id_pegawai']; ?>">
                    <?php echo $peg['nama_pegawai']; ?>
                  </option>
            <?php } } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="tanggal">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>
        <div class="form-group">
          <label for="jumlah_unit">Jumlah Unit</label>
          <input type="number" class="form-control" id="jumlah_unit" name="jumlah_unit" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
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
              <th>Nama Pegawai</th>
              <th>Tanggal</th>
              <th>Jumlah Unit</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; foreach($produksi as $p) { ?>
              <tr>
                <td><?php echo $no++; ?></td>
                <td>
                  <?php 
                    $nama_pegawai = 'Tidak Diketahui';
                    foreach($pegawai as $peg) {
                      if($peg['id_pegawai'] == $p->id_pegawai) {
                        $nama_pegawai = $peg['nama_pegawai'];
                        break;
                      }
                    }
                    echo $nama_pegawai;
                  ?>
                </td>
                <td><?php echo date('d-m-Y', strtotime($p->tanggal)); ?></td>
                <td><?php echo $p->jumlah_unit; ?></td>
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

