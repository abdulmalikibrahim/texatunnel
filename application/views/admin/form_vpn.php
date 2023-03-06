<?php
if (!empty($this->p3)) {
    $data = $this->model->gd("vpn_master", "*", "id = '" . $this->p3 . "'", "row");
    if (!empty($data->id)) {
        $id_server = $data->id_server;
        $nama = $data->nama;
        $harga = number_format($data->harga, 0, "", ".");
        $lokasi = $data->lokasi;
        if ($data->status == "Aktif") {
            $status = "checked";
        } else {
            $status = "";
        }
        $explode_ip_local = explode(".",$data->ip_local);
        $ip_local_0 = $explode_ip_local[0];
        $ip_local_1 = $explode_ip_local[1];
        $ip_local_2 = $explode_ip_local[2];
        $explode_ip_public = explode(".",$data->ip_public);
        $ip_public_0 = $explode_ip_public[0];
        $ip_public_1 = $explode_ip_public[1];
        $ip_public_2 = $explode_ip_public[2];
        $ip_public_3 = $explode_ip_public[3];
    } else {
        $this->session->set_flashdata("swal", '
		<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
		<script>
			var text = "ID Tidak Ditemukan";
			swal.fire({title:"Error",html:text,icon:"error"});
		</script>');
        redirect("list_vpn");
    }
} else {
    $id_server = "";
    $nama = "";
    $harga = "";
    $lokasi = "";
    $ip_local_0 = "";
    $ip_local_1 = "";
    $ip_local_2 = "";
    $ip_public_0 = "";
    $ip_public_1 = "";
    $ip_public_2 = "";
    $ip_public_3 = "";
    $ip_public = "";
    $status = "checked";
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
                    <form action="<?= base_url("save_vpn_master/" . $this->p3) ?>" method="post">
						<?= $this->csrf; ?>
                        <div class="row">
                            <div class="col-lg-12 mb-2">
                                <p class="mb-1">Server</p>
                                <select name="id_server" id="id_server" class="custom-select" required>
                                    <option value="">- Pilih Server -</option>
                                    <?php
                                    $data_server = $this->model->gd("api_routeros", "id_server,nama_server,country,ip_address,port", "id != '' AND is_active = '1'", "result");
                                    if (!empty($data_server)) {
                                        foreach ($data_server as $data_server) {
                                            if($data_server->id_server == $id_server){
                                                $s = "selected";
                                            }else{
                                                $s = "";
                                            }
                                            if(empty($data_server->port)){
                                                $port = "-";
                                            }else{
                                                $port = $data_server->port;
                                            }
                                            echo '<option value="'.$data_server->id_server.'" data-nama_server="'.$data_server->nama_server.'" data-country="'.$data_server->country.'" data-ip_address="'.$data_server->ip_address.'" data-port="'.$port.'" '.$s.'>'.$data_server->nama_server.'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <div class="row mt-2" id="detail_server">
                                    <div class="col-lg-5">Nama Server</div>
                                    <div class="col-lg-7 font-weight-bold" id="tnama_server"></div>
                                    <div class="col-lg-5">IP Address</div>
                                    <div class="col-lg-7 font-weight-bold" id="tip_address"></div>
                                    <div class="col-lg-5">Port</div>
                                    <div class="col-lg-7 font-weight-bold" id="tport"></div>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <p class="mb-1">Nama VPN</p>
                                <input type="text" name="nama" id="nama" class="form-control" placeholder="Contoh : VPN Remote ID27" value="<?= $nama ?>" required>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <p class="mb-1">Harga</p>
                                <input type="text" inputmode="numeric" name="harga" id="harga" class="form-control harga" placeholder="Masukkan Harga disini" value="<?= $harga ?>" required>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <p class="mb-1">IP Public</p>
                                <div class="input-group">
                                    <input type="text" name="ip_public_0" class="form-control text-center" placeholder="117" maxlength="3" value="<?= $ip_public_0 ?>" required>
                                    <input type="text" name="ip_public_1" class="form-control text-center" placeholder="13" maxlength="3" value="<?= $ip_public_1 ?>" required>
                                    <input type="text" name="ip_public_2" class="form-control text-center" placeholder="202" maxlength="3" value="<?= $ip_public_2 ?>" required>
                                    <input type="text" name="ip_public_3" class="form-control text-center" placeholder="112" maxlength="3" value="<?= $ip_public_3 ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <p class="mb-1">IP Local</p>
                                <div class="input-group">
                                    <input type="text" name="ip_local_0" class="form-control text-center" placeholder="27" maxlength="2" value="<?= $ip_local_0 ?>" required>
                                    <input type="text" name="ip_local_1" class="form-control text-center" placeholder="27" maxlength="2" value="<?= $ip_local_1 ?>" required>
                                    <input type="text" name="ip_local_2" class="form-control text-center" placeholder="27" maxlength="2" value="<?= $ip_local_2 ?>" required>
                                    <input type="text" class="form-control text-center" placeholder="1" value="1" readonly>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <p class="mb-1">Lokasi</p>
                                <input type="text" name="lokasi" id="lokasi" class="form-control" placeholder="Lokasi" value="<?= $lokasi ?>" required>
                            </div>
                            <div class="col-lg-12 mb-2">
                                <p class="mb-1">Status</p>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="status" name="status" value="Aktif" onchange="change_status()" <?= $status ?>>
                                    <label class="custom-control-label" for="status">Aktif</label>
                                </div>
                            </div>
                            <div class="col-lg-12 mb-2" align="right">
                                <a href="<?= base_url("list_vpn") ?>" class="btn btn-sm btn-danger">Kembali</a>
                                <button type="submit" class="btn btn-sm btn-info">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
