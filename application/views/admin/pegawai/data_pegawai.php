<div class="container-fluid">
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $title?></h1>
  </div>
  <a class="btn btn-sm btn-success mb-3" href="<?php echo base_url('admin/data_pegawai/tambah_data') ?>"><i class="fas fa-plus"></i> Tambah Pegawai</a>
  <?php echo $this->session->flashdata('pesan')?>
</div>

<div class="container-fluid">
  <div class="card shadow mb-4">
   <div class="card-body">
     <div class="table-responsive">
       <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
         <thead class="thead-dark">
           <tr>
              <th class="text-center">No</th>
              <th class="text-center">NIK</th>
              <th class="text-center">Nama Pegawai</th>
              <th class="text-center">Jenis Kelamin</th>
              <th class="text-center">Jabatan</th>
              <th class="text-center">Tanggal Masuk</th>
              <th class="text-center">Status</th>
              <th class="text-center">Gaji Pokok</th>
              <th class="text-center">Tunjangan Transport</th>
              <th class="text-center">Uang Makan</th>
              <th class="text-center">Total Gaji</th>
              <th class="text-center">Tarif Borongan</th>
              <th class="text-center">Jenis Gaji</th>
              <th class="text-center">Hak Akses</th>
              <th class="text-center">Photo</th>
              <th class="text-center">Actions</th>
           </tr>
         </thead>
         <tbody>
           <?php $no=1; foreach($pegawai as $p) : ?>
            <?php
            // Menghitung total gaji
            $total_gaji = $p->gaji_pokok + $p->tj_transport + $p->uang_makan;
            ?>
            <tr>
              <td class="text-center"><?php echo $no++ ?></td>
              <td class="text-center"><?php echo $p->nik ?></td>
              <td class="text-center"><?php echo $p->nama_pegawai ?></td>
              <td class="text-center"><?php echo $p->jenis_kelamin ?></td>
              <td class="text-center"><?php echo $p->nama_jabatan ?></td>
              <td class="text-center"><?php echo $p->tanggal_masuk ?></td>
              <td class="text-center"><?php echo $p->status ?></td>
              <td class="text-center">Rp <?= number_format($p->gaji_pokok, 0, ',', '.') ?></td>
              <td class="text-center">Rp <?= number_format($p->tj_transport, 0, ',', '.') ?></td>
              <td class="text-center">Rp <?= number_format($p->uang_makan, 0, ',', '.') ?></td>
              <td class="text-center">Rp <?= number_format($total_gaji, 0, ',', '.') ?></td>
              <td class="text-center">
                Rp. <?= ($p->jenis_gaji == 'Borongan') ? number_format($p->tarif_borongan, 0, ',', '.') : '0' ?>
              </td>

              <td class="text-center"><?php echo $p->jenis_gaji ?></td>
              <?php if($p->hak_akses=='1') { ?>
                <td>Admin</td>
                <?php } elseif($p->hak_akses=='2') { ?>
                  <td>Pegawai</td>  
                <?php } else { ?>
                  <td>HRD</td>
                <?php } ?>
              <td><img src="<?php echo base_url().'photo/'.$p->photo?>" width="50px"></td>
              <td>
                <center>
                  <a class="btn btn-sm btn-info" href="<?php echo base_url('admin/data_pegawai/update_data/'.$p->id_pegawai) ?>"><i class="fas fa-edit"></i></a>

                 </center>
              </td>
            </tr>
          <?php endforeach; ?>
         </tbody>
       </table>
     </div>
   </div>
  </div>
</div>