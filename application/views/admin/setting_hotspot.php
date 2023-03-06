<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?= $nzm ?></h1>
    <div class="row">
        <div class="col-lg-4">
            <p class="mb-1">Pilih Server</p>
            <select id="server" class="custom-select mb-2" onchange="change_server()">
                <option value="" data-id="0" disabled>- Pilih Server -</option>
                <?php
                $list_server = '';
                $server = $this->model->gd("api_routeros", "*", "is_active = '1'", "result");
                if (!empty($server)) {
                    foreach ($server as $server) {
                        $list_server .= '<option data-id="' . e_nzm($server->id) . '" value="' . $server->id_server . '">' . $server->nama_server . ' (' . $server->ip_address . ')</option>';
                    }
                }
                echo $list_server;
                ?>
            </select>
        </div>
        <div class="col-lg-8"></div>
		<div class="col-lg-12">
			<ul class="nav nav-pills border-0">
				<li class="nav-item"><a href="#hotspot-server" class="nav-link active" data-toggle="tab">Hotspot Server</a></li>
				<li class="nav-item"><a href="#data-paket" class="nav-link" data-toggle="tab">Daftar Paket</a></li>
			</ul>
		</div>
		<div class="col-12 tab-content border-0 pt-0" id="tabcontent">
			<div class="tab-pane fade show active" role="tabpanel" id="hotspot-server" aria-labelledby="hotspot-server-tab">
				<div class="row">
					<div class="col-lg-12">
						<div class="card mb-3">
							<div class="card-header p-2 text-light bg-gradient-primary">
								<div class="row">
									<div class="col-8"><span class="align-middle">Hotspot Servers</span></div>
									<div class="col-4" align="right"><a href="javascript:void(0)" onclick="form_add_hotspot()" class="btn btn-sm btn-light"><i class="fas fa-plus text-info"></i></a></div>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-sm table-bordered table-horvered" style="font-size:8pt;">
									<thead class="thead-light">
										<tr align="center">
											<th class="align-middle">No</th>
											<th class="align-middle">Name</th>
											<th class="align-middle">Interface</th>
											<th class="align-middle">Address Pool</th>
											<th class="align-middle">Profile</th>
											<th class="align-middle">Status</th>
											<th class="align-middle">Action</th>
										</tr>
									</thead>
									<tbody id="data-hotspot-servers">
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card mb-3">
							<div class="card-header p-2 text-light bg-gradient-primary">
								<div class="row">
									<div class="col-8"><span class="align-middle">IP Pool Hotspot</span></div>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-sm table-bordered table-horvered" style="font-size:8pt;">
									<thead class="thead-light">
										<tr align="center">
											<th class="align-middle">No</th>
											<th class="align-middle">Name</th>
											<th class="align-middle">Addresses</th>
											<th class="align-middle">Action</th>
										</tr>
									</thead>
									<tbody id="data-ip-pool">
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
						<div class="card mb-3">
							<div class="card-header p-2 text-light bg-gradient-primary">
								<div class="row">
									<div class="col-8"><span class="align-middle">Profile Hotspot</span></div>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-sm table-bordered table-horvered" style="font-size:8pt;">
									<thead class="thead-light">
										<tr align="center">
											<th class="align-middle">No</th>
											<th class="align-middle">Name</th>
											<th class="align-middle">Hotspot Address</th>
											<th class="align-middle">DNS Name</th>
											<th class="align-middle">Cookie</th>
											<th class="align-middle">Action</th>
										</tr>
									</thead>
									<tbody id="data-profile-hotspot">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane fade" role="tabpanel" id="data-paket" aria-labelledby="data-paket-tab">
				<div class="row">
					<div class="col-12">
						<div class="card mb-3">
							<div class="card-header p-2 text-light bg-gradient-primary">
								<div class="row">
									<div class="col-8"><span class="align-middle">Daftar Paket</span></div>
									<div class="col-4" align="right"><a href="javascript:void(0)" onclick="form_add_paket()" class="btn btn-sm btn-light"><i class="fas fa-plus text-info"></i></a></div>
								</div>
							</div>
							<div class="card-body">
								<table class="table table-sm table-bordered table-horvered" style="font-size:8pt;">
									<thead class="thead-light">
										<tr align="center">
											<th class="align-middle" rowspan="2">No</th>
											<th class="align-middle" rowspan="2">Name</th>
											<th class="align-middle" rowspan="2">Sesi</th>
											<th class="align-middle" rowspan="2">Shared<br>Users</th>
											<th class="align-middle" colspan="2">Limit</th>
											<th class="align-middle" rowspan="2">Harga</th>
											<th class="align-middle" rowspan="2">Action</th>
										</tr>
										<tr>
											<th class="align-middle">Download</th>
											<th class="align-middle">Upload</th>
										</tr>
									</thead>
									<tbody id="data-paket-hotspot">
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div>
<div class="modal" id="form_hotspot" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-hotspot"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Service Name <span class="text-danger">*</span></p>
                <div class="input-group">
                    <input type="text" class="form-control form-clear mb-2" id="service_name" placeholder="Masukkan Service Name Hotspot">
                    <div class="input-group-prepend mb-2">
                        <div class="input-group-text">-Hotspot</div>
                    </div>
                </div>
                <p class="mb-1">Interface</p>
                <select id="interface" class="custom-select form-clear mb-2" onchange="check_ip_address('element')"></select>
                <p id="interface_print"></p>
                <p class="mb-1">Local Address <span class="text-danger">*</span></p>
                <select id="local-address" class="custom-select form-clear mb-2" onchange="element_ip_pool_hotspot()"></select>
                <p class="mb-1">Address Pool <span class="text-danger">*</span></p>
                <div id="element-ip-pool" class="input-group"></div>
                <p class="mb-1">DNS Name <span class="text-danger">*</span></p>
                <input type="text" class="form-control form-clear mb-2" id="dns_name_server" placeholder="Contoh : Hotspotwifi.net">
                <p class="mb-1">Username <span class="text-danger">*</span></p>
                <input type="text" id="username" class="form-control form-clear mb-2" placeholder="Contoh : admin">
                <p class="mb-1">Password <span class="text-danger">*</span></p>
                <input type="password" id="password" class="form-control form-clear mb-2" placeholder="Contoh : admin">
                <p class="mb-1">Cookie <span class="text-danger">*</span></p>
                <select id="cookie" class="custom-select form-clear mb-2 col-lg-3">
                    <option value="" disabled>- Pilih Cookie -</option>
                    <option value="Yes">Yes</option>
                    <option value="No">No</option>
                </select>
                <div id="waktu_cookie">
                    <p class="mb-1">Cookie Lifetime <span class="text-danger">*</span></p>
                    <div class="input-group">
                        <select id="time_cookie_hours" class="custom-select mb-2">
                            <?php
                            for ($i = 0; $i <= 23; $i++) {
                                echo '<option value="' . $i . '">' . $i . ' Jam</option>';
                            }
                            ?>
                        </select>
                        <select id="time_cookie_minutes" class="custom-select mb-2">
                            <?php
                            for ($i = 0; $i <= 59; $i++) {
                                echo '<option value="' . $i . '">' . $i . ' Menit</option>';
                            }
                            ?>
                        </select>
                        <select id="time_cookie_seconds" class="custom-select mb-2">
                            <?php
                            for ($i = 0; $i <= 59; $i++) {
                                echo '<option value="' . $i . '">' . $i . ' Detik</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <p class="mb-1">Trial <span class="text-danger">*</span></p>
                <select id="trial" class="custom-select mb-2 col-lg-3">
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
                </select>
                <div id="waktu_trial">
                    <p class="mb-1">Trial Lifetime <span class="text-danger">*</span></p>
                    <div class="input-group">
                        <select id="time_trial_hours" class="custom-select mb-2">
                            <?php
                            for ($i = 0; $i <= 23; $i++) {
                                echo '<option value="' . $i . '">' . $i . ' Jam</option>';
                            }
                            ?>
                        </select>
                        <select id="time_trial_minutes" class="custom-select mb-2">
                            <?php
                            for ($i = 0; $i <= 59; $i++) {
                                echo '<option value="' . $i . '">' . $i . ' Menit</option>';
                            }
                            ?>
                        </select>
                        <select id="time_trial_seconds" class="custom-select mb-2">
                            <?php
                            for ($i = 0; $i <= 59; $i++) {
                                echo '<option value="' . $i . '">' . $i . ' Detik</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <p class="mb-1">Status</p>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_active" value="1" onchange="change_status()">
                    <label class="custom-control-label" for="is_active">Aktif</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-simpan-hotspot-servers">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="form_user_paket" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-paket-hotspot"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Name <span class="text-danger">*</span></p>
                <div class="input-group">
                    <input type="text" class="form-control form-clear mb-2" id="name_paket" placeholder="Masukkan Nama Paket, Contoh : Paket-2Jam">
                    <div class="input-group-prepend mb-2">
                        <div class="input-group-text">-Hotspot</div>
                    </div>
                </div>
                <p class="mb-1">Address Pool <span class="text-danger">*</span></p>
                <select id="address_pool_paket" class="custom-select mb-2"></select>
                <p class="mb-1">Waktu Penggunaan <span class="text-danger">*</span></p>
                <div class="input-group">
                    <select id="session_time_days" class="custom-select mb-2">
                        <?php
                        for ($i = 0; $i <= 5; $i++) {
                            echo '<option value="' . $i . '">' . $i . ' Hari</option>';
                        }
                        ?>
                    </select>
                    <select id="session_time_hours" class="custom-select mb-2">
                        <?php
                        for ($i = 0; $i <= 23; $i++) {
                            echo '<option value="' . $i . '">' . $i . ' Jam</option>';
                        }
                        ?>
                    </select>
                    <select id="session_time_minutes" class="custom-select mb-2">
                        <?php
                        for ($i = 0; $i <= 59; $i++) {
                            echo '<option value="' . $i . '">' . $i . ' Menit</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <p class="mb-1">Download Limit <span class="text-danger">*</span></p>
                        <input type="number" class="form-control form-clear mb-2" id="download_limit" placeholder="Contoh : 5 (Satuan MB)">
                    </div>
                    <div class="col-lg-6">
                        <p class="mb-1">Upload Limit <span class="text-danger">*</span></p>
                        <input type="number" class="form-control form-clear mb-2" id="upload_limit" placeholder="Contoh : 2 (Satuan MB)">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <p class="mb-1">Shared Users <span class="text-danger">*</span></p>
                        <input type="number" class="form-control form-clear mb-2" id="shared_users" placeholder="Jumlah share user">
                    </div>
                    <div class="col-lg-6">
                        <p class="mb-1">Harga <span class="text-danger">*</span></p>
                        <input type="text" class="form-control form-clear harga mb-2" id="harga" placeholder="Masukkan harga paket disini">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-simpan-paket-hotspot">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="form_ippool" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-ip-pool"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Name</p>
                <div class="input-group">
                    <input type="text" class="form-control form-clear mb-2" id="name_ip_pool" placeholder="Masukkan Name IP Pool">
                    <div class="input-group-prepend mb-2">
                        <div class="input-group-text">-Hotspot</div>
                    </div>
                </div>
                <p class="mb-1">Addresses</p>
                <input type="text" id="addresses" class="form-control form-clear mb-2" placeholder="Contoh : 10.5.5.0-10.5.5.254">
                <span class="text-danger">Gunakan tanda <b>,</b> jika IP Pool lebih dari 1<br>Contoh 10.5.5.0-10.5.5.54<b>,</b>10.5.5.56-10.5.5.254</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-simpan-ip-pool">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
