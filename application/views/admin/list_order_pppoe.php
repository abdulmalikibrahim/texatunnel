<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800"><?=$nzm?></h1>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5 mb-3 mb-sm-0">
                    <div class="input-group">
                        <label for="id_server" class="pl-0 pr-2 pt-2 pb-2"><i class="fas fa-server fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Pilih lokasi server" aria-hidden="true"></i> Server : </label>
                        <select id="id_server" class="custom-select input-sm rounded" onchange="get_paket()">
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
                </div>
                <div class="col-lg-12">
                        <table class="table table-sm table-bordered table-horvered w-100" id="datatable" style="font-size: 10pt;">
                            <thead class="thead-light">
                                <tr>
                                    <th class="p-1 text-center align-middle">No</th>
                                    <th class="p-1 text-center align-middle">Email</th>
                                    <th class="p-1 text-center align-middle">Username</th>
                                    <th class="p-1 text-center align-middle">Password</th>
                                    <th class="p-1 text-center align-middle">Paket</th>
                                    <th class="p-1 text-center align-middle">Date Order</th>
                                    <th class="p-1 text-center align-middle">Expired</th>
                                    <th class="p-1 text-center align-middle">Berlangganan</th>
                                    <th class="p-1 text-center align-middle">@Rp</th>
                                    <th class="p-1 text-center align-middle">Auto<br>Debit</th>
                                    <th class="p-1 text-center align-middle">Status</th>
                                    <th class="p-1 text-center align-middle">Action</th>
                                </tr>
                            </thead>
                            <tbody id="data-body">
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="form_edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-edit">Edit PPPoE</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Username</p>
                <div class="input-group">
                    <input type="text" class="form-control form-clear mb-2 rounded" id="name" placeholder="Masukkan Username Baru" onkeyup="check_username()" autocomplete="off">
                    <span id="check-username"></span>
                </div>
                <p class="mb-1">Password</p>
                <input type="text" class="form-control form-clear mb-2" id="password" placeholder="Masukkan Password Baru" autocomplete="off">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="action">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="form_edit_paket" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-edit">Rubah Paket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Date Order</p>
                <input type="text" class="form-control mb-2 rounded" id="date_order" value="<?= date("d-M-Y"); ?>" disabled autocomplete="off">
                <p class="mb-1">Paket</p>
                <select id="paket" class="custom-select form-clear mb-2"></select>
                <p class="mb-1">Berlangganan</p>
                <select id="berlangganan" class="custom-select form-clear mb-2">
                    <option selected="selected" disabled="disable" value="">Pilih Berlangganan</option>
                    <?php
                    for ($i = 1; $i <= 12; $i++) {
                        echo '<option value="' . $i . '">' . $i . ' Bulan</option>';
                    }
                    ?>
                </select>
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input id="status_debit" type="checkbox" class="custom-control-input" checked="">
                        <label name="remember" class="custom-control-label" for="status_debit" data-toggle="tooltip" data-original-title="Opsi auto Perpanjang">Perpanjang Otomatis <small>(Auto Debit)</small>
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <h4>Saldo : <span id="current_saldo"></span></h4>
                </div>
                <div class="form-group">
                    <h4>Total Pembayaran : <span id="total_bayar"></span></h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-save-paket">Aktifkan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>