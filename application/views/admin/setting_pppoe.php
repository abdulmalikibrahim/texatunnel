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
                $server = $this->model->gd("api_routeros", "*", "id_mitra = '".$this->id_mitra."' AND is_active = '1'", "result");
                if (!empty($server)) {
                    foreach ($server as $server) {
                        $list_server .= '<option data-id="' . e_nzm($server->id) . '" value="' . $server->id_server . '">' . $server->nama_server . ' ('.$server->ip_address.')</option>';
                    }
                }
                echo $list_server;
                ?>
            </select>
        </div>
        <div class="col-lg-8"></div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header p-2 text-light bg-gradient-primary">
                    <div class="row">
                        <div class="col-8"><span class="align-middle">PPPoE Servers</span></div>
                        <div class="col-4" align="right"><a href="javascript:void(0)" onclick="form_add_pppoe()" class="btn btn-sm btn-light"><i class="fas fa-plus text-info"></i></a></div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered table-horvered" style="font-size:8pt;">
                        <thead class="thead-light">
                            <tr align="center">
                                <th class="align-middle">No</th>
                                <th class="align-middle">Service Name</th>
                                <th class="align-middle">Ether</th>
                                <th class="align-middle">Status</th>
                                <th class="align-middle">Action</th>
                            </tr>
                        </thead>
                        <tbody id="data-pppoe-servers">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header p-2 text-light bg-gradient-primary">
                    <div class="row">
                        <div class="col-8"><span class="align-middle">IP Pool PPPoE</span></div>
                        <div class="col-4" align="right"><a href="javascript:void(0)" onclick="form_add_ippool()" class="btn btn-sm btn-light"><i class="fas fa-plus text-info"></i></a></div>
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
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header p-2 text-light bg-gradient-primary">
                    <div class="row">
                        <div class="col-8"><span class="align-middle">Profile PPPoE</span></div>
                        <div class="col-4" align="right"><a href="javascript:void(0)" onclick="form_add_profile_pppoe()" class="btn btn-sm btn-light"><i class="fas fa-plus text-info"></i></a></div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered table-horvered">
                        <thead>
                            <tr align="center">
                                <th class="align-middle" style="font-size:8pt;" rowspan="2">No</th>
                                <th class="align-middle" style="font-size:8pt;" rowspan="2">Name</th>
                                <th class="align-middle" style="font-size:8pt;" rowspan="2">Local Address</th>
                                <th class="align-middle" style="font-size:8pt;" rowspan="2">Remote Address</th>
                                <th class="align-middle" style="font-size:8pt;" colspan="2">Rate Limit</th>
                                <th class="align-middle" style="font-size:8pt;" rowspan="2">Only One</th>
                                <th class="align-middle" style="font-size:8pt;" rowspan="2">Harga/Bulan</th>
                                <th class="align-middle" style="font-size:8pt;" colspan="2">User</th>
                                <th class="align-middle" style="font-size:8pt;" rowspan="2">Action</th>
                            </tr>
                            <tr align="center">
                                <th class="align-middle" style="font-size:8pt;">Download</th>
                                <th class="align-middle" style="font-size:8pt;">Upload</th>
                                <th class="align-middle" style="font-size:8pt;">Aktif</th>
                                <th class="align-middle" style="font-size:8pt;">Isolir</th>
                            </tr>
                        </thead>
                        <tbody id="data-profile-pppoe">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="form_pppoe" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-pppoe"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Service Name</p>
                <input type="text" class="form-control form-clear mb-2" id="service_name" placeholder="Masukkan Service Name PPPoE">
                <p class="mb-1">Interface</p>
                <select id="interface" class="custom-select form-clear mb-2"></select>
                <p id="interface_print"></p>
                <p class="mb-1">Status</p>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="is_active" value="1" onchange="change_status()">
                    <label class="custom-control-label" for="is_active">Aktif</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-simpan-pppoe-servers">Simpan</button>
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
                        <div class="input-group-text">-PPPoE</div>
                    </div>
                </div>
                <p class="mb-1">Addresses</p>
                <input type="text" id="addresses" class="form-control form-clear mb-2" placeholder="Contoh : 10.5.5.0/24">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-simpan-ip-pool">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="form_profile_pppoe" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="title-profile-pppoe"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="mb-1">Name <span class="text-danger">*</span></p>
                <input type="text" class="form-control form-clear mb-2" id="name_profile_pppoe" placeholder="Masukkan Nama Profile, Contoh : PaketFast">
                <p class="mb-1">Local Address <span class="text-danger">*</span></p>
                <select id="local_address" class="custom-select mb-2 form-clear"></select>
                <p class="mb-1">Remote Address <span class="text-danger">*</span></p>
                <select id="remote_address" class="custom-select mb-2 form-clear"></select>
                <div class="row">
                    <div class="col-lg-6">
                        <p class="mb-1">Download Rate <span class="text-danger">*</span></p>
                        <input type="number" class="form-control form-clear mb-2" id="download_rate" placeholder="Contoh : 5 (Satuan MB)">
                    </div>
                    <div class="col-lg-6">
                        <p class="mb-1">Upload Rate <span class="text-danger">*</span></p>
                        <input type="number" class="form-control form-clear mb-2" id="upload_rate" placeholder="Contoh : 2 (Satuan MB)">
                    </div>
                </div>
                <p class="mb-1">Only One <span class="text-danger">*</span></p>
                <select id="only_one" class="custom-select mb-2">
                    <option value="default">Default</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                </select>
                <p class="mb-1">Harga <span class="text-danger">*</span></p>
                <input type="text" class="form-control form-clear harga mb-2" id="harga" placeholder="Masukkan harga paket disini">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-simpan-profile-pppoe">Simpan</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>