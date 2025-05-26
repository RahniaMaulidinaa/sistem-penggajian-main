<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <h1 class="h3 mb-2 text-gray-800"><?php echo $title; ?></h1>

  <!-- Form Edit Data -->
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <h6 class="m-0 font-weight-bold text-primary">Edit Data Produksi Harian</h6>
    </div>
    <div class="card-body">
      <form action="<?php echo base_url('admin/produksi_harian/update'); ?>" method="post">
        <input type="hidden" name="id_produksi" value="<?php echo $produksi->id_produksi; ?>">
        <div class="form-group">
          <label for="id_pegawai">Nama Pegawai</label>
          <select class="form-control" id="id_pegawai" name="id_pegawai" required>
            <option value="">Pilih Pegawai</option>
            <?php 
              $borongan_jabatan = ['Tukang Obras', 'Cutting', 'Operator Overdeck', 'Cutting Assistant', 'Finishing'];
              foreach ($pegawai as $peg) {
                if (in_array($peg['jabatan'], $borongan_jabatan)) { ?>
                  <option value="<?php echo $peg['id_pegawai']; ?>" <?php echo ($peg['id_pegawai'] == $produksi->id_pegawai) ? 'selected' : ''; ?>>
                    <?php echo $peg['nama_pegawai']; ?> (<?php echo $peg['jabatan']; ?> - ID: <?php echo $peg['id_pegawai']; ?> - NIK: <?php echo $peg['nik']; ?>)
                  </option>
            <?php } } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="tanggal">Tanggal</label>
          <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $produksi->tanggal; ?>" required>
        </div>
        <div class="form-group">
          <label for="jumlah_unit">Jumlah Unit</label>
          <input type="number" class="form-control" id="jumlah_unit" name="jumlah_unit" value="<?php echo $produksi->jumlah_unit; ?>" min="1" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?php echo base_url('admin/produksi_harian'); ?>" class="btn btn-secondary">Kembali</a>
      </form>
    </div>
  </div>

</div>
<!-- /.container-fluid -->
</div>
<!-- End of Main Content -->