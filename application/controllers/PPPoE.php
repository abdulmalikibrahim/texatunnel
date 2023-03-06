<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class PPPoE extends MY_Controller
{
    public function data_interface()
    {
        $data = [
            "id" => d_nzm($this->input->get("id")),
        ];
        $interface = $this->router_process("print_interface", $data);
        $option = "";
        if (!empty($interface)) {
            foreach ($interface as $key => $value) {
                if ($value["disabled"] == 'false') {
                    $option .= '<option>' . $value["name"] . '</option>';
                } else {
                    $option .= '<option disabled>' . $value["name"] . ' (Disabled)</option>';
                }
            }
        }
        echo $option;
        die();
    }

    public function simpan_pppoe_servers($p)
    {
        if (!empty($p)) {
            $this->form_validation->set_rules('id_server', 'Server', 'required|trim|xss_clean');
            $this->form_validation->set_rules('service_name', 'Service Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('interface', 'Interface', 'required|trim|xss_clean');
            $this->form_validation->set_rules('is_active', 'Status', 'required|trim|xss_clean|integer');
            if ($this->form_validation->run() === TRUE) {
                if ($this->input->post("is_active") == "1") {
                    $disabled = 'no';
                } else {
                    $disabled = 'yes';
                }
                $data_router = [
                    "id"  => d_nzm($this->input->post("id_server")),
                    "service_name" => str_replace(" ","-",$this->input->post("service_name")),
                    "interface" => $this->input->post("interface"),
                    "disabled" => $disabled,
                ];
                if ($p == "add") {
                    //ADD PPPOE SERVER
                    $action_pppoe_servers = $this->router_process('pppoe_servers/add', $data_router);
                    $pesan_action = 'Ditambah';
                } else {
                    //EDIT PPPOE SERVER
                    $data_router[".id"] = d_nzm($p);
                    $action_pppoe_servers = $this->router_process('pppoe_servers/update', $data_router);
                    $pesan_action = 'Dirubah';
                }

                if ($action_pppoe_servers == "200") {
                    $fb = [
                        'title' => 'Sukses',
                        'pesan' => "PPPoE Server Berhasil " . $pesan_action,
                        'icon' => 'success',
                    ];
                } else {
                    $fb = [
                        'title' => 'Error',
                        'pesan' => "PPPoE Server Gagal " . $pesan_action,
                        'icon' => 'error',
                    ];
                }
            } else {
                $fb = [
                    'title' => 'Warning',
                    'pesan' => str_replace("\n", "<br>", validation_errors()),
                    'icon' => 'warning',
                ];
            }
        } else {
            $fb = [
                'title' => 'Error',
                'pesan' => 'Parameter yang anda kirim kosong',
                'icon' => 'error',
            ];
        }
        echo json_encode($fb);
        die();
    }

    public function delete_pppoe_servers($p)
    {
        if (!empty($p)) {
            $data_router = [
                "id" => d_nzm($this->input->post("id")),
                ".id" => d_nzm($p),
            ];
            $remove = $this->router_process("pppoe_servers/remove", $data_router);
            if ($remove == "200") {
                $fb = [
                    'title' => 'Sukses',
                    'pesan' => 'PPPoE Server Berhasil Dihapus',
                    'icon' => 'success',
                ];
            } else {
                $fb = [
                    'title' => 'Error',
                    'pesan' => 'PPPoE Server Gagal Dihapus',
                    'icon' => 'error',
                ];
            }
        } else {
            $fb = [
                'title' => 'Error',
                'pesan' => 'Parameter yang anda kirim kosong',
                'icon' => 'error',
            ];
        }
        echo json_encode($fb);
        die();
    }

    public function list_pppoe_servers($p)
    {
        if (!empty($p)) {
            $data_router = [
                "id" => d_nzm($p),
            ];
            $list_pppoe_servers = $this->router_process("pppoe_servers/list", $data_router);
            if (!empty($list_pppoe_servers)) {
                $td_load = '';
                $no = 1;
                foreach ($list_pppoe_servers as $key => $value) {
                    //LOAD TABLE ROW
                    $id = $value[".id"];
                    if ($value["disabled"] == "false") {
                        $status = '<i class="fas fa-check-circle text-success" title="Active" style="cursor:pointer;"></i>';
                    } else {
                        $status = '<i class="fas fa-times-circle text-danger" title="Non Active" style="cursor:pointer;"></i>';
                    }
                    $td_load .= '
                    <tr class="text-center">
                        <td class="align-middle p-1">' . $no . '</td>
                        <td class="align-middle p-1">' . $value["service-name"] . '</td>
                        <td class="align-middle p-1"><div style="min-width:70px;">' . $value["interface"] . '</div></td>
                        <td class="align-middle p-1">' . $status . '</td>
                        <td class="align-middle p-1">
                            <div style="min-width:70px;">
                                <button class="btn btn-sm btn-info btn-edit-pppoe-servers" data-id="' . e_nzm($id) . '" data-service_name="' . $value["service-name"] . '" data-interface="' . $value["interface"] . '" data-is_active="' . $value["disabled"] . '"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete-pppoe-servers" data-id="' . e_nzm($id) . '"><i class="fas fa-trash-alt"></i></button>
                            </div>
                        </td>
                    </tr>';
                    $no++;
                }
            } else {
                $td_load = '
                <tr>
                    <td colspan="5" align="center"><i class="text-danger">Data PPPoE Servers Kosong</i></td>
                </tr>';
            }
        } else {
            $td_load = '
            <tr>
                <td colspan="5" align="center"><i class="text-danger">Silahkan pilih server di atas</i></td>
            </tr>';
        }

        echo $td_load;
        die();
    }

    public function list_ip_pool($p)
    {
        if (!empty($p)) {
            $data_router = [
                "id" => d_nzm($p),
            ];
            $list = $this->router_process("ip_pool/list", $data_router);
            if (!empty($list)) {
                $option_load = '<option value="">- Pilih Address -</option>';
                $td_load = '';
                $no = 1;
                foreach ($list as $key => $value) {
                    //LOAD TABLE ROW
                    $id = $value[".id"];
                    $ex_name = explode("-", $value["name"]);
                    if (end($ex_name) == "PPPoE") {
                        $name = str_replace("-PPPoE", "", $value["name"]);
                        $td_load .= '
                        <tr class="text-center">
                            <td class="align-middle">' . $no . '</td>
                            <td class="align-middle">' . $value["name"] . '</td>
                            <td class="align-middle">' . $value["ranges"] . '</td>
                            <td class="align-middle">
                                <button class="btn btn-sm btn-info btn-edit-ip-pool" data-id="' . e_nzm($id) . '" data-name="' . $name . '" data-addresses="' . $value["ranges"] . '"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-sm btn-danger btn-delete-ip-pool" data-id="' . e_nzm($id) . '"><i class="fas fa-trash-alt"></i></button>
                            </td>
                        </tr>';

                        $option_load .= '<option value="' . $value["name"] . '">' . $value["name"] . '</option>';
                        $no++;
                    }
                }
            } else {
                $td_load = '
                <tr>
                    <td colspan="4" align="center"><i class="text-danger">Data IP Pool Kosong</i></td>
                </tr>';
                $option_load = "<option value=''>IP Pool PPPoE Kosong</option>";
            }
        } else {
            $td_load = '
            <tr>
                <td colspan="4" align="center"><i class="text-danger">Silahkan pilih server di atas</i></td>
            </tr>';
            $option_load = "<option value=''>Server Belum Dipilih</option>";
        }

        $fb = [
            "td_load" => $td_load,
            "option_load" => $option_load,
        ];
        echo json_encode($fb);
        die();
    }

    public function simpan_ip_pool($p)
    {
        if (!empty($p)) {
            $this->form_validation->set_rules('id_server', 'Server', 'required|trim|xss_clean');
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('addresses', 'Addresses', 'required|trim|xss_clean');
            if ($this->form_validation->run() === TRUE) {
                $data_router = [
                    "id"  => d_nzm($this->input->post("id_server")),
                    "name" => str_replace(" ","-",$this->input->post("name"))."-PPPoE",
                    "addresses" => $this->input->post("addresses"),
                ];
                if ($p == "add") {
                    //ADD IP POOL
                    $action = $this->router_process('ip_pool/add', $data_router);
                    $pesan_action = 'Ditambah';
                } else {
                    //EDIT IP POOL
                    $data_router[".id"] = d_nzm($p);
                    $action = $this->router_process('ip_pool/update', $data_router);
                    $pesan_action = 'Dirubah';
                }

                if ($action == "200") {
                    $fb = [
                        'title' => 'Sukses',
                        'pesan' => "IP Pool Berhasil " . $pesan_action,
                        'icon' => 'success',
                    ];
                } else {
                    $fb = [
                        'title' => 'Error',
                        'pesan' => "IP Pool Gagal " . $pesan_action,
                        'icon' => 'error',
                    ];
                }
            } else {
                $fb = [
                    'title' => 'Warning',
                    'pesan' => str_replace("\n", "<br>", validation_errors()),
                    'icon' => 'warning',
                ];
            }
        } else {
            $fb = [
                'title' => 'Error',
                'pesan' => 'Parameter yang anda kirim kosong',
                'icon' => 'error',
            ];
        }
        echo json_encode($fb);
        die();
    }

    public function remove_ip_pool($p)
    {
        if (!empty($p)) {
            $data_router = [
                "id" => d_nzm($this->input->post("id")),
                ".id" => d_nzm($p),
            ];
            $remove = $this->router_process("ip_pool/remove", $data_router);
            if ($remove == "200") {
                $fb = [
                    'title' => 'Sukses',
                    'pesan' => 'IP Pool Berhasil Dihapus',
                    'icon' => 'success',
                ];
            } else {
                $fb = [
                    'title' => 'Error',
                    'pesan' => 'IP Pool Gagal Dihapus',
                    'icon' => 'error',
                ];
            }
        } else {
            $fb = [
                'title' => 'Error',
                'pesan' => 'Parameter yang anda kirim kosong',
                'icon' => 'error',
            ];
        }
        echo json_encode($fb);
        die();
    }

    public function list_profile_pppoe($p)
    {
        if (!empty($p)) {
            $data_router = [
                "id" => d_nzm($p),
            ];
            $list = $this->router_process("profile_pppoe/list", $data_router);
            $user_profile = $this->router_process("pppoe_client/list", $data_router);
            if (!empty($list)) {
                if (array_keys(array_column($list, 'comment'))) {
                    $td_load = '';
                    $no = 1;
                    $user_pppoe = array_keys(array_column($user_profile, 'service'), 'pppoe');
                    foreach ($list as $key => $value) {
                        //LOAD TABLE ROW
                        $id = $value[".id"];
                        if (array_key_exists("comment", $value)) {
                            $comment = $value["comment"];
                            if (substr_count($comment, "PPPoE") > 0) {
                                $rate_limit = explode("/", $value["rate-limit"]);
                                $harga = explode(";", $comment);
                                $user_active[$value["name"]] = 0;
                                $user_dis[$value["name"]] = 0;
                                //USER ACTIVE
                                if(!empty($user_pppoe)){
                                    foreach ($user_pppoe as $k_user => $v_user) {
                                        $paket = $user_profile[$v_user]["profile"];
                                        $disabled_user = $user_profile[$v_user]["disabled"];
                                        // echo $paket." ".$disabled_user;
                                        if($paket == $value["name"]){
                                            if($disabled_user == "false"){
                                                $user_active[$paket] += 1;
                                            }else{
                                                $user_dis[$paket] += 1;
                                            }
                                        }
                                    }
                                }

                                if($user_active[$value["name"]] > 0){
                                    $user_active[$value["name"]] = $user_active[$value["name"]];
                                }else{
                                    $user_active[$value["name"]] = "-";
                                }

                                if($user_dis[$value["name"]] > 0){
                                    $user_dis[$value["name"]] = $user_dis[$value["name"]];
                                }else{
                                    $user_dis[$value["name"]] = "-";
                                }
                                $td_load .= '
                                <tr class="text-center">
                                    <td class="align-middle p-1">' . $no . '</td>
                                    <td class="align-middle p-1">' . $value["name"] . '</td>
                                    <td class="align-middle p-1">' . $value["local-address"] . '</td>
                                    <td class="align-middle p-1">' . $value["remote-address"] . '</td>
                                    <td class="align-middle p-1">' . $rate_limit[1] . '</td>
                                    <td class="align-middle p-1">' . $rate_limit[0] . '</td>
                                    <td class="align-middle p-1">' . $value["only-one"] . '</td>
                                    <td class="align-middle p-1">' . number_format($harga[1], 0, "", ".") . '</td>
                                    <td class="align-middle p-1">' . $user_active[$value["name"]] . '</td>
                                    <td class="align-middle p-1">' . $user_dis[$value["name"]] . '</td>
                                    <td class="align-middle p-1">
                                        <button class="btn btn-sm btn-info btn-edit-profile-pppoe" data-id="' . e_nzm($id) . '" data-name="' . $value["name"] . '" data-local-address="' . $value["local-address"] . '" data-remote-address="' . $value["remote-address"] . '" data-download-rate="' . str_replace("M", "", $rate_limit[1]) . '" data-upload-rate="' . str_replace("M", "", $rate_limit[0]) . '" data-only-one="' . $value["only-one"] . '" data-harga="' . number_format($harga[1], 0, "", ".") . '"><i class="fas fa-pencil-alt"></i></button>
                                        <button class="btn btn-sm btn-danger btn-delete-profile-pppoe" data-id="' . e_nzm($id) . '"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>';
                            }
                            $no++;
                        }
                    }
                } else {
                    $td_load = '
                    <tr>
                        <td colspan="11" align="center"><i class="text-danger">Data Profile PPPoE Kosong</i></td>
                    </tr>';
                }
            } else {
                $td_load = '
                <tr>
                    <td colspan="10" align="center"><i class="text-danger">Data Profile PPPoE Kosong</i></td>
                </tr>';
            }
        } else {
            $td_load = '
            <tr>
                <td colspan="10" align="center"><i class="text-danger">Silahkan pilih server di atas</i></td>
            </tr>';
        }

        echo $td_load;
        die();
    }

    public function simpan_profile_pppoe($p)
    {
        if (!empty($p)) {
            $this->form_validation->set_rules('id_server', 'Server', 'required|trim|xss_clean');
            $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('local_address', 'Local Address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('remote_address', 'Remote Address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('download_rate', 'Download Rate', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('upload_rate', 'Upload Rate', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('only_one', 'Only One', 'required|trim|xss_clean');
            $this->form_validation->set_rules('harga', 'Harga', 'required|trim|xss_clean');
            if ($this->form_validation->run() === TRUE) {
                $data_router = [
                    "id"  => d_nzm($this->input->post("id_server")),
                    "name" => str_replace(" ","-",$this->input->post("name")),
                    "local_address" => $this->input->post("local_address"),
                    "remote_address" => $this->input->post("remote_address"),
                    "rate_limit" => $this->input->post("upload_rate") . "M/" . $this->input->post("download_rate") . "M",
                    "only_one" => strtolower($this->input->post("only_one")),
                    "comment" => "PPPoE;" . str_replace(".", "", $this->input->post("harga")),
                ];
                if ($p == "add") {
                    //ADD PROFILE PPPOE
                    $action = $this->router_process('profile_pppoe/add', $data_router);
                    $pesan_action = 'Ditambah';
                } else {
                    //EDIT PROFILE PPPOE
                    $data_router[".id"] = d_nzm($p);
                    $action = $this->router_process('profile_pppoe/update', $data_router);
                    $pesan_action = 'Dirubah';
                }

                if ($action == "200") {
                    $fb = [
                        'title' => 'Sukses',
                        'pesan' => "Profile PPPoE Berhasil " . $pesan_action,
                        'icon' => 'success',
                    ];
                } else {
                    $fb = [
                        'title' => 'Error',
                        'pesan' => "Profile PPPoE Gagal " . $pesan_action,
                        'icon' => 'error',
                    ];
                }
            } else {
                $fb = [
                    'title' => 'Warning',
                    'pesan' => str_replace("\n", "<br>", validation_errors()),
                    'icon' => 'warning',
                ];
            }
        } else {
            $fb = [
                'title' => 'Error',
                'pesan' => validation_errors(),
                'icon' => 'error',
            ];
        }
        echo json_encode($fb);
        die();
    }

    public function remove_profile_pppoe($p)
    {
        if (!empty($p)) {
            $data_router = [
                "id" => d_nzm($this->input->post("id")),
                ".id" => d_nzm($p),
            ];
            $remove = $this->router_process("profile_pppoe/remove", $data_router);
            if ($remove == "200") {
                $fb = [
                    'title' => 'Sukses',
                    'pesan' => 'Profile PPPoE Berhasil Dihapus',
                    'icon' => 'success',
                ];
            } else {
                $fb = [
                    'title' => 'Error',
                    'pesan' => 'Profile PPPoE Gagal Dihapus',
                    'icon' => 'error',
                ];
            }
        } else {
            $fb = [
                'title' => 'Error',
                'pesan' => 'Parameter yang anda kirim kosong',
                'icon' => 'error',
            ];
        }
        echo json_encode($fb);
        die();
    }

    public function get_paket()
    {
        $id_server = $this->input->post('id_server');
        if (!empty($id_server)) {
            $data_router = [
                "id" => d_nzm($id_server),
            ];
            $list = $this->router_process("profile_pppoe/list", $data_router);
            if (!empty($list)) {
                $option_load = '<option value="" selected disabled>Pilih Paket</option>';
                foreach ($list as $key => $value) {
                    //LOAD TABLE ROW
                    if (!empty($value["comment"])) {
                        if (substr_count($value["comment"], "PPPoE") > 0) {
                            $harga = explode(";", $value["comment"]);
                            $option_load .= '
                            <option value="' . $value["name"] . '" data-h="' . $harga[1] . '">' . $value["name"] . ' (' . $value["rate-limit"] . ')' . '</option>';
                        }
                    }
                }
            } else {
                $option_load = '<option value="" disabled selected>Paket Server Ini Kosong</option>';
            }
        } else {
            $option_load = '<option value="" disabled>Silahkan pilih server</option>';
        }

        echo $option_load;
        die();
    }

    public function check_username()
    {
        $this->form_validation->set_rules('idd_pppoe', "ID PPPoE", "trim|xss_clean");
        $this->form_validation->set_rules('id_server', "Server", "required|trim|xss_clean");
        $this->form_validation->set_rules('username', "Username", "required|trim|xss_clean");
        if ($this->form_validation->run() === TRUE) {
            $id_pppoe = d_nzm($this->input->post('id_pppoe'));
            $id_server = $this->input->post('id_server');
            $username = $this->input->post('username');
            $data_router = [
                "id" => d_nzm($id_server),
            ];

            $username_list = $this->router_process("pppoe_client/list", $data_router);
            if(empty($id_pppoe)){
                $key_username = array_keys(array_column($username_list, 'name'), $username);
                if(empty($key_username)){//JIKA USERNAME TERSEDIA
                    $fb = [
                        "status" => "ok",
                    ];
                }else{
                    $fb = [
                        "status" => "error",
                    ];
                }
            }else{
                $key_username = array_keys(array_column($username_list, 'name'), $username);
                if(!empty($key_username)){
                    if($username_list[$key_username[0]][".id"] == $id_pppoe){
                        $fb = [
                            "status" => "ok",
                        ];
                    }else{
                        $fb = [
                            "status" => "error",
                        ];
                    }
                }else{
                    $fb = [
                        "status" => "ok",
                    ];
                }
            }
        } else {
            $fb = [
                "title" => "Error",
                "pesan" => validation_errors(),
                "icon" => "error",
                "status" => "error",
            ];
        }

        echo json_encode($fb);
        die();
    }

    public function simpan_order()
    {
		if($this->role_id == "1"){
			$this->form_validation->set_rules("id_user", "Client", "required|trim|xss_clean|integer");
		}
        $this->form_validation->set_rules("id_server", "Server", "required|trim|xss_clean");
        $this->form_validation->set_rules("paket", "Paket", "required|trim|xss_clean");
        $this->form_validation->set_rules("username", "Username", "required|trim|xss_clean");
        $this->form_validation->set_rules("password", "Password", "required|trim|xss_clean");
        $this->form_validation->set_rules("berlangganan", "Berlangganan", "required|trim|xss_clean|integer");
        $this->form_validation->set_rules("status_debit", "Status Debit", "required|trim|xss_clean|integer");
        if ($this->form_validation->run() === TRUE) {
			if($this->role_id == "1"){
				$id_user = $this->input->post("id_user");
			}else{
				$id_user = $this->id_user;
			}
            $id_server = $this->input->post("id_server");
            $paket = $this->input->post("paket");
            $username = $this->input->post("username");
            $password = $this->input->post("password");
            $berlangganan = $this->input->post("berlangganan");
            $status_debit = $this->input->post("status_debit");
            $comment = [
                "service" => "PPPoE",
                "date_order" => date("d-m-Y"),
                "berlangganan" => $berlangganan,
                "expired_date" => date("d-m-Y",strtotime("+".$berlangganan." month")),
                "auto_debit" => $status_debit,
                "id_user" => $id_user,
                "email" => $this->email,
            ];
            $data_router = [
                "id" => d_nzm($id_server),
                "data" => array(
                    "name" => $username,
                    "password" => $password,
                    "service" => "pppoe",
                    "profile" => $paket,
                    "comment" => json_encode($comment),
                )
            ];

            $username_list = $this->router_process("pppoe_client/list", $data_router);
            $key_username = array_keys(array_column($username_list, 'name'), $username);
            if(empty($key_username)){//JIKA USERNAME TERSEDIA
                $data_paket = $this->router_process("profile_pppoe/list", $data_router);
                if (!empty($data_paket)) {
                    $key_paket = array_keys(array_column($data_paket, 'name'), $paket);
                    $key_paket = $key_paket[0];
                    $harga = explode(";", $data_paket[$key_paket]["comment"]);
                    $harga = $harga[1];
                    $total_bayar = $harga * $berlangganan;
    
                    //CHECK SALDO
                    $saldo = $this->model->gd("user", "saldo", "id = '$id_user'", "row");
                    if (!empty($saldo->saldo)) {
                        $saldo_current = $saldo->saldo;
                        if ($saldo_current >= $total_bayar) {
                            $data_sche = [
                                "id" => d_nzm($id_server),
                                "data" => array(
                                    "name" => "sche_pppoe_" . $username,
                                    "on-event" => "/ppp secret disable [find name=" . $username . "]",
                                    "start-date" => date("M/d/Y",strtotime("+".$berlangganan." month")),
                                    "start-time" => "00:00:01",
                                    "policy" => "ftp,reboot,read,write,policy,test,password,sniff,sensitive",
                                    "disabled" => "no",
                                )
                            ];
                            $action = $this->router_process("sche/add", $data_sche);
                            if ($action == 200) {
                                $action = $this->router_process("pppoe_client/add", $data_router);
                                if($action == 200){
                                    $data_saldo = [
                                        "saldo" => $saldo_current - $total_bayar,
                                    ];

                                    $update_saldo = $this->model->update("user", "id='$id_user'", $data_saldo);
                                    $data_log = [
                                        "id_user" => $this->id_user,
                                        "id_mitra" => $this->id_mitra,
                                        "tanggal" => date("Y-m-d H:i:s"),
                                        "logs" => "Order PPPoE ".$username." berlangganan selama ".$berlangganan,
                                        "category" => "Create",
                                    ];
                                    $logs = $this->model->insert("log_activity_user",$data_log);
                                    $fb = [
                                        "title" => "Sukses",
                                        "pesan" => "PPPoE Client Berhasil Dibuat",
                                        "icon" => "success",
                                    ];
                                }else{
                                    $fb = [
                                        "title" => "Gagal",
                                        "pesan" => "PPPoE Client Gagal Dibuat<br>".$action["!trap"][0]["message"],
                                        "icon" => "error",
                                    ];
                                }
                            } else {
                                $fb = [
                                    "title" => "Gagal",
                                    "pesan" => "PPPoE Client Gagal Dibuat<br>".$action["!trap"][0]["message"],
                                    "icon" => "error",
                                ];
                            }
                        } else {
                            $fb = [
                                "title" => "Gagal",
                                "pesan" => "Saldo anda kurang, mohon top up saldo terlebih dahulu.",
                                "icon" => "error",
                            ];
                        }
                    }else{
                        $fb = [
                            "title" => "Gagal",
                            "pesan" => "User tidak ditemukan",
                            "icon" => "error",
                        ];
                    }
                } else {
                    $fb = [
                        "title" => "Gagal",
                        "pesan" => "Data paket tidak ditemukan, cobalah beberapa saat lagi",
                        "icon" => "error",
                    ];
                }
            }else{
                $fb = [
                    "title" => "Gagal",
                    "pesan" => "Username sudah terdaftar<br>Mohon buat username yang belum terdaftar",
                    "icon" => "error",
                ];
            }
        } else {
            $fb = [
                "title" => "Warning",
                "pesan" => validation_errors(),
                "icon" => "warning",
            ];
        }
        echo json_encode($fb);
        die();
    }

    public function list_order()
    {
        $this->form_validation->set_rules("id_server","Server","required|trim|xss_clean");
        if($this->form_validation->run() === TRUE){
            $id_server = $this->input->post("id_server");
            $data_router = [
                "id" => d_nzm($id_server),
            ];
            $td_load = '';
            $no = 1;
            $username_list = $this->router_process("pppoe_client/list", $data_router);
            $sche_list = $this->router_process("sche/list", $data_router);
            $profile_list = $this->router_process("profile_pppoe/list", $data_router);
            if(!empty($username_list)){
                if(array_keys(array_column($username_list, 'comment'))){
                    foreach ($username_list as $key => $value) {
                        $id_pppoe = e_nzm($value[".id"]);
                        if(!empty($value["comment"])){
                            $comment = json_decode($value["comment"],true);
                            if($comment["auto_debit"] == "1"){
                                $auto_debit = '<i class="fas fa-check-circle text-success"></i>';
                            }else{
                                $auto_debit = '<i class="fas fa-times-circle text-danger"></i>';
                            }
                            if($value["disabled"] == "false"){
                                $status = '<i class="fas fa-check-circle text-success"></i>';
                                $reactive = '<a class="dropdown-item btn-active" id="btn-active-'.$id_pppoe.'" data-s="0" data-e="'.strtotime($comment["expired_date"]).'" data-i="'.$id_pppoe.'" data-paket="'.$value['profile'].'" data-berlangganan="'.$comment['berlangganan'].'" data-debit="'.$comment['auto_debit'].'" href="javascript:void(0)">Disable</a>';
                            }else{
                                $status = '<i class="fas fa-times-circle text-danger"></i>';
                                $reactive = '<a class="dropdown-item btn-active" id="btn-active-'.$id_pppoe.'" data-s="1" data-e="'.strtotime($comment["expired_date"]).'" data-i="'.$id_pppoe.'" data-paket="'.$value['profile'].'" data-berlangganan="'.$comment['berlangganan'].'" data-debit="'.$comment['auto_debit'].'" href="javascript:void(0)">Re-Active</a>';
                            }
                            $key_sche = array_keys(array_column($sche_list, 'name'), "sche_pppoe_".$value["name"]);
                            if(!empty($key_sche)){
                                $id_sche = e_nzm($sche_list[$key_sche[0]][".id"]);
                            }else{
                                $id_sche = '';
                            }
                            
                            //GET PROFILE PPPOE
                            $key_profile = array_keys(array_column($profile_list, 'name'), $value["profile"]);
                            if(!empty($key_profile)){
                                $comment_profile = explode(";",$profile_list[$key_profile[0]]["comment"]);
                                $harga = $comment_profile[1];
                                $biaya_langganan = number_format($harga*$comment["berlangganan"],0,"",".");
                            }else{
                                $biaya_langganan = "Err";
                            }
                            $data_load = '
                            <tr id="row-'.$id_pppoe.'">
                                <td class="p-1 text-center align-middle">'.$no.'</td>
                                <td class="p-1 text-center align-middle">'.$comment["email"].'</td>
                                <td class="p-1 text-center align-middle" id="name-'.$id_pppoe.'">'.$value["name"].'</td>
                                <td class="p-1 text-center align-middle" id="password-'.$id_pppoe.'">'.$value["password"].'</td>
                                <td class="p-1 text-center align-middle" id="profile-'.$id_pppoe.'">'.$value["profile"].'</td>
                                <td class="p-1 text-center align-middle" id="date-order-'.$id_pppoe.'">'.date("d-M-Y",strtotime($comment["date_order"])).'</td>
                                <td class="p-1 text-center align-middle" id="expired-date-'.$id_pppoe.'">'.date("d-M-Y",strtotime($comment["expired_date"])).'</td>
                                <td class="p-1 text-center align-middle" id="berlangganan-'.$id_pppoe.'">'.$comment["berlangganan"].' Bulan</td>
                                <td class="p-1 text-center align-middle" id="biaya-langganan-'.$id_pppoe.'">'.$biaya_langganan.'</td>
                                <td class="p-1 text-center align-middle" id="auto-debit-'.$id_pppoe.'">'.$auto_debit.'</td>
                                <td class="p-1 text-center align-middle" id="status-'.$id_pppoe.'">'.$status.'</td>
                                <td class="p-1 text-center align-middle">
                                <div class="dropdown">
                                    <a href="javascript:void(0)" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis text-dark"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item btn-edit" id="btn-edit-'.$id_pppoe.'" href="javascript:void(0)" data-i="'.$id_pppoe.'" data-username="'.$value["name"].'" data-password="'.$value["password"].'" data-sche="'.$id_sche.'">Edit</a>
                                        
                                        <a class="dropdown-item btn-delete" data-i="'.$id_pppoe.'" data-sche="'.$id_sche.'" data-username="'.$value["name"].'" href="javascript:void(0)">Hapus</a>
                                        '.$reactive.'
                                    </div>
                                </div>
                                </td>
                            </tr>';
                            $no++;


                            if($this->role_id == "1"){
                                $td_load .= $data_load;
                            }else{
                                if($comment["id_user"] == $this->id_user){
                                    $td_load .= $data_load;
                                }
                            }
                        }
                    }
                }
            }
            $fb = [
                "data" => $td_load,
                "status" => "success",
            ];
        }else{
            $fb = [
                "title" => "Warning",
                "pesan" => validation_errors(),
                "icon" => "warning",
                "status" => "warning",
            ];
        }
        echo json_encode($fb);
        die();
    }

    public function simpan_edit()
    {
        $this->form_validation->set_rules("id_server","Server","required|trim|xss_clean");
        $this->form_validation->set_rules("id_sche","ID Schedule","required|trim|xss_clean");
        $this->form_validation->set_rules("id_pppoe","ID PPPoE","required|trim|xss_clean");
        $this->form_validation->set_rules("username","Username","required|trim|xss_clean");
        $this->form_validation->set_rules("password","Password","required|trim|xss_clean");
        if($this->form_validation->run() === TRUE){
            $id_pppoe = d_nzm($this->input->post('id_pppoe'));
            $id_sche = d_nzm($this->input->post('id_sche'));
            $id_server = d_nzm($this->input->post('id_server'));
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $data_router = [
                "id" => $id_server,
            ];

            $username_list = $this->router_process("pppoe_client/list", $data_router);
            $key_username = array_keys(array_column($username_list, 'name'), $username);
            if(!empty($key_username)){ //JIKA USERNAME OK
                if($username_list[$key_username[0]][".id"] == $id_pppoe){ //JIKA ID PPPOE AWAL DAN ID PPPOE TERDAFTAR ADALAH SAMA
                    $id = $username_list[$key_username[0]][".id"];
                    $data_sche = [
                        "id" => $id_server,
                        "data" => array(
                            ".id" => $id_sche,
                            "name" => "sche_pppoe_" . $username,
                            "on-event" => "/ppp secret disable [find name=" . $username . "]",
                        )
                    ];
                    $action = $this->router_process("sche/update", $data_sche);
                    if($action == 200){
                        $data = [
                            "id" => $id_server,
                            "data" => array(
                                ".id" => $id,
                                "name" => $username,
                                "password" => $password,
                            )
                        ];
                        $action = $this->router_process("pppoe_client/update", $data);
                        if($action == 200){
                            $data_log = [
                                "id_user" => $this->id_user,
                                "id_mitra" => $this->id_mitra,
                                "tanggal" => date("Y-m-d H:i:s"),
                                "logs" => "Edit PPPoE ".$username,
                                "category" => "Update",
                            ];
                            $logs = $this->model->insert("log_activity_user",$data_log);
                            $fb = [
                                "title" => "Sukses",
                                "pesan" => "PPPoE Berhasil Dirubah",
                                "icon" => "success",
                            ];
                        }else{
                            $fb = [
                                "title" => "Error",
                                "pesan" => "PPPoE Gagal Dirubah<br>".$action["!trap"][0]["message"],
                                "icon" => "error",
                            ];
                        }
                    }else{
                        $fb = [
                            "title" => "Error",
                            "pesan" => "PPPoE Gagal Dirubah<br>".$action["!trap"][0]["message"],
                            "icon" => "error",
                        ];
                    }
                }else{
                    $fb = [
                        "title" => "Error",
                        "pesan" => "Username sudah terdaftar, mohon buat username yang belum terdaftar.",
                        "icon" => "error",
                    ];
                }
            }else{
                $data_username = array_keys(array_column($username_list, '.id'), $id_pppoe);
                $id = $username_list[$data_username[0]][".id"];
                $data_sche = [
                    "id" => $id_server,
                    "data" => array(
                        ".id" => $id_sche,
                        "name" => "sche_pppoe_" . $username,
                        "on-event" => "/ppp secret disable [find name=" . $username . "]",
                    )
                ];
                $action = $this->router_process("sche/update", $data_sche);
                if($action == 200){
                    $data = [
                        "id" => $id_server,
                        "data" => array(
                            ".id" => $id,
                            "name" => $username,
                            "password" => $password,
                        )
                    ];
                    $action = $this->router_process("pppoe_client/update", $data);
                    if($action == 200){
                        $data_log = [
                            "id_user" => $this->id_user,
                            "id_mitra" => $this->id_mitra,
                            "tanggal" => date("Y-m-d H:i:s"),
                            "logs" => "Edit PPPoE ".$username,
                            "category" => "Update",
                        ];
                        $logs = $this->model->insert("log_activity_user",$data_log);
                        $fb = [
                            "title" => "Sukses",
                            "pesan" => "PPPoE Berhasil Dirubah",
                            "icon" => "success",
                        ];
                    }else{
                        $fb = [
                            "title" => "Gagal",
                            "pesan" => $action["!trap"][0]["message"],
                            "icon" => "error",
                        ];
                    }
                }else{
                    $fb = [
                        "title" => "Error",
                        "pesan" => $action["!trap"][0]["message"],
                        "icon" => "error",
                    ];
                }
            }
        }else{
            $fb = [
                "title" => "Warning",
                "pesan" => validation_errors(),
                "icon" => "warning",
            ];
        }
        echo json_encode($fb);
        die();
    }

    public function hapus_order()
    {
        $this->form_validation->set_rules("id_server","ID Server","required|trim|xss_clean");
        $this->form_validation->set_rules("id_pppoe","ID PPPoE","required|trim|xss_clean");
        $this->form_validation->set_rules("id_sche","ID Schedule","required|trim|xss_clean");
        $this->form_validation->set_rules("username","Username","required|trim|xss_clean");
        if($this->form_validation->run() === TRUE){
            $id_server = d_nzm($this->input->post("id_server"));
            $id_pppoe = d_nzm($this->input->post("id_pppoe"));
            $id_schedule = d_nzm($this->input->post("id_sche"));
            $username = $this->input->post("username");
            $data_sche = [
                "id" => $id_server,
                "data" => array(
                    ".id" => $id_schedule,
                )
            ];
            $action = $this->router_process("sche/remove",$data_sche);
            if($action == 200){
                $data_router = [
                    "id" => $id_server,
                    "data" => array(
                        ".id" => $id_pppoe,
                    )
                ];
                $action = $this->router_process("pppoe_client/remove",$data_router);
                if($action == 200){
                    $data_log = [
                        "id_user" => $this->id_user,
                        "id_mitra" => $this->id_mitra,
                        "tanggal" => date("Y-m-d H:i:s"),
                        "logs" => "Hapus PPPoE ".$username,
                        "category" => "Delete",
                    ];
                    $logs = $this->model->insert("log_activity_user",$data_log);
                    $fb = [
                        "title" => "Sukses",
                        "pesan" => "PPPoE Berhasil Di Hapus",
                        "icon" => "success",
                    ];
                }else{
                    $fb = [
                        "title" => "Error",
                        "pesan" => $action["!trap"][0]["message"],
                        "icon" => "error",
                    ];
                }
            }else{
                $fb = [
                    "title" => "Error",
                    "pesan" => $action["!trap"][0]["message"],
                    "icon" => "error",
                ];
            }
        }else{
            $fb = [
                "title" => "Warning",
                "pesan" => validation_errors(),
                "icon" => "warning",
            ];
        }

        echo json_encode($fb);
        die();
    }

    public function update_status()
    {
        $this->form_validation->set_rules("id_server","ID Server","required|trim|xss_clean");
        $this->form_validation->set_rules("id_pppoe","ID PPPoE","required|trim|xss_clean");
        $this->form_validation->set_rules("status","Status","required|trim|xss_clean");
        if($this->form_validation->run() === TRUE){
            $id_server = d_nzm($this->input->post("id_server"));
            $id_pppoe = d_nzm($this->input->post("id_pppoe"));
            $status = $this->input->post("status");
            if($status == "0"){
                $status = "yes";
                $pesan = "Non Aktifkan";
                $p = "Disabled";
                $icon_disable = '<i class="fas fa-times-circle text-danger"></i>';
            }else{
                $status = "no";
                $pesan = "Aktifkan";
                $p = "Enabled";
                $icon_disable = '<i class="fas fa-check-circle text-success"></i>';
            }

            $data_router = [
                "id" => $id_server,
                "data" => array(
                    ".id" => $id_pppoe,
                    "disabled" => $status,
                )
            ];

            //GET DATA ALL PPPOE CLIENT
            $data_pppoe = $this->router_process("pppoe_client/list",$data_router);
            $key_pppoe = array_keys(array_column($data_pppoe, '.id'), $id_pppoe);
            $comment = json_decode($data_pppoe[$key_pppoe[0]]["comment"],true);
            $expired_date = $comment["expired_date"]; //GET EXPIRED DATE PPPOE

            if(strtotime($expired_date) >= strtotime(date("Y-m-d"))){
                $action = $this->router_process("pppoe_client/update",$data_router);
                if($action == 200){
                    $data_log = [
                        "id_user" => $this->id_user,
                        "id_mitra" => $this->id_mitra,
                        "tanggal" => date("Y-m-d H:i:s"),
                        "logs" => $p." PPPoE ".$data_pppoe[$key_pppoe[0]]["name"],
                        "category" => "Update",
                    ];
                    $logs = $this->model->insert("log_activity_user",$data_log);
                    $fb = [
                        "title" => "Sukses",
                        "pesan" => "PPPoE berhasil di ".$pesan,
                        "icon" => "success",
                        "icon_disable" => $icon_disable,
                    ];
                }else{
                    $fb = [
                        "title" => "Error",
                        "pesan" => "PPPoE gagal update status<br>Err :<br>".$action["!trap"][0]["message"],
                        "icon" => "error",
                    ];
                }
            }else{
                $fb = [
                    "title" => "Error",
                    "pesan" => "PPPoE gagal update status<br>Err :<br>Masa aktif anda telah habis",
                    "icon" => "error",
                ];
            }
        }else{
            $fb = [
                "title" => "Warning",
                "pesan" => validation_errors(),
                "icon" => "warning",
            ];
        }

        echo json_encode($fb);
        die();
    }

    public function rubah_paket()
    {
        $this->form_validation->set_rules("id_server","ID Server","required|trim|xss_clean");
        $this->form_validation->set_rules("id_pppoe","ID PPPoE","required|trim|xss_clean");
        $this->form_validation->set_rules("paket","Paket","required|trim|xss_clean");
        $this->form_validation->set_rules("berlangganan","Berlangganan","required|trim|xss_clean|integer");
        $this->form_validation->set_rules("status_debit", "Status Debit", "required|trim|xss_clean|integer");
        if($this->form_validation->run() === TRUE){
            $id_server = d_nzm($this->input->post("id_server"));
            $id_pppoe = d_nzm($this->input->post("id_pppoe"));
            $paket = $this->input->post("paket");
            $berlangganan = $this->input->post("berlangganan");
            $status_debit = $this->input->post("status_debit");

            //SETUP DATA RETURN
            if($status_debit == "1"){
                $icon_auto_debit = '<i class="fas fa-check-circle text-success"></i>';
            }else{
                $icon_auto_debit = '<i class="fas fa-times-circle text-danger"></i>';
            }
            $data_return = [
                "paket" => $paket,
                "auto_debit" => $icon_auto_debit,
                "date_order" => date("d-M-Y"),
                "expired_date" => date("d-M-Y",strtotime("+".$berlangganan." month")),
                "berlangganan" => $berlangganan." Bulan",
                "status_disabled" => '<i class="fas fa-check-circle text-success"></i>',
            ];

            //CHECK SALDO USER
            $saldo = $this->model->gd("user","saldo","id = '".$this->id_user."'","row");
            if(!empty($saldo)){
                $saldo = $saldo->saldo;
            }else{
                $saldo = 0;
            }

            //HARGA PAKET
            $data_router = [
                "id" => $id_server,
            ];
            $profile_paket = $this->router_process("profile_pppoe/list",$data_router);
            $key_paket = array_keys(array_column($profile_paket, 'name'), $paket);
            $comment = explode(";",$profile_paket[$key_paket[0]]["comment"]);
            $harga_paket = $comment[1];
            $total_bayar = $harga_paket*$berlangganan;

            //CHECK SALDO VS TOTAL BAYAR
            if($saldo >= $total_bayar){
                //UPDATE PPPOE
                $new_comment = [
                    "service" => "PPPoE",
                    "date_order" => date("d-m-Y"),
                    "berlangganan" => $berlangganan,
                    "expired_date" => date("d-m-Y",strtotime("+".$berlangganan." month")),
                    "auto_debit" => $status_debit,
                    "id_user" => $this->id_user,
                    "email" => $this->email,
                ];

                //CHECK USERNAME
                $get_pppoe_client = $this->router_process("pppoe_client/list",$data_router);
                $key_pppoe_client = array_keys(array_column($get_pppoe_client, '.id'), $id_pppoe);
                $username = $get_pppoe_client[$key_pppoe_client[0]]["name"];

                //CHECK DATA SCHEDULE
                $get_schedule = $this->router_process("sche/list",$data_router);
                $key_sche = array_keys(array_column($get_schedule, 'name'), "sche_pppoe_".$username);

                //SETUP SCHEDULE
                $data_sche = [
                    "id" => $id_server,
                    "data" => array(
                        ".id" => $get_schedule[$key_sche[0]][".id"],
                        "start-date" => date("M/d/Y",strtotime("+".$berlangganan." month")),
                    )
                ];

                $action = $this->router_process("sche/update",$data_sche);
                if($action == 200){
                    $data_pppoe = [
                        "id" => $id_server,
                        "data" => array(
                            ".id" => $id_pppoe,
                            "disabled" => "no",
                            "profile" => $paket,
                            "comment" => json_encode($new_comment)
                        )
                    ];

                    $action = $this->router_process("pppoe_client/update",$data_pppoe);
                    if($action == 200){
                        $new_saldo = [
                            "saldo" => $saldo - $total_bayar,
                        ];
                        $update_saldo = $this->model->update("user","id = '".$this->id_user."'",$new_saldo);
                        $data_log = [
                            "id_user" => $this->id_user,
                            "id_mitra" => $this->id_mitra,
                            "tanggal" => date("Y-m-d H:i:s"),
                            "logs" => "Re-Activated PPPoE ".$username,
                            "category" => "Update",
                        ];
                        $logs = $this->model->insert("log_activity_user",$data_log);
                        $fb = [
                            "title" => "Sukses",
                            "pesan" => "PPPoE Berhasil Di Aktifkan",
                            "icon" => "success",
                            "data" => $data_return,
                        ];
                    }else{
                        $fb = [
                            "title" => "Gagal",
                            "pesan" => "PPPoE Gagal Di Aktifkan<br>".$action["!trap"][0]["message"],
                            "icon" => "error",
                        ];
                    }
                }else{
                    $fb = [
                        "title" => "Gagal",
                        "pesan" => "PPPoE Gagal Di Aktifkan<br>".$action["!trap"][0]["message"],
                        "icon" => "error",
                    ];
                }
            }else{
                $fb = [
                    "title" => "Gagal",
                    "pesan" => "Saldo Anda Kurang",
                    "icon" => "error",
                ];
            }
        }else{
            $fb = [
                "title" => "Warning",
                "pesan" => validation_errors(),
                "icon" => "warning",
            ];
        }

        echo json_encode($fb);
        die();
    }

    public function check_status()
    {
        //GET DATA SERVER ALL
        $server = $this->model->gd("api_routeros","id","id_mitrar = '".$this->id_mitra."' AND is_active = '1'","result");
        if(!empty($server)){
            foreach ($server as $server) {
                $id_server = $server->id;
                $data_router = [
                    "id" => $id_server,
                ];
                $pppoe_list = $this->router_process("pppoe_client/list", $data_router);
                $profile_pppoe = $this->router_process("profile_pppoe/list", $data_router);
                $data_disable = array_keys(array_column($pppoe_list, 'disabled'), 'true');
                if(!empty($data_disable)){
                    foreach ($data_disable as $k_dis => $v_dis) {
                        $comment = json_decode($pppoe_list[$v_dis]["comment"], true);
                        $id_user = $comment["id_user"];
                        $auto_debit = $comment["auto_debit"];
                        $expired_date = $comment["expired_date"];
                        $paket = $pppoe_list[$v_dis]["profile"];
                        $id_pppoe = $pppoe_list[$v_dis][".id"];
                        $username = $pppoe_list[$v_dis]["name"];
                        $berlangganan = $comment["berlangganan"];
                        $email = $comment["email"];
                        $service = $pppoe_list[$v_dis]["service"];

                        if($service == "pppoe"){
                            if($auto_debit == "1"){
                                //JIKA HARI INI LEBIH BESAR DARI EXPIRED DATE
                                if(strtotime(date("Y-m-d")) > strtotime($expired_date)){
                                    //CHECK SALDO
                                    $saldo = $this->model->gd("user","saldo","id = '$id_user'","row");
                                    if(!empty($saldo->saldo)){
                                        $saldo = $saldo->saldo;
                                        
                                        //CHECK BIAYA LANGGANAN (HARGA PPPOE * BERLANGGANAN)
                                        $data_paket = array_keys(array_column($profile_pppoe, 'name'), $paket);
                                        if(!empty($data_paket)){
                                            $harga = explode(";",$profile_pppoe[$data_paket[0]]["comment"]);
                                            $harga = $harga[1];
            
                                            $biaya_langganan = $harga*$berlangganan;
                                            
                                            if($saldo >= $biaya_langganan){
                                                //CHECK DATA SCHEDULE
                                                $get_schedule = $this->router_process("sche/list",$data_router);
                                                $key_sche = array_keys(array_column($get_schedule, 'name'), "sche_pppoe_".$username);
                                
                                                //SETUP SCHEDULE
                                                $data_sche = [
                                                    "id" => $id_server,
                                                    "data" => array(
                                                        ".id" => $get_schedule[$key_sche[0]][".id"],
                                                        "start-date" => date("M/d/Y",strtotime("+".$berlangganan." month")),
                                                    )
                                                ];
            
                                                $action = $this->router_process("sche/update",$data_sche);
                                                if($action == 200){
                                                    $new_comment = [
                                                        "service" => "PPPoE",
                                                        "date_order" => date("d-m-Y"),
                                                        "berlangganan" => $berlangganan,
                                                        "expired_date" => date("d-m-Y",strtotime("+".$berlangganan." month")),
                                                        "auto_debit" => "1",
                                                        "id_user" => $id_user,
                                                        "email" => $comment['email'],
                                                    ];
            
                                                    $data_pppoe = [
                                                        "id" => $id_server,
                                                        "data" => array(
                                                            ".id" => $id_pppoe,
                                                            "disabled" => "no",
                                                            "comment" => json_encode($new_comment)
                                                        )
                                                    ];
                                
                                                    $action = $this->router_process("pppoe_client/update",$data_pppoe);
                                                    if($action == 200){
                                                        $new_saldo = [
                                                            "saldo" => $saldo - $biaya_langganan,
                                                        ];
                                                        $update_saldo = $this->model->update("user","id = '$id_user'",$new_saldo);
                                                        $data_log = [
                                                            "id_user" => $id_user,
                                                            "id_mitra" => $this->id_mitra,
                                                            "tanggal" => date("Y-m-d H:i:s"),
                                                            "logs" => "Perpanjangan PPPoE ".$username,
                                                            "category" => "Update",
                                                        ];
                                                        $logs = $this->model->insert("log_activity_user",$data_log);
                                                        $to = $email;
                                                        $subject = "Sukses Perpanjangan PPPoE";
                                                        $message = "Proses perpanjangan PPPoE anda atas,<br>
                                                        Username : ".$username."<br>
                                                        Sisa Saldo : ".$new_saldo["saldo"]."<br><br>
                                                        Telah berhasil di lakukan dengan detail di bawah ini :<br>
                                                        Date Order : ".$new_comment["date_order"]."<br>
                                                        Expired Date : ".$new_comment["expired_date"]."<br>
                                                        Berlangganan : ".$new_comment["berlangganan"]." Bulan<br>
                                                        Biaya Langganan : ".number_format($biaya_langganan,0,"",".")."<br>
                                                        <br>Terimakasih";
                                                        $fb = [
                                                            "res" => 200,
                                                            "message" => "Sukses",
                                                        ];
                                                    }else{
                                                        $to = $email;
                                                        $subject = "Error Perpanjangan PPPoE";
                                                        $message = "Mohon maaf untuk PPPoE atas,<br>
                                                        Username : ".$username."<br>
                                                        Mengalami error saat ingin melakukan aktivasi otomatis.<br>
                                                        <br>Err :<br>
                                                        ".$action["!trap"][0]["message"]."<br>
                                                        Anda bisa melakukan aktivasi secara menual melalui website <a href='".base_url()."'>disini</a><br>
                                                        <br>Terimakasih";
                                                        $fb = [
                                                            "res" => 500,
                                                            "message" => "Error : ".$action["!trap"][0]["message"],
                                                        ];
                                                    }
                                                }else{
                                                    $to = $email;
                                                    $subject = "Error Perpanjangan PPPoE";
                                                    $message = "Mohon maaf untuk PPPoE atas,<br>
                                                    Username : ".$username."<br>
                                                    Mengalami error saat ingin melakukan aktivasi otomatis.<br>
                                                    <br>Err :<br>
                                                    ".$action["!trap"][0]["message"]."<br>
                                                    Anda bisa melakukan aktivasi secara menual melalui website <a href='".base_url()."'>disini</a><br>
                                                    <br>Terimakasih";
                                                    $fb = [
                                                        "res" => 500,
                                                        "message" => "Error : ".$action["!trap"][0]["message"],
                                                    ];
                                                }
                                            }else{
                                                $to = $email;
                                                $subject = "Perpanjangan PPPoE";
                                                $message = "Mohon maaf untuk PPPoE atas,<br>
                                                Username : ".$username."<br>
                                                Telah di Non Aktifkan karena saldo anda kurang untuk melakukan aktivasi PPPoE anda.<br>
                                                Anda bisa melakukan aktivasi secara menual melalui website <a href='".base_url()."'>disini</a><br>
                                                <br>Terimakasih";
                                                $fb = [
                                                    "res" => 500,
                                                    "message" => "Saldo Kurang",
                                                ];
                                            }
                                            $send_email = $this->email_send($to,$subject,$message);
                                        }else{
                                            $fb = [
                                                "res" => 500,
                                                "message" => "Profile PPPoE Tidak Ditemukan",
                                            ];
                                        }
                                    }else{
                                        $fb = [
                                            "res" => 500,
                                            "message" => "Data User Tidak Ditemukan",
                                        ];
                                    }
                                    echo json_encode($fb);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
