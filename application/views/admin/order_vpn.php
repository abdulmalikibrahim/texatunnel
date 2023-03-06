<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $nzm ?></h1>
    <div class="row">
        <div class="col-lg-8">
            <!-- <div class="card mb-4">
                <div class="card-header"><i class="fas fa-circle-info mr-2"></i>Informasi VPN Remote</div>
                <div class="card-body">
                    <small>
                        <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i> VPN Remote berfungsi untuk remote perangkat anda dari luar jaringan.
                    </small><br>
                    <small>
                        <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i> Ini dapat digunakan sebagai alternative dari tidak tersedianya ip public pada isp anda.
                    </small><br>
                    <small>
                        <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i> 1 Akun VPN hanya bisa digunakan untuk 1 remote port (Buat lagi jika ingin nambah remote).
                    </small><br>
                    <small>
                        <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i> Koneksi VPN ini bisa menggunakan protokol OpenVPN.
                    </small><br>
                    <small>
                        <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i> Jika kamu menggunakan load balance, silahkan hubungi admin di 0852-9018-9491
                    </small><br>
                </div>
            </div> -->
            <div class="card shadow mb-4 ">
                <div class="collapse show" id="createvpn">
					<form id="VPNRemote" action="<?=base_url("simpan_order_vpn")?>" method="post">
						<div class="card-body">
							<?= $this->csrf; ?>
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
									<label for="email">
										<i class="fas fa-server fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih lokasi server" aria-hidden="true"></i> VPN Server (Server yang tersedia): </label>
									<select name="id_server" id="id_server" class="custom-select input-sm" required="">
										<option selected="selected" disabled="disable" value="">Pilih Server</option>
										<?php
										$data_vpn = $this->model->gd("vpn_master","id,nama,harga,lokasi","id_mitra = '".$this->id_mitra."' AND status = 'Aktif'","result");
										if(!empty($data_vpn)){
											foreach ($data_vpn as $data_vpn) {
												echo '<option value="'.$data_vpn->id.'" data-harga="'.$data_vpn->harga.'">'.$data_vpn->nama.' (Rp. '.number_format($data_vpn->harga,0,"",".").') '.$data_vpn->lokasi.'</option>';
											}
										}
										?>
									</select>
								</div>
								<div class="form-group row">
									<div class="col-sm-6 mb-3 mb-sm-0">
										<label for="email">
											<i class="fas fa-user fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Masukan username" aria-hidden="true"></i> Username : </label>
										<div class="input-group mb-2 mr-sm-2">
											<input type="text" class="form-control" name="username" id="username" placeholder="Masukkan Username" required="" autocomplete="off">
											<div class="input-group-prepend">
												<div class="input-group-text bg-white" id="status-check"></div>
											</div>
										</div>
									</div>
									<div class="col-sm-6 ">
										<label for="email">
											<i class="fas fa-fingerprint fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Masukan password" aria-hidden="true"></i> Password : </label>
										<input type="text" class="form-control" name="password" placeholder="Masukkan Password" required="">
									</div>
								</div>
								<div class="form-group">
									<label for="email">
										<i class="fas fa-ethernet fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Masukan port yang ingin diremote" aria-hidden="true"></i> Port For Remote : </label>
									<input type="number" class="form-control" name="port" placeholder="Contoh : 8291 (Port Yang ingin diremote)" required="">
								</div>
								<div class="form-group">
									<label for="email">
										<i class="fas fa-calendar-alt fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih lokasi server" aria-hidden="true"></i> Berlangganan: </label>
									<select name="berlangganan" id="berlangganan" class="custom-select input-sm" required="">
										<option selected="selected" disabled="disable" value="">Pilih Berlangganan</option>
										<?php
										for ($i=1; $i <= 12; $i++) { 
											echo '<option value="'.$i.'">'.$i.' Bulan</option>';
										}
										?>
									</select>
								</div>
								<div class="form-group" hidden>
									<div class="custom-control custom-switch">
										<input name="status_debit" value="0" type="hidden" class="custom-control-input" checked="">
										<input name="status_debit" value="1" type="checkbox" class="custom-control-input" id="customCheck">
										<label name="remember" class="custom-control-label" for="customCheck" data-toggle="tooltip" data-original-title="Opsi auto Perpanjang">Perpanjang Otomatis <small>(Auto Debit)</small>
										</label>
									</div>
								</div>
								<div class="form-group">
									<h4>Total Pembayaran : <span id="total_bayar"></span></h4>
								</div>
							</div>
						</div>
						<div class="card-footer text-right">
							<button type="reset" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fas fa-undo" style="font-size:13px" aria-hidden="true"></i> Reset </button>
							<button class="btn btn-sm btn-primary" id="action"><i class="fas fa-shopping-cart" style="font-size:13px" aria-hidden="true"></i> Order </button>
						</div>
					</form>
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
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 15rem;" src="<?= base_url("assets/img/form.png") ?>" alt="">
                        </div>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>VPN Server : </b> Silahkan pilih sesuai lokasi server &amp; harga yang ada inginkan. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Username : </b> Silahkan isi username untuk akun vpn anda. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Password : </b> Silahkan isi password untuk akun vpn anda. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Port For Remote : </b> Silahkan isi port yang mau anda remote, misal mau remote winbox, maka isi port sesuai port winbox di IP &gt; Service. </small>
                        <br>
                        <small>
                            <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                            <b>Perpanjang Otomatis : </b> Harap aktifkan fitur ini jika ingin vpn kamu diperpanjang secara otomatis.
                        </small>
                        <br>
                        <br>
                        <small>
                            <b>Harap diperhatikan kembali data yang anda isi sebelum order vpn !!!</b>
                        </small>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Main Content -->
