<div class="container-fluid">
    <h4 class="mb-4"><?= $title ?></h4>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ganti Password</h6>
        </div>
        <div class="card-body">
            <?= $this->session->flashdata('pesan') ?>
            <form action="<?= base_url('ganti_password/ganti_password_aksi') ?>" method="post">
                <div class="form-group">
                    <label for="passBaru">Password Baru</label>
                    <input type="password" class="form-control" id="passBaru" name="passBaru" required>
                </div>
                <div class="form-group">
                    <label for="ulangPass">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="ulangPass" name="ulangPass" required>
                </div>
                <button type="submit" class="btn btn-primary">Ganti Password</button>
            </form>
        </div>
    </div>
</div>