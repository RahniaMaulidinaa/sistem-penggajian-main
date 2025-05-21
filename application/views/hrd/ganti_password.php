<div class="d-flex justify-content-center">
    <div class="container-fluid" style="max-width: 600px;">
        <h4 class="mb-4 text-center"><?= $title ?></h4>
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
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>