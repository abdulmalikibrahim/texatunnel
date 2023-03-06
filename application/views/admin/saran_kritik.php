<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $nzm; ?></h1>
    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url("submit_saran_kritik") ?>" method="post">
						<?= $this->csrf; ?>
                        <div class="row">
                            <div class="col-lg-12 md-sandbox mb-3">
                                <p class="mb-1">Masukkan Saran & Kritik</p>
                                <textarea name="saran_kritik" id="saran_kritik" rows="15" class="form-control text-sand" placeholder="Saran & Kritik anda membantu kami menjadi lebih baik." required></textarea>
                            </div>
                            <div class="col-lg-12 mb-2" align="right">
                                <button type="submit" class="btn btn-sm btn-info"><i class="fas fa-paper-plane pr-2"></i>Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->
