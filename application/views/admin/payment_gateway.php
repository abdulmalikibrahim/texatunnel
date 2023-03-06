<?php
$data_md = $this->model->gd("payment_gateway", "*", "id_mitra = '".$this->id_mitra."' AND gateway = 'Midtrans'", "row");
if (!empty($data_md->id)) {
    $id_merchant_sand = $data_md->id_merchant_sand;
    $client_key_sand = $data_md->client_key_sand;
    $server_key_sand = $data_md->server_key_sand;
    $id_merchant_prod = $data_md->id_merchant_prod;
    $client_key_prod = $data_md->client_key_prod;
    $server_key_prod = $data_md->server_key_prod;
    $biaya_penanganan = number_format($data_md->biaya_penanganan,0,"",".");
    $status = $data_md->status;
    if ($data_md->is_active == "1") {
        $is_active = "checked";
    } else {
        $is_active = "";
    }
} else {
    $id_merchant_sand = "";
    $client_key_sand = "";
    $server_key_sand = "";
    $id_merchant_prod = "";
    $client_key_prod = "";
    $server_key_prod = "";
    $status = "";
    $is_active = "checked";
    $biaya_penanganan = '0';
}
?>
<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $nzm; ?></h1>
    <div class="row">
        <div class="col-lg-5">
            <div class="card">
                <div class="card-body">
                    <form action="<?= base_url("save_pg_md") ?>" method="post">
						<?= $this->csrf; ?>
                        <div class="row">
                            <div class="col-12" align="center">
                                <img src="https://docs.midtrans.com/asset/image/main/midtrans-logo.png" width="80%">
                            </div>
                            <div class="col-lg-12 mt-3 mb-2" align="right">
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="Aktif" onchange="change_is_active()" <?= $is_active ?>>
                                    <label class="custom-control-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <p class="mb-1">Status</p>
                                <select name="status" id="status" class="custom-select" onchange="check_status()" required>
                                    <option value="" disabled>- Pilih Status -</option>
                                    <?php
                                    if($status == "Sandbox"){
                                        $s_sand = "selected";
                                        $s_prod = "";
                                    }else{
                                        $s_sand = "";
                                        $s_prod = "selected";
                                    }
                                    echo '
                                    <option '.$s_sand.'>Sandbox</option>
                                    <option '.$s_prod.'>Production</option>';
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-12 md-sandbox mb-2">
                                <p class="mb-1">ID Merchant</p>
                                <input type="text" name="id_merchant_sand" id="id_merchant_sand" class="form-control text-sand" placeholder="Masukkan ID Merchant" value="<?= $id_merchant_sand ?>">
                            </div>
                            <div class="col-lg-12 md-sandbox mb-2">
                                <p class="mb-1">Client Key</p>
                                <input type="text" name="client_key_sand" id="client_key_sand" class="form-control text-sand" placeholder="Masukkan Client Key" value="<?= $client_key_sand ?>">
                            </div>
                            <div class="col-lg-12 md-sandbox mb-2">
                                <p class="mb-1">Server Key</p>
                                <input type="text" name="server_key_sand" id="server_key_sand" class="form-control text-sand" placeholder="Masukkan Client Key" value="<?= $server_key_sand ?>">
                            </div>

                            <div class="col-lg-12 md-production mb-2">
                                <p class="mb-1">ID Merchant</p>
                                <input type="text" name="id_merchant_prod" id="id_merchant_prod" class="form-control text-prod" placeholder="Masukkan ID Merchant" value="<?= $id_merchant_prod ?>">
                            </div>
                            <div class="col-lg-12 md-production mb-2">
                                <p class="mb-1">Client Key</p>
                                <input type="text" name="client_key_prod" id="client_key_prod" class="form-control text-prod" placeholder="Masukkan Client Key" value="<?= $client_key_prod ?>">
                            </div>
                            <div class="col-lg-12 md-production mb-2">
                                <p class="mb-1">Server Key</p>
                                <input type="text" name="server_key_prod" id="server_key_prod" class="form-control text-prod" placeholder="Masukkan Client Key" value="<?= $server_key_prod ?>">
                            </div>
                            
                            <div class="col-lg-12 md-sandbox mb-2">
                                <p class="mb-1">Biaya Penanganan</p>
                                <input type="text" inputmode="numeric" name="biaya_penanganan" id="biaya_penanganan" class="form-control harga" placeholder="Masukkan ID Merchant" value="<?= $biaya_penanganan ?>">
                                <span class="text-danger">* Biaya yang akan di tanggung oleh customer</span>
                            </div>
                            <div class="col-lg-12 mb-2" align="right">
                                <button type="submit" class="btn btn-sm btn-info">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->
