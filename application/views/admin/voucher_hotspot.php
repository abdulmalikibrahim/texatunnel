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
                            <div class="form-group">
                                <div class="row">
									<div class="col-12 mb-3">
										<?php
										if($this->role_id == "1"){
											?>
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
											<?php
										}
										?>
									</div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="email">
                                            <i class="fas fa-server fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih lokasi server" aria-hidden="true"></i> Server (Server yang tersedia): </label>
                                        <select id="id_server" class="custom-select input-sm" onchange="get_paket()">
                                            <option disabled value="">Pilih Server</option>
                                            <?php
                                            $server = $this->model->gd("api_routeros", "id,nama_server,ip_address", "id_mitra = '".$this->id_mitra."' AND is_active = '1'", "result");
                                            if (!empty($server)) {
                                                foreach ($server as $server) {
                                                    echo '<option value="' . e_nzm($server->id) . '" data-ip-address="'.$server->ip_address.'">' . $server->nama_server . ' (' . $server->ip_address . ') ' . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="paket">
                                            <i class="fas fa-wifi fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih Server Hotspot" aria-hidden="true"></i> Server Hotspot : 
                                        </label>
                                        <select id="server_hotspot" class="custom-select input-sm form-clear">
                                            <option selected="selected" disabled value="">Silahkan Pilih Server Hotspot</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="paket">
                                            <i class="fas fa-gift fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih Paket Hotspot" aria-hidden="true"></i> Paket : 
                                        </label>
                                        <select id="paket" class="custom-select input-sm form-clear">
                                            <option selected="selected" disabled value="">Silahkan Pilih Paket</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="paket">
                                            <i class="fas fa-info-circle fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Infor Paket Hotspot" aria-hidden="true"></i> Info Paket : 
                                        </label>
                                        <div id="info-paket">
                                            <div class="row">
                                                <div class="col-6">Waktu</div>
                                                <div class="col-6 font-weight-bold" id="info-waktu">: -</div>
                                                <div class="col-6">Download</div>
                                                <div class="col-6 font-weight-bold" id="info-download">: -</div>
                                                <div class="col-6">Upload</div>
                                                <div class="col-6 font-weight-bold" id="info-upload">: -</div>
                                                <div class="col-6">Shared User</div>
                                                <div class="col-6 font-weight-bold" id="info-shared">: -</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <h4>Total Pembayaran : <span id="total_bayar"></span></h4>
                                <span class="text-danger"><small>*Note : Click Order untuk mendapatkan username dan password login hotspot.</small></span>
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
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 100%;" src="<?= base_url("assets/img/hotspot.png") ?>" alt="">
                        </div>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Server : </b> Silahkan pilih server yang sesuai. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Paket : </b> Silahkan pilih paket hotspot yang anda inginkan. </small>
                        <br>
                        <small class="text-danger">
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Note : </b>Username & Password akan otomatis generate saat anda klik order</small>
                        <br>
                        <br>
                        <small>
                            <b>Harap diperhatikan kembali data yang anda isi sebelum order hotspot !!!</b>
                        </small>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->
