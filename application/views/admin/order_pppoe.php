<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $nzm ?></h1>
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4 ">
                <div class="collapse show" id="createvpn">
                    <div class="card-body">
                        <div class="col-sm-12 mb-3 mb-sm-0">
							<?php
							if($this->role_id == "1"){
								?>
								<div class="form-group">
									<label for="email">
										<i class="fas fa-user fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih client anda" aria-hidden="true"></i> Client : </label>
									<select name="id_user" id="id_user" class="custom-select input-sm" required="">
										<option selected="selected" disabled="disable" value="">Pilih Client</option>
										<?php
										$data_client = $this->model->gd("user","id,name","id_mitra = '".$this->id_mitra."' AND is_active = '1' AND role_id = '2'","result");
										if(!empty($data_client)){
											foreach ($data_client as $data_client) {
												echo '<option value="'.$data_client->id.'">'.$data_client->name.'</option>';
											}
										}
										?>
									</select>
								</div>
								<?php
							}
							?>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label for="email">
                                            <i class="fas fa-server fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih lokasi server" aria-hidden="true"></i> Server (Server yang tersedia): </label>
                                        <select id="id_server" class="custom-select input-sm" onchange="get_paket()">
                                            <option disabled value="">Pilih Server</option>
                                            <?php
                                            $server = $this->model->gd("api_routeros", "id,nama_server,ip_address", "id_mitra = '".$this->id_mitra."' AND is_active = '1'", "result");
                                            if (!empty($server)) {
                                                foreach ($server as $server) {
                                                    echo '<option value="' . e_nzm($server->id) . '">' . $server->nama_server . ' (' . $server->ip_address . ') ' . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <label for="paket">
                                            <i class="fas fa-gift fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih Paket PPPoE" aria-hidden="true"></i> Paket (Upload/Download): 
                                        </label>
                                        <select id="paket" class="custom-select input-sm form-clear">
                                            <option selected="selected" disabled value="">Silahkan Pilih Paket</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <label for="email">
                                        <i class="fas fa-user fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Masukan username" aria-hidden="true"></i> Username :
                                    </label>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <input type="text" class="form-control form-clear rounded" id="username" placeholder="Masukkan Username" onkeyup="check_username()">
                                        <span id="check-username"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6 ">
                                    <label for="email">
                                        <i class="fas fa-fingerprint fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Masukan password" aria-hidden="true"></i> Password : 
                                    </label>
                                    <input type="text" class="form-control form-clear" id="password" placeholder="Masukkan Password" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">
                                    <i class="fas fa-calendar-alt fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih lokasi server" aria-hidden="true"></i> Berlangganan: 
                                </label>
                                <select name="berlangganan" id="berlangganan" class="custom-select form-clear input-sm" required="">
                                    <option selected="selected" disabled="disable" value="">Pilih Berlangganan</option>
                                    <?php
                                    for ($i = 1; $i <= 12; $i++) {
                                        echo '<option value="' . $i . '">' . $i . ' Bulan</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" hidden>
                                <div class="custom-control custom-switch">
                                    <input id="status_debit" type="checkbox" class="custom-control-input">
                                    <label name="remember" class="custom-control-label" for="status_debit" data-toggle="tooltip" data-original-title="Opsi auto Perpanjang">Perpanjang Otomatis <small>(Auto Debit)</small>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <h4>Total Pembayaran : <span id="total_bayar"></span></h4>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-sm btn-secondary" data-dismiss="modal">
                                <i class="fas fa-undo" style="font-size:13px" aria-hidden="true" id="reset"></i> Reset </button>
                            <button class="btn btn-sm btn-primary" id="action" onclick="simpan_order()">
                                <i class="fas fa-shopping-cart" style="font-size:13px" aria-hidden="true"></i> Order </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-gradient-info text-light">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-fw fa-exclamation-circle" style="font-size:13px;" aria-hidden="true"></i> Informasi Pengisian
                    </h6>
                </div>
                <div class="collapse show" id="informasivpn">
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 70%;" src="<?= base_url("assets/img/ppoe.png") ?>" alt="">
                        </div>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Server : </b> Silahkan pilih server yang tersedia. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Paket : </b> Silahkan pilih paket yang anda inginkan. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Username : </b> Silahkan isi username untuk akun PPPoE anda. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Password : </b> Silahkan isi password untuk akun PPPoE anda. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Berlangganan : </b> Silahkan pilih langganan waktu anda, contoh 1 bulan maka akun PPPoE anda akan aktif selama 1 bulan. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Perpanjang Otomatis : </b> Harap aktifkan fitur ini jika ingin PPPoE kamu diperpanjang secara otomatis.
                        </small>
                        <br>
                        <br>
                        <small>
                            <b>Harap diperhatikan kembali data yang anda isi sebelum order PPPoE !!!</b>
                        </small>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->
