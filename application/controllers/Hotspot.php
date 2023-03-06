<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Hotspot extends MY_Controller
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

    public function ip_address()
    {
        $this->form_validation->set_rules("id_server", "ID Server", "required|trim|xss_clean");
        $this->form_validation->set_rules("interface", "Interface", "required|trim|xss_clean");
        if ($this->form_validation->run() === TRUE) {
            $id_server = $this->input->post("id_server");
            $interface = $this->input->post("interface");
            $ip_address = [
                "id" => d_nzm($id_server),
                "proses" =>  '/ip/address/getall',
                "data" => array(),
            ];
            $list = $this->router_process("router", $ip_address);
            $option = '';
            if (!empty($list)) {
                if (!empty(array_keys(array_column($list, "interface"), $interface))) {
                    foreach ($list as $key => $value) {
                        if ($value["interface"] == $interface) {
                            if ($value["disabled"] == "false") {
                                $option .= '<option>' . $value["address"] . '</option>';
                            }
                        }
                    }
                } else {
                    $option = '<option value="">Local Address Kosong</option>';
                }
            } else {
                $option = '<option value="">Local Address Kosong</option>';
            }
        } else {
            $option = '<option value="">Err: Validation Error</option>';
        }
        echo $option;
        die();
    }

    public function element_ip_pool()
    {
        $this->form_validation->set_rules("local_address", "Local Address", "required|trim|xss_clean");
        if ($this->form_validation->run() === TRUE) {
            $local_address = $this->input->post("local_address");
            $local_exp = explode(".", $local_address);
            $current_num = explode("/", end($local_exp));
            if ($current_num[0] > 1) {
                if ($current_num[0] >= 254) {
                    $ip_address_1 = $local_exp[0] . "." . $local_exp[1] . "." . $local_exp[2] . ".1";
                    $ip_address_2 = $local_exp[0] . "." . $local_exp[1] . "." . $local_exp[2] . ".253";
                    $element_ip_address = '
                    <div class="input-group">
                        <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address 1" value="' . $ip_address_1 . '">
                        <span class="p-2">-</span>
                        <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address 2" value="' . $ip_address_2 . '">
                    </div>';

                    $ip_address = $ip_address_1 . "-" . $ip_address_2;
                } else {
                    $ip_address_1 = $local_exp[0] . "." . $local_exp[1] . "." . $local_exp[2] . ".1";
                    $ip_address_2 = $local_exp[0] . "." . $local_exp[1] . "." . $local_exp[2] . "." . intval($current_num[0] - 1);
                    $ip_address_3 = $local_exp[0] . "." . $local_exp[1] . "." . $local_exp[2] . "." . intval($current_num[0] + 1);
                    $ip_address_4 = $local_exp[0] . "." . $local_exp[1] . "." . $local_exp[2] . ".254";
                    $element_ip_address = '
                    <div class="input-group">
                        <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address 1" value="' . $ip_address_1 . '">
                        <span class="p-2">-</span>
                        <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address 2" value="' . $ip_address_2 . '">
                    </div>
                    <div class="input-group">
                        <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address 3" value="' . $ip_address_3 . '">
                        <span class="p-2">-</span>
                        <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address 4" value="' . $ip_address_4 . '">
                    </div>';
                }
            } else {
                $ip_address_1 = $local_exp[0] . "." . $local_exp[1] . "." . $local_exp[2] . "." . intval($current_num[0] + 1);
                $ip_address_2 = $local_exp[0] . "." . $local_exp[1] . "." . $local_exp[2] . ".254";
                $element_ip_address = '
                <div class="input-group">
                    <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address 1" value="' . $ip_address_1 . '">
                    <span class="p-2">-</span>
                    <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address 2" value="' . $ip_address_2 . '">
                </div>';
            }
        } else {
            $element_ip_address = validation_errors();
        }
        echo $element_ip_address;
        die();
    }

    public function setup_edit()
    {
        $this->form_validation->set_rules("id_server", "Server", "required|trim|xss_clean");
        $this->form_validation->set_rules("id_hotspot", "ID Hotspot", "required|trim|xss_clean");
        $this->form_validation->set_rules("name", "Service Name", "required|trim|xss_clean");
        $this->form_validation->set_rules("interface", "Interface Name", "required|trim|xss_clean");
        $this->form_validation->set_rules("ip_dns", "IP DNS", "required|trim|xss_clean");
        if ($this->form_validation->run() === TRUE) {
            $id_server = d_nzm($this->input->post("id_server"));
            $id_hotspot = d_nzm($this->input->post("id_hotspot"));
            $name = $this->input->post("name");
            $interface = $this->input->post("interface");
            $ip_dns = $this->input->post("ip_dns");
            //GET IP POOL 
            $data_router_ip_pool = [
                "id" => $id_server,
                "proses" =>  '/ip/pool/getall',
                "data" => array(),
            ];
            $get_ip_pool = $this->router_process("router", $data_router_ip_pool);
            $key_ip_pool = array_keys(array_column($get_ip_pool, "name"), $name);
            if (!empty($key_ip_pool)) {
                $addresses_ip_pool = $get_ip_pool[$key_ip_pool[0]]["ranges"];
                $jumlah_ip = substr_count($addresses_ip_pool, ",");
                if ($jumlah_ip > 0) {
                    $expl_ip = explode(",", $addresses_ip_pool);
                    $element_ip_address = '';
                    for ($i = 0; $i <= $jumlah_ip; $i++) {
                        $expl_ip_sub = explode("-", $expl_ip[$i]);
                        $element_ip_address .= '
                        <div class="input-group">
                            <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address" value="' . $expl_ip_sub[0] . '">
                            <span class="p-2">-</span>
                            <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address" value="' . $expl_ip_sub[1] . '">
                        </div>';
                    }
                } else {
                    $expl_ip_sub = explode("-", $addresses_ip_pool);
                    $element_ip_address = '
                    <div class="input-group">
                        <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address" value="' . $expl_ip_sub[0] . '">
                        <span class="p-2">-</span>
                        <input type="text" class="form-control form-clear mb-2 rounded ip-pool-address" placeholder="IP Address" value="' . $expl_ip_sub[1] . '">
                    </div>';
                }
                $id_ip_pool = $get_ip_pool[$key_ip_pool[0]][".id"];
            } else {
                $element_ip_address = "";
                $id_ip_pool = "";
            }

            //GET PROFILE SERVER
            $data_router_profile = [
                "id" => $id_server,
                "proses" =>  '/ip/hotspot/profile/getall',
                "data" => array(),
            ];
            $get_profile = $this->router_process("router", $data_router_profile);
            $key_profile = array_keys(array_column($get_profile, "name"), $name);
            if (!empty($key_profile)) {
                $dns_name = $get_profile[$key_profile[0]]["dns-name"];
                $login_by = $get_profile[$key_profile[0]]["login-by"];
                $time_cookie_hours = 0;
                $time_cookie_minutes = 0;
                $time_cookie_seconds = 0;
                if (!empty($get_profile[$key_profile[0]]["http-cookie-lifetime"])) {
                    $cookie_lifetime_format = str_replace("w", "w ", str_replace("d", "d ", str_replace("h", "h ", str_replace("m", "m ", str_replace("s", "s ", $get_profile[$key_profile[0]]["http-cookie-lifetime"])))));
                    $ecl = explode(" ", $cookie_lifetime_format);
                    for ($i = 0; $i <= substr_count($cookie_lifetime_format, " "); $i++) {
                        if (preg_match('/h/', $ecl[$i])) { //CHECK HOURS
                            $time_cookie_hours = str_replace("h", "", $ecl[$i]);
                        } else if (preg_match('/m/', $ecl[$i])) { //CHECK MINUTES
                            $time_cookie_minutes = str_replace("m", "", $ecl[$i]);
                        } else if (preg_match('/s/', $ecl[$i])) { //CHECK SECONDS
                            $time_cookie_seconds = str_replace("s", "", $ecl[$i]);
                        }
                    }
                }

                $time_trial_hours = 0;
                $time_trial_minutes = 0;
                $time_trial_seconds = 0;
                if (!empty($get_profile[$key_profile[0]]["trial-uptime"])) {
                    $trial_uptime = explode("/", $get_profile[$key_profile[0]]["trial-uptime"]);
                    $trial_lifetime_format = str_replace("w", "w ", str_replace("d", "d ", str_replace("h", "h ", str_replace("m", "m ", str_replace("s", "s ", $trial_uptime[0])))));
                    $ecl = explode(" ", $trial_lifetime_format);
                    for ($i = 0; $i <= substr_count($trial_lifetime_format, " "); $i++) {
                        if (preg_match('/h/', $ecl[$i])) { //CHECK HOURS
                            $time_trial_hours = str_replace("h", "", $ecl[$i]);
                        } else if (preg_match('/m/', $ecl[$i])) { //CHECK MINUTES
                            $time_trial_minutes = str_replace("m", "", $ecl[$i]);
                        } else if (preg_match('/s/', $ecl[$i])) { //CHECK SECONDS
                            $time_trial_seconds = str_replace("s", "", $ecl[$i]);
                        }
                    }
                }
                if (preg_match('/\bcookie\b/', $login_by)) {
                    $cookie = "Yes";
                } else {
                    $cookie = "No";
                }
                if (preg_match('/\btrial\b/', $login_by)) {
                    $trial = "Yes";
                } else {
                    $trial = "No";
                }
                $id_profile = $get_profile[$key_profile[0]][".id"];
            } else {
                $dns_name = "";
                $cookie =  "No";
                $trial =  "No";
                $id_profile = "";
            }

            //GET USER & PASSWORD
            $data_router_user = [
                "id" => $id_server,
                "proses" =>  '/ip/hotspot/user/getall',
                "data" => array(),
            ];
            $get_user = $this->router_process("router", $data_router_user);
            $key_user = array_keys(array_column($get_user, "comment"), $name);
            if (!empty($key_user)) {
                $username = $get_user[$key_user[0]]["name"];
                $password = $get_user[$key_user[0]]["password"];
                $id_user = $get_user[$key_user[0]][".id"];
            } else {
                $username = "";
                $password = "";
                $id_user = "";
            }

            //GET PROFILE SERVER
            $data_router_ipaddress = [
                "id" => $id_server,
                "proses" =>  '/ip/address/getall',
                "data" => array(),
            ];
            $get_ipaddress = $this->router_process("router", $data_router_ipaddress);
            $json_ipaddress = json_encode($get_ipaddress);
            if (preg_match('/\b' . $ip_dns . '\b/', $json_ipaddress)) {
                foreach ($get_ipaddress as $key => $value) {
                    $ip = explode("/", $value["address"]);
                    if ($ip[0] == $ip_dns) {
                        $ip_address = $value["address"];
                    }
                }
            } else {
                $ip_address = "";
            }

            //GET DHCP SERVER
            $data_router_dhcp_server = [
                "id" => $id_server,
                "proses" =>  '/ip/dhcp-server/getall',
                "data" => array(),
            ];
            $get_dhcp_server = $this->router_process("router", $data_router_dhcp_server);
            $key_dhcp_server = array_keys(array_column($get_dhcp_server, "name"), $name);
            if (!empty($key_dhcp_server)) {
                $id_dhcp_server = $get_dhcp_server[$key_dhcp_server[0]][".id"];
            } else {
                $id_dhcp_server = "";
            }

            //GET FIREWALL NAT
            $data_router_firewall_nat = [
                "id" => $id_server,
                "proses" =>  '/ip/firewall/nat/getall',
                "data" => array(),
            ];
            $get_firewall_nat = $this->router_process("router", $data_router_firewall_nat);
            $key_firewall_nat = array_keys(array_column($get_firewall_nat, "comment"), "masquerade hotspot network, " . $name);
            if (!empty($key_firewall_nat)) {
                $id_firewall_nat = $get_firewall_nat[$key_firewall_nat[0]][".id"];
            } else {
                $id_firewall_nat = "";
            }

            $fb = [
                "icon" => "success",
                "element_ip_address" => $element_ip_address,
                "dns_name" => $dns_name,
                "cookie" => $cookie,
                "time_cookie_hours" => $time_cookie_hours * 1,
                "time_cookie_minutes" => $time_cookie_minutes * 1,
                "time_cookie_seconds" => $time_cookie_seconds * 1,
                "trial" => $trial,
                "time_trial_hours" => $time_trial_hours * 1,
                "time_trial_minutes" => $time_trial_minutes * 1,
                "time_trial_seconds" => $time_trial_seconds * 1,
                "username" => $username,
                "password" => $password,
                "ip_address" => $ip_address,
                "id_ip_pool" => e_nzm($id_ip_pool),
                "id_profile" => e_nzm($id_profile),
                "id_user" => e_nzm($id_user),
                "id_dhcp_server" => e_nzm($id_dhcp_server),
                "id_firewall_nat" => e_nzm($id_firewall_nat),
            ];
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

    public function list_hotspot_servers($p)
    {
        if (!empty($p)) {
            $data_router = [
                "id" => d_nzm($p),
                "proses" =>  '/ip/hotspot/getall',
                "data" => array(),
            ];
            $list = $this->router_process("router", $data_router);
            if (!empty($list)) {
                $td_load = '';
                $option_load = '';
                $no = 1;
                foreach ($list as $key => $value) {
                    //LOAD TABLE ROW
                    $id = $value[".id"];
                    if ($value["disabled"] == "false") {
                        $status = '<i class="fas fa-check-circle text-success" title="Active" style="cursor:pointer;"></i>';
                    } else {
                        $status = '<i class="fas fa-times-circle text-danger" title="Non Active" style="cursor:pointer;"></i>';
                    }

                    if (!empty($value["ip-of-dns-name"])) {
                        $ip_of_dns = $value["ip-of-dns-name"];
                    } else {
                        $ip_of_dns = "0.0.0.0";
                    }
                    $td_load .= '
                    <tr class="text-center">
                        <td class="align-middle p-1">' . $no . '</td>
                        <td class="align-middle p-1">' . $value["name"] . '</td>
                        <td class="align-middle p-1"><div style="min-width:70px;">' . $value["interface"] . '</div></td>
                        <td class="align-middle p-1">' . $value["address-pool"] . '</td>
                        <td class="align-middle p-1">' . $value["profile"] . '</td>
                        <td class="align-middle p-1">' . $status . '</td>
                        <td class="align-middle p-1">
                            <a href="javascript:void(0)" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis text-dark"></i>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a href="javascript:void(0)" class="dropdown-item btn-edit-hotspot-servers" id="btn-edit-hotspot-servers-' . e_nzm($id) . '" data-name="' . $value["name"] . '" data-n="' . str_replace("-Hotspot", "", $value["name"]) . '" data-interface="' . $value["interface"] . '" data-address-pool="' . $value["address-pool"] . '" data-profile="' . $value["profile"] . '" data-ip-dns="' . $ip_of_dns . '" data-id="' . e_nzm($id) . '" data-dis="' . $value["disabled"] . '">Edit</a>
                                
                                <a class="dropdown-item btn-delete-hotspot-servers" data-id="' . e_nzm($id) . '" href="javascript:void(0)">Hapus</a>
                            </div>
                        </td>
                    </tr>';
                    $option_load = '<option>' . $value["name"] . '</option>';
                    $no++;
                }
            } else {
                $td_load = '
                <tr>
                    <td colspan="10" align="center"><i class="text-danger">Data Hotspot Servers Kosong</i></td>
                </tr>';
                $option_load = '<option>Data Hotspot Servers Kosong</option>';
            }
        } else {
            $td_load = '
            <tr>
                <td colspan="5" align="center"><i class="text-danger">Silahkan pilih server di atas</i></td>
            </tr>';
            $option_load = '<option>Server belum dipilih</option>';
        }

        $fb = [
            "td_load" => $td_load,
            "option_load" => $option_load,
        ];
        echo json_encode($fb);
        die();
    }

    public function simpan_hotspot_servers($p)
    {
        if (!empty($p)) {
            $this->form_validation->set_rules('id_server', 'Server', 'required|trim|xss_clean');
            $this->form_validation->set_rules('service_name', 'Service Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('interface', 'Interface', 'required|trim|xss_clean');
            $this->form_validation->set_rules('local_address', 'Local Address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('dns_name_server', 'DNS Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('ip_pool_address', 'IP Pool Address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('username', 'Username', 'required|trim|xss_clean');
            $this->form_validation->set_rules('password', 'Password', 'required|trim|xss_clean');
            $this->form_validation->set_rules('cookie', 'Cookie', 'required|trim|xss_clean');
            $this->form_validation->set_rules('is_active', 'Status', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('time_cookie_hours', 'Cookie Lifetime Hours', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('time_cookie_minutes', 'Cookie Lifetime Minutes', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('time_cookie_seconds', 'Cookie Lifetime Seconds', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('trial', 'Trial Status', 'required|trim|xss_clean');
            $this->form_validation->set_rules('time_trial_hours', 'Trial Lifetime Hours', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('time_trial_minutes', 'Trial Lifetime Minutes', 'required|trim|xss_clean|integer');
            $this->form_validation->set_rules('time_trial_seconds', 'Trial Lifetime Seconds', 'required|trim|xss_clean|integer');
            if ($p != "add") {
                $this->form_validation->set_rules('id_ip_pool', 'ID IP Pool', 'required|trim|xss_clean');
                $this->form_validation->set_rules('id_profile', 'ID Profile', 'required|trim|xss_clean');
                $this->form_validation->set_rules('id_user', 'ID User', 'required|trim|xss_clean');
                $this->form_validation->set_rules('id_dhcp_server', 'ID DHCP Server', 'required|trim|xss_clean');
                $this->form_validation->set_rules('id_firewall_nat', 'ID Firewall NAT', 'required|trim|xss_clean');
            }
            if ($this->form_validation->run() === TRUE) {
                if ($this->input->post("is_active") == "1") {
                    $disabled = 'no';
                } else {
                    $disabled = 'yes';
                }
                $id_server = d_nzm($this->input->post("id_server"));
                $service_name = $this->input->post("service_name") . "-Hotspot";
                $interface = $this->input->post("interface");
                $local_address = $this->input->post("local_address");
                $ip_pool_address = $this->input->post("ip_pool_address");
                $username = $this->input->post("username");
                $password = $this->input->post("password");
                $cookie = $this->input->post("cookie");
                $time_cookie_hours = sprintf("%02d", $this->input->post("time_cookie_hours"));
                $time_cookie_minutes = sprintf("%02d", $this->input->post("time_cookie_minutes"));
                $time_cookie_seconds = sprintf("%02d", $this->input->post("time_cookie_seconds"));
                $time_cookie = $time_cookie_hours . ":" . $time_cookie_minutes . ":" . $time_cookie_seconds;
                $trial = $this->input->post("trial");
                $time_trial_hours = sprintf("%02d", $this->input->post("time_trial_hours"));
                $time_trial_minutes = sprintf("%02d", $this->input->post("time_trial_minutes"));
                $time_trial_seconds = sprintf("%02d", $this->input->post("time_trial_seconds"));
                $time_trial = $time_trial_hours . ":" . $time_trial_minutes . ":" . $time_trial_seconds;
                $local_address_exp = explode("/", $local_address);
                $hotspot_address = $local_address_exp[0];
                $dns_name = strtolower($this->input->post("dns_name_server"));
                $login_by = "http-chap";
                if ($cookie == "Yes") {
                    $login_by .= ",cookie";
                }

                if ($trial == "Yes") {
                    $login_by .= ",trial";
                }
                if ($p == "add") {
                    //ADD PPPOE SERVER
                    // ADD FIREWALL NAT PASSTHROUGH
                    $data_add = [
                        "id" => $id_server,
                        "proses" =>  '/ip/firewall/nat/add',
                        "data" => array(
                            "chain" => "unused-hs-chain",
                            "action" => "passthrough",
                            "comment" => "place hotspot rules here, " . $service_name,
                            "disabled" => "yes",
                        ),
                    ];
                    $add_fw_passthrough = $this->router_process("router", $data_add);
                    if ($add_fw_passthrough == 200) {
                        // ADD FIREWALL NAT HOTSPOT
                        $data_add = [
                            "id" => $id_server,
                            "proses" =>  '/ip/firewall/nat/add',
                            "data" => array(
                                "chain" => "srcnat",
                                "action" => "masquerade",
                                "src-address" => $local_address,
                                "comment" => "masquerade hotspot network, " . $service_name,
                                "disabled" => $disabled,
                            ),
                        ];
                        $add_fw_hotspot = $this->router_process("router", $data_add);
                        if ($add_fw_hotspot == 200) {
                            //ADD IP POOL HOTSPOT
                            $ip_pool_exp = explode(";", $ip_pool_address);
                            $count_ip_pool = substr_count($ip_pool_address, ";");
                            if ($count_ip_pool > 2) {
                                $ip_pool = $ip_pool_exp[0] . "-" . $ip_pool_exp[1] . "," . $ip_pool_exp[2] . "-" . $ip_pool_exp[3];
                            } else {
                                $ip_pool = $ip_pool_exp[0] . "-" . $ip_pool_exp[1];
                            }
                            $data_add = [
                                "id"  => d_nzm($this->input->post("id_server")),
                                "proses" =>  '/ip/pool/add',
                                "data" => array(
                                    "name" => $service_name,
                                    "ranges" => $ip_pool,
                                )
                            ];
                            $add_ip_pool = $this->router_process("router", $data_add);
                            if ($add_ip_pool == 200) {
                                //ADD DHCP SERVER
                                $data_add = [
                                    "id" => $id_server,
                                    "proses" =>  '/ip/dhcp-server/add',
                                    "data" => array(
                                        "name" => $service_name,
                                        "interface" => $interface,
                                        "address-pool" => $service_name,
                                        "lease-time" => "01:00:00",
                                        "bootp-support" => "static",
                                        "authoritative" => "after-2sec-delay",
                                        "disabled" => $disabled,
                                    ),
                                ];
                                $add_dhcp_server = $this->router_process("router", $data_add);
                                if ($add_dhcp_server == 200) {
                                    //ADD PROFILE SERVER
                                    $data_add = [
                                        "id" => $id_server,
                                        "proses" =>  '/ip/hotspot/profile/add',
                                        "data" => array(
                                            "name" => $service_name,
                                            "hotspot-address" => $hotspot_address,
                                            "dns-name" => $dns_name,
                                            "html-directory" => "hotspot",
                                            "login-by" => $login_by,
                                            "http-cookie-lifetime" => $time_cookie,
                                            "trial-uptime" => $time_trial . "/1d 00:00:00",
                                        ),
                                    ];
                                    $add_hotspot_profile_server = $this->router_process("router", $data_add);
                                    if ($add_hotspot_profile_server == 200) {
                                        //ADD USER HOTSPOT ADMIN
                                        $data_add = [
                                            "id" => $id_server,
                                            "proses" =>  '/ip/hotspot/user/add',
                                            "data" => array(
                                                "name" => $username,
                                                "password" => $password,
                                                "profile" => "default",
                                                "disabled" => $disabled,
                                                "comment" => $service_name,
                                            ),
                                        ];
                                        $add_hotspot_user = $this->router_process("router", $data_add);
                                        if ($add_hotspot_user == 200) {
                                            //ADD HOTSPOT SERVER
                                            $data_add = [
                                                "id" => $id_server,
                                                "proses" =>  '/ip/hotspot/add',
                                                "data" => array(
                                                    "name" => $service_name,
                                                    "interface" => $interface,
                                                    "address-pool" => $service_name,
                                                    "profile" => $service_name,
                                                    "disabled" => $disabled,
                                                ),
                                            ];
                                            $add_hotspot = $this->router_process("router", $data_add);
                                        } else {
                                            $add_hotspot = "Gagal membuat hotspot server";
                                        }
                                    } else {
                                        $add_hotspot = "Gagal membuat hotspot profile server, Mungkin sudah terdapat profile dengan nomor IP Address yang sama, mohon untuk bisa di hapus terlebih dahulu di dalam winbox.";
                                    }
                                } else {
                                    $add_hotspot = "Gagal membuat DHCP Server";
                                }
                            } else {
                                $add_hotspot = "Gagal membuat IP Pool Server";
                            }
                        } else {
                            $add_hotspot = "Gagal membuat Firewall NAT";
                        }
                    } else {
                        $add_hotspot = "Gagal membuat Firewall NAT Passthrough";
                    }

                    if ($add_hotspot == 200) {
                        $fb = [
                            'title' => 'Sukses',
                            'pesan' => "Hotspot Server Berhasil Dibuat",
                            'icon' => 'success',
                        ];
                    } else {
                        $fb = [
                            'title' => 'Error',
                            'pesan' => "Hotspot Server Gagal Dibuat.<br><br>Err:<br>" . $add_hotspot,
                            'icon' => 'error',
                        ];
                    }
                } else {
                    $id_ip_pool = d_nzm($this->input->post("id_ip_pool"));
                    $id_profile = d_nzm($this->input->post("id_profile"));
                    $id_user = d_nzm($this->input->post("id_user"));
                    $id_dhcp_server = d_nzm($this->input->post("id_dhcp_server"));
                    $id_firewall_nat = d_nzm($this->input->post("id_firewall_nat"));
                    //EDIT HOTSPOT SERVER
                    $data_router[".id"] = d_nzm($p);
                    //SET FIREWALL NAT HOTSPOT
                    $data_set = [
                        "id" => $id_server,
                        "proses" =>  '/ip/firewall/nat/set',
                        "data" => array(
                            ".id" => $id_firewall_nat,
                            "chain" => "srcnat",
                            "action" => "masquerade",
                            "src-address" => $local_address,
                            "comment" => "masquerade hotspot network, " . $service_name,
                            "disabled" => $disabled,
                        ),
                    ];
                    $set_fw_hotspot = $this->router_process("router", $data_set);
                    if ($set_fw_hotspot == 200) {
                        //SET IP POOL HOTSPOT
                        $ip_pool_exp = explode(";", $ip_pool_address);
                        $count_ip_pool = substr_count($ip_pool_address, ";");
                        if ($count_ip_pool > 2) {
                            $ip_pool = $ip_pool_exp[0] . "-" . $ip_pool_exp[1] . "," . $ip_pool_exp[2] . "-" . $ip_pool_exp[3];
                        } else {
                            $ip_pool = $ip_pool_exp[0] . "-" . $ip_pool_exp[1];
                        }
                        $data_set = [
                            "id"  => d_nzm($this->input->post("id_server")),
                            "proses" =>  '/ip/pool/set',
                            "data" => array(
                                ".id" => $id_ip_pool,
                                "name" => $service_name,
                                "ranges" => $ip_pool,
                            )
                        ];
                        $set_ip_pool = $this->router_process("router", $data_set);
                        if ($set_ip_pool == 200) {
                            //SET DHCP SERVER
                            $data_set = [
                                "id" => $id_server,
                                "proses" =>  '/ip/dhcp-server/set',
                                "data" => array(
                                    ".id" => $id_dhcp_server,
                                    "name" => $service_name,
                                    "interface" => $interface,
                                    "address-pool" => $service_name,
                                    "lease-time" => "01:00:00",
                                    "bootp-support" => "static",
                                    "authoritative" => "after-2sec-delay",
                                    "disabled" => $disabled,
                                ),
                            ];
                            $set_dhcp_server = $this->router_process("router", $data_set);
                            if ($set_dhcp_server == 200) {
                                //SET PROFILE SERVER
                                $data_set = [
                                    "id" => $id_server,
                                    "proses" =>  '/ip/hotspot/profile/set',
                                    "data" => array(
                                        ".id" => $id_profile,
                                        "name" => $service_name,
                                        "hotspot-address" => $hotspot_address,
                                        "dns-name" => $dns_name,
                                        "login-by" => $login_by,
                                        "http-cookie-lifetime" => $time_cookie,
                                        "trial-uptime" => $time_trial . "/1d 00:00:00",
                                    ),
                                ];
                                $set_hotspot_profile_server = $this->router_process("router", $data_set);
                                if ($set_hotspot_profile_server == 200) {
                                    //SET USER HOTSPOT ADMIN
                                    $data_set = [
                                        "id" => $id_server,
                                        "proses" =>  '/ip/hotspot/user/set',
                                        "data" => array(
                                            ".id" => $id_user,
                                            "name" => $username,
                                            "password" => $password,
                                            "profile" => "default",
                                            "disabled" => $disabled,
                                            "comment" => $service_name,
                                        ),
                                    ];
                                    $set_hotspot_user = $this->router_process("router", $data_set);
                                    if ($set_hotspot_user == 200) {
                                        //SET HOTSPOT SERVER
                                        $data_set = [
                                            "id" => $id_server,
                                            "proses" =>  '/ip/hotspot/set',
                                            "data" => array(
                                                ".id" => d_nzm($p),
                                                "name" => $service_name,
                                                "interface" => $interface,
                                                "address-pool" => $service_name,
                                                "profile" => $service_name,
                                                "disabled" => $disabled,
                                            ),
                                        ];
                                        $set_hotspot = $this->router_process("router", $data_set);
                                    } else {
                                        $set_hotspot = "Gagal merubah hotspot server";
                                    }
                                } else {
                                    $set_hotspot = "Gagal merubah hotspot profile server, Mungkin sudah terdapat profile dengan nomor IP Address yang sama, mohon untuk bisa di hapus terlebih dahulu di dalam winbox.";
                                }
                            } else {
                                $set_hotspot = "Gagal merubah DHCP Server";
                            }
                        } else {
                            $set_hotspot = "Gagal merubah IP Pool Server";
                        }
                    } else {
                        $set_hotspot = "Gagal merubah Firewall NAT";
                    }

                    if ($set_hotspot == 200) {
                        $fb = [
                            'title' => 'Sukses',
                            'pesan' => "Hotspot Server Berhasil Dirubah",
                            'icon' => 'success',
                        ];
                    } else {
                        $fb = [
                            'title' => 'Error',
                            'pesan' => "Hotspot Server Gagal Dirubah.<br><br>Err:<br>" . $set_hotspot,
                            'icon' => 'error',
                        ];
                    }
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

    public function delete_hotspot_servers($p)
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

    public function list_ip_pool($p)
    {
        if (!empty($p)) {
            $data_router = [
                "id" => d_nzm($p),
            ];
            $list = $this->router_process("ip_pool/list", $data_router);
            if (!empty($list)) {
                $option_load = '<option value="">- Pilih Address -</option>';
                $td_load = "";
                $no = 1;
                foreach ($list as $key => $value) {
                    //LOAD TABLE ROW
                    $id = $value[".id"];
                    $ex_name = explode("-", $value["name"]);
                    if (end($ex_name) == "Hotspot") {
                        $name = str_replace("-Hotspot", "", $value["name"]);
                        $td_load .= '
                        <tr class="text-center">
                            <td class="align-middle">' . $no . '</td>
                            <td class="align-middle">' . $value["name"] . '</td>
                            <td class="align-middle">' . str_replace(",", "<br>", $value["ranges"]) . '</td>
                            <td class="align-middle">
                                <a href="javascript:void(0)" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-ellipsis text-dark"></i>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item  btn-edit-ip-pool" id=" btn-edit-ip-pool-' . e_nzm($id) . '" href="javascript:void(0)" data-id="' . e_nzm($id) . '" data-name="' . $name . '" data-addresses="' . $value["ranges"] . '">Edit</a>
                                    
                                    <a class="dropdown-item btn-delete-ip-pool" data-id="' . e_nzm($id) . '" href="javascript:void(0)">Hapus</a>
                                </div>
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
                $option_load = "<option value=''>IP Pool Hotspot Kosong</option>";
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
                    "data" => array(
                        "name" => str_replace(" ", "-", $this->input->post("name")) . "-Hotspot",
                        "ranges" => $this->input->post("addresses"),
                    )
                ];
                if ($p == "add") {
                    //ADD IP POOL
                    $data_router["proses"] = "/ip/pool/add";
                    $action = $this->router_process('router', $data_router);
                    $pesan_action = 'Ditambah';
                } else {
                    //EDIT IP POOL
                    $data_router["data"][".id"] = d_nzm($p);
                    $data_router["proses"] = "/ip/pool/set";
                    $action = $this->router_process('router', $data_router);
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

    public function profile_hotspot($method, $p)
    {
        if ($method == "list") {
            if (!empty($p)) {
                $data_router_list = [
                    "id" => d_nzm($p),
                    "proses" =>  '/ip/hotspot/profile/getall',
                    "data" => array(),
                ];
                $list = $this->router_process("router", $data_router_list);
                if (!empty($list)) {
                    $td_load = '';
                    $no = 1;
                    foreach ($list as $key => $value) {
                        //LOAD TABLE ROW
                        $id = $value[".id"];
                        $name = $value["name"];
                        $hotspot_address = $value["hotspot-address"];
                        $dns_name = $value["dns-name"];
                        $name_exp = explode("-", $name);
                        if (end($name_exp) == "Hotspot") {
                            if (!empty($value["http-cookie-lifetime"])) {
                                $status_cookie = "Yes";
                                $cookie_icon = '<i class="fas fa-check-circle text-success"></i>';
                            } else {
                                $status_cookie = "No";
                                $cookie_icon = '<i class="fas fa-times-circle text-danger"></i>';
                            }
                            $td_load .= '
                            <tr class="text-center">
                                <td class="align-middle p-1">' . $no . '</td>
                                <td class="align-middle p-1">' . $name . '</td>
                                <td class="align-middle p-1">' . $hotspot_address . '</td>
                                <td class="align-middle p-1">' . $dns_name . '</td>
                                <td class="align-middle p-1">' . $cookie_icon . '</td>
                                <td class="align-middle p-1">
                                    <a href="javascript:void(0)" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis text-dark"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item btn-edit-profile-hotspot" id=" btn-edit-profile-hotspot-' . e_nzm($id) . '" href="javascript:void(0)" data-id="' . e_nzm($id) . '" data-name="' . str_replace("-Hotspot", "", $name) . '" data-hotspot-address="' . $hotspot_address . '" data-dns-name="' . $dns_name . '" data-status-cookie="' . $status_cookie . '">Edit</a>
                                        
                                        <a class="dropdown-item btn-delete-profile-hotspot" data-id="' . e_nzm($id) . '" href="javascript:void(0)">Hapus</a>
                                    </div>
                                </td>
                            </tr>';
                            $no++;
                        }
                    }
                } else {
                    $td_load = '
                    <tr>
                        <td colspan="6" align="center"><i class="text-danger">Data Profile PPPoE Kosong</i></td>
                    </tr>';
                }
            } else {
                $td_load = '
                <tr>
                    <td colspan="6" align="center"><i class="text-danger">Silahkan pilih server di atas</i></td>
                </tr>';
            }

            echo $td_load;
        } else if ($method == "save") {
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
                        "name" => str_replace(" ", "-", $this->input->post("name")),
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
                    'pesan' => 'Parameter yang anda kirim kosong',
                    'icon' => 'error',
                ];
            }
            echo json_encode($fb);
        } else if ($method == "remove") {
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
        } else {
            $fb = [
                'title' => 'Error',
                'pesan' => 'Error parameter tidak diizinkan',
                'icon' => 'error',
            ];
            echo json_encode($fb);
        }
        die();
    }

    public function simpan_order()
    {
        $this->form_validation->set_rules("id_server", "Server", "required|trim|xss_clean");
        $this->form_validation->set_rules("paket", "Paket", "required|trim|xss_clean");
        $this->form_validation->set_rules("server_hotspot", "Server Hotspot", "required|trim|xss_clean");
        if ($this->form_validation->run() === TRUE) {
            $id_server = $this->input->post("id_server");
            $paket = $this->input->post("paket");
            $server_hotspot = $this->input->post("server_hotspot");
            $username = explode("@", $this->email);
            $username = $username[0];
            $password = $username;
            $id_user = $this->id_user;
            $data_router = [
                "id" => d_nzm($id_server),
                "proses" => '/ip/hotspot/user/profile/getall',
                "data" => array()
            ];

            $get_paket = $this->router_process("router", $data_router);
            if (!empty($get_paket)) {
                $key_paket = array_keys(array_column($get_paket, "name"), $paket);
                if (!empty($key_paket)) {
                    $harga = $get_paket[$key_paket[0]]["incoming-packet-mark"];
                    //CHECK SALDO USER
                    $saldo = $this->model->gd("user", "saldo", "id = '$id_user'", "row");
                    if (!empty($saldo->saldo)) {
                        $saldo_current = $saldo->saldo;
                        if ($saldo_current >= $harga) {
                            $new_saldo = $saldo_current - $harga;
                            //CHECK USER HOTSPOT
                            $router_user = [
                                "id" => d_nzm($id_server),
                                "proses" => '/ip/hotspot/user/getall',
                                "data" => array()
                            ];
                            $data_user = $this->router_process("router", $router_user);
                            $key_user = "";
                            foreach ($data_user as $key => $value) {
                                if(!empty($value["comment"])){
                                    $comment_decode = json_decode($value["comment"],true);
                                    if($comment_decode["id_user"] == $id_user){
                                        $key_user = $key;
                                    }
                                }
                            }
                            $comment = [
                                "id_user" => $id_user,
                                "email" => $this->email,
                            ];
                            if(empty($key_user)){
                                //USER BELUM TERDAFTAR DI HOTSPOT
                                $router_action_user = [
                                    "id" => d_nzm($id_server),
                                    "proses" => '/ip/hotspot/user/add',
                                    "data" => array(
                                        "server" => $server_hotspot,
                                        "name" => $username,
                                        "password" => $password,
                                        "profile" => $paket,
                                        "comment" => json_encode($comment),
                                        "disabled" => "false",
                                    )
                                ];
                                $username = $username;
                                $password = $password;
                            }else{
                                //USER SUDAH TERDAFTAR DI HOTSPOT
                                $router_action_user = [
                                    "id" => d_nzm($id_server),
                                    "proses" => '/ip/hotspot/user/set',
                                    "data" => array(
                                        ".id" => $data_user[$key_user][".id"],
                                        "server" => $server_hotspot,
                                        "profile" => $paket,
                                        "disabled" => "false",
                                    )
                                ];
                                $username = $data_user[$key_user]["name"];
                                $password = $data_user[$key_user]["password"];
                            }
                            $action_user = $this->router_process("router", $router_action_user);
                            if($action_user == 200){
                                //CHECK DNS NAME
                                $router_dns_name = [
                                    "id" => d_nzm($id_server),
                                    "proses" => '/ip/hotspot/profile/getall',
                                    "data" => array()
                                ];
                                $dns_name = $this->router_process("router", $router_dns_name);
                                $key_dns = array_keys(array_column($dns_name,"name"),$server_hotspot);
                                if(!empty($key_dns)){
                                    $link_hotspot = $dns_name[$key_dns[0]]["dns-name"];
                                }else{
                                    $link_hotspot = "";
                                }
                                $param_saldo = [
                                    "saldo" => $new_saldo,
                                ];
                                $update_saldo = $this->model->update("user","id = '$id_user'",$param_saldo);
                                
                                $data_log = [
                                    "id_user" => $id_user,
                                    "id_mitra" => $this->id_mitra,
                                    "tanggal" => date("Y-m-d H:i:s"),
                                    "logs" => "Order Hotspot ".$username." paket ".$paket,
                                    "category" => "Create",
                                ];
                                $logs = $this->model->insert("log_activity_user",$data_log);

                                $fb = [
                                    "title" => "Sukses",
                                    "pesan" => 'Hotspot berhasil di order, Silahkan login menggunakan profile di bawah ini<br><br><b>Username : </b>'.$username.'<br><b>Password : </b>'.$password.'<br><br>Terimakasih.',
                                    "icon" => 'success',
                                    "link_hotspot" => $link_hotspot,
                                ];
                            }else{
                                $fb = [
                                    "title" =>  "Gagal",
                                    "pesan" => "Hotspot gagal di order",
                                    "icon" => "error",
                                ];
                            }
                        } else {
                            $fb = [
                                "title" =>  "Gagal",
                                "pesan" => "Saldo anda kurang",
                                "icon" => "error",
                            ];
                        }
                    } else {
                        $fb = [
                            "title" =>  "Gagal",
                            "pesan" => "User tidak valid",
                            "icon" => "error",
                        ];
                    }
                } else {
                    $fb = [
                        "title" =>  "Gagal",
                        "pesan" => "Paket tidak valid",
                        "icon" => "error",
                    ];
                }
            } else {
                $fb = [
                    "title" =>  "Gagal",
                    "pesan" => "Data paket kosong, mohon hubungi admin kami.",
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

    public function list_order() //SUDAH OK
    {
        $this->form_validation->set_rules("id_server", "Server", "required|trim|xss_clean");
        if ($this->form_validation->run() === TRUE) {
            $id_server = $this->input->post("id_server");
            $data_router = [
                "id" => d_nzm($id_server),
                "proses" => "/ip/hotspot/user/getall",
                "data" => array(),
            ];
            $td_load = '';
            $no = 1;
            $username_list = $this->router_process("router", $data_router);
            if (!empty($username_list)) {
                if (array_keys(array_column($username_list, 'comment'))) {
                    foreach ($username_list as $key => $value) {
                        $id_hotspot = e_nzm($value[".id"]);
                        if (!empty($value["comment"])) {
                            $comment = json_decode($value["comment"], true);
                            if ($value["disabled"] == "false") {
                                $status = '<i class="fas fa-check-circle text-success"></i>';
                            } else {
                                $status = '<i class="fas fa-times-circle text-danger"></i>';
                            }

                            if($this->role_id == "1"){
                                if($value["disabled"] == "false"){
                                    $btn_dis = '<a href="javascript:void(0)" class="dropdown-item btn-dis" id="btn-dis-'.$id_hotspot.'" data-i="' . $id_hotspot . '" data-dis="0">Disable</a>';
                                }else{
                                    $btn_dis = '<a href="javascript:void(0)" class="dropdown-item btn-dis" id="btn-dis-'.$id_hotspot.'" data-i="' . $id_hotspot . '" data-dis="1">Enable</a>';
                                }
                            }else{
                                $btn_dis = '<a href="'.base_url("order_hotspot").'" class="dropdown-item" data-i="' . $id_hotspot . '">Enable</a>';
                            }

                            if(empty($value["password"])){
                                $password = "";
                            }else{
                                $password = $value["password"];
                            }

                            if(empty($value["name"])){
                                $name = "";
                            }else{
                                $name = $value["name"];
                            }

                            //GET USERNAME
                            $data_load = '
                            <tr id="row-' . $id_hotspot . '">
                                <td class="p-1 text-center align-middle">' . $no . '</td>
                                <td class="p-1 text-center align-middle">' . $comment["email"] . '</td>
                                <td class="p-1 text-center align-middle" id="name-' . $id_hotspot . '">' . $name . '</td>
                                <td class="p-1 text-center align-middle" id="password-' . $id_hotspot . '">' . $password . '</td>
                                <td class="p-1 text-center align-middle" id="status-' . $id_hotspot . '">' . $status . '</td>
                                <td class="p-1 text-center align-middle">
                                <div class="dropdown">
                                    <a href="javascript:void(0)" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis text-dark"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item btn-edit" id="btn-edit-' . $id_hotspot . '" href="javascript:void(0)" data-i="' . $id_hotspot . '" data-username="' . $name . '" data-password="' . $password . '" data-dis="'.$value["disabled"].'">Edit</a>
                                        
                                        <a class="dropdown-item btn-delete" data-i="' . $id_hotspot . '" data-username="' . $name . '" href="javascript:void(0)">Hapus</a>
                                        '.$btn_dis.'
                                    </div>
                                </div>
                                </td>
                            </tr>';
                            $no++;
                            if ($this->role_id == "1") {
                                $td_load .= $data_load;
                            } else {
                                if ($comment["id_user"] == $this->id_user) {
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
        } else {
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

    public function simpan_edit() //SUDAH OK
    {
        $this->form_validation->set_rules("id_server", "Server", "required|trim|xss_clean");
        $this->form_validation->set_rules("id_hotspot", "ID Hotspot", "required|trim|xss_clean");
        $this->form_validation->set_rules("username", "Username", "required|trim|xss_clean");
        $this->form_validation->set_rules("password", "Password", "required|trim|xss_clean");
        if ($this->form_validation->run() === TRUE) {
            $id_hotspot = d_nzm($this->input->post('id_hotspot'));
            $id_server = d_nzm($this->input->post('id_server'));
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $data_router = [
                "id" => $id_server,
                "proses" => "/ip/hotspot/user/set",
                "data" => array(
                    ".id" => $id_hotspot,
                    "password" => $password,
                )
            ];

            $action = $this->router_process("router", $data_router);
            if ($action == 200) {
                $data_log = [
                    "id_user" => $this->id_user,
                    "id_mitra" => $this->id_mitra,
                    "tanggal" => date("Y-m-d H:i:s"),
                    "logs" => "Edit Password Hotspot " . $password."  at Username : ".$username,
                    "category" => "Update",
                ];
                $logs = $this->model->insert("log_activity_user", $data_log);
                $fb = [
                    "title" => "Sukses",
                    "pesan" => "Passwor Hotspot Berhasil Dirubah",
                    "icon" => "success",
                ];
            } else {
                $fb = [
                    "title" => "Error",
                    "pesan" => "Password Hotspot Gagal Dirubah<br>" . $action["!trap"][0]["message"],
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

    public function hapus_order()
    {
        $this->form_validation->set_rules("id_server", "ID Server", "required|trim|xss_clean");
        $this->form_validation->set_rules("id_hotspot", "ID Hotspot", "required|trim|xss_clean");
        $this->form_validation->set_rules("username", "Username", "required|trim|xss_clean");
        if ($this->form_validation->run() === TRUE) {
            $id_server = d_nzm($this->input->post("id_server"));
            $id_hotspot = d_nzm($this->input->post("id_hotspot"));
            $username = $this->input->post("username");
            $data_action = [
                "id" => $id_server,
                "proses" => "/ip/hotspot/user/remove",
                "data" => array(
                    ".id" => $id_hotspot,
                )
            ];
            $action = $this->router_process("router", $data_action);
            if ($action == 200) {
                $data_log = [
                    "id_user" => $this->id_user,
                    "id_mitra" => $this->id_mitra,
                    "tanggal" => date("Y-m-d H:i:s"),
                    "logs" => "Hapus Hotspot User " . $username,
                    "category" => "Delete",
                ];
                $logs = $this->model->insert("log_activity_user", $data_log);
                $fb = [
                    "title" => "Sukses",
                    "pesan" => "Hotspot Berhasil Di Hapus",
                    "icon" => "success",
                ];
            } else {
                $fb = [
                    "title" => "Error",
                    "pesan" => $action["!trap"][0]["message"],
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

    public function update_status() //SUDAH OK
    {
        $this->form_validation->set_rules("id_server", "ID Server", "required|trim|xss_clean");
        $this->form_validation->set_rules("id_hotspot", "ID Hotspot", "required|trim|xss_clean");
        $this->form_validation->set_rules("status", "Status", "required|trim|xss_clean|integer");
        if ($this->form_validation->run() === TRUE) {
            $id_server = d_nzm($this->input->post("id_server"));
            $id_hotspot = d_nzm($this->input->post("id_hotspot"));
            $status = $this->input->post("status");
            if ($status == "0") {
                $status = "yes";
                $pesan = "Non Aktifkan";
                $p = "Disabled";
                $icon_disable = '<i class="fas fa-times-circle text-danger"></i>';
            } else {
                $status = "no";
                $pesan = "Aktifkan";
                $p = "Enabled";
                $icon_disable = '<i class="fas fa-check-circle text-success"></i>';
            }

            $data_router = [
                "id" => $id_server,
                "proses" => "/ip/hotspot/user/set",
                "data" => array(
                    ".id" => $id_hotspot,
                    "disabled" => $status,
                )
            ];

            //GET DATA ALL HOTSPOT CLIENT
            $action = $this->router_process("router", $data_router);
            $name = explode("@",$this->email);
            if ($action == 200) {
                $data_log = [
                    "id_user" => $this->id_user,
                    "id_mitra" => $this->id_mitra,
                    "tanggal" => date("Y-m-d H:i:s"),
                    "logs" => $p . " Hotspot " . $name[0],
                    "category" => "Update",
                ];
                $logs = $this->model->insert("log_activity_user", $data_log);
                $fb = [
                    "title" => "Sukses",
                    "pesan" => "Hotspot berhasil di " . $pesan,
                    "icon" => "success",
                    "icon_disable" => $icon_disable,
                ];
            } else {
                $fb = [
                    "title" => "Error",
                    "pesan" => "Hotspot gagal update status<br>Err :<br>" . $action["!trap"][0]["message"],
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

    public function rubah_paket()
    {
        $this->form_validation->set_rules("id_server", "ID Server", "required|trim|xss_clean");
        $this->form_validation->set_rules("id_pppoe", "ID PPPoE", "required|trim|xss_clean");
        $this->form_validation->set_rules("paket", "Paket", "required|trim|xss_clean");
        $this->form_validation->set_rules("berlangganan", "Berlangganan", "required|trim|xss_clean|integer");
        $this->form_validation->set_rules("status_debit", "Status Debit", "required|trim|xss_clean|integer");
        if ($this->form_validation->run() === TRUE) {
            $id_server = d_nzm($this->input->post("id_server"));
            $id_pppoe = d_nzm($this->input->post("id_pppoe"));
            $paket = $this->input->post("paket");
            $berlangganan = $this->input->post("berlangganan");
            $status_debit = $this->input->post("status_debit");

            //SETUP DATA RETURN
            if ($status_debit == "1") {
                $icon_auto_debit = '<i class="fas fa-check-circle text-success"></i>';
            } else {
                $icon_auto_debit = '<i class="fas fa-times-circle text-danger"></i>';
            }
            $data_return = [
                "paket" => $paket,
                "auto_debit" => $icon_auto_debit,
                "date_order" => date("d-M-Y"),
                "expired_date" => date("d-M-Y", strtotime("+" . $berlangganan . " month")),
                "berlangganan" => $berlangganan . " Bulan",
                "status_disabled" => '<i class="fas fa-check-circle text-success"></i>',
            ];

            //CHECK SALDO USER
            $saldo = $this->model->gd("user", "saldo", "id = '" . $this->id_user . "'", "row");
            if (!empty($saldo)) {
                $saldo = $saldo->saldo;
            } else {
                $saldo = 0;
            }

            //HARGA PAKET
            $data_router = [
                "id" => $id_server,
            ];
            $profile_paket = $this->router_process("profile_pppoe/list", $data_router);
            $key_paket = array_keys(array_column($profile_paket, 'name'), $paket);
            $comment = explode(";", $profile_paket[$key_paket[0]]["comment"]);
            $harga_paket = $comment[1];
            $total_bayar = $harga_paket * $berlangganan;

            //CHECK SALDO VS TOTAL BAYAR
            if ($saldo >= $total_bayar) {
                //UPDATE PPPOE
                $new_comment = [
                    "service" => "PPPoE",
                    "date_order" => date("d-m-Y"),
                    "berlangganan" => $berlangganan,
                    "expired_date" => date("d-m-Y", strtotime("+" . $berlangganan . " month")),
                    "auto_debit" => $status_debit,
                    "id_user" => $this->id_user,
                    "email" => $this->email,
                ];

                //CHECK USERNAME
                $get_pppoe_client = $this->router_process("pppoe_client/list", $data_router);
                $key_pppoe_client = array_keys(array_column($get_pppoe_client, '.id'), $id_pppoe);
                $username = $get_pppoe_client[$key_pppoe_client[0]]["name"];

                //CHECK DATA SCHEDULE
                $get_schedule = $this->router_process("sche/list", $data_router);
                $key_sche = array_keys(array_column($get_schedule, 'name'), "sche_pppoe_" . $username);

                //SETUP SCHEDULE
                $data_sche = [
                    "id" => $id_server,
                    "data" => array(
                        ".id" => $get_schedule[$key_sche[0]][".id"],
                        "start-date" => date("M/d/Y", strtotime("+" . $berlangganan . " month")),
                    )
                ];

                $action = $this->router_process("sche/update", $data_sche);
                if ($action == 200) {
                    $data_pppoe = [
                        "id" => $id_server,
                        "data" => array(
                            ".id" => $id_pppoe,
                            "disabled" => "no",
                            "profile" => $paket,
                            "comment" => json_encode($new_comment)
                        )
                    ];

                    $action = $this->router_process("pppoe_client/update", $data_pppoe);
                    if ($action == 200) {
                        $new_saldo = [
                            "saldo" => $saldo - $total_bayar,
                        ];
                        $update_saldo = $this->model->update("user", "id = '" . $this->id_user . "'", $new_saldo);
                        $data_log = [
                            "id_user" => $this->id_user,
                            "id_mitra" => $this->id_mitra,
                            "tanggal" => date("Y-m-d H:i:s"),
                            "logs" => "Re-Activated PPPoE " . $username,
                            "category" => "Update",
                        ];
                        $logs = $this->model->insert("log_activity_user", $data_log);
                        $fb = [
                            "title" => "Sukses",
                            "pesan" => "PPPoE Berhasil Di Aktifkan",
                            "icon" => "success",
                            "data" => $data_return,
                        ];
                    } else {
                        $fb = [
                            "title" => "Gagal",
                            "pesan" => "PPPoE Gagal Di Aktifkan<br>" . $action["!trap"][0]["message"],
                            "icon" => "error",
                        ];
                    }
                } else {
                    $fb = [
                        "title" => "Gagal",
                        "pesan" => "PPPoE Gagal Di Aktifkan<br>" . $action["!trap"][0]["message"],
                        "icon" => "error",
                    ];
                }
            } else {
                $fb = [
                    "title" => "Gagal",
                    "pesan" => "Saldo Anda Kurang",
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

    public function check_status()
    {
        //GET DATA SERVER ALL
        $server = $this->model->gd("api_routeros", "id", "id_mitra = '".$this->id_mitra."' AND is_active = '1'", "result");
        if (!empty($server)) {
            foreach ($server as $server) {
                $id_server = $server->id;
                $data_router = [
                    "id" => $id_server,
                ];
                $pppoe_list = $this->router_process("pppoe_client/list", $data_router);
                $profile_pppoe = $this->router_process("profile_pppoe/list", $data_router);
                $data_disable = array_keys(array_column($pppoe_list, 'disabled'), 'true');
                if (!empty($data_disable)) {
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

                        if ($service == "pppoe") {
                            if ($auto_debit == "1") {
                                //JIKA HARI INI LEBIH BESAR DARI EXPIRED DATE
                                if (strtotime(date("Y-m-d")) > strtotime($expired_date)) {
                                    //CHECK SALDO
                                    $saldo = $this->model->gd("user", "saldo", "id = '$id_user'", "row");
                                    if (!empty($saldo->saldo)) {
                                        $saldo = $saldo->saldo;

                                        //CHECK BIAYA LANGGANAN (HARGA PPPOE * BERLANGGANAN)
                                        $data_paket = array_keys(array_column($profile_pppoe, 'name'), $paket);
                                        if (!empty($data_paket)) {
                                            $harga = explode(";", $profile_pppoe[$data_paket[0]]["comment"]);
                                            $harga = $harga[1];

                                            $biaya_langganan = $harga * $berlangganan;

                                            if ($saldo >= $biaya_langganan) {
                                                //CHECK DATA SCHEDULE
                                                $get_schedule = $this->router_process("sche/list", $data_router);
                                                $key_sche = array_keys(array_column($get_schedule, 'name'), "sche_pppoe_" . $username);

                                                //SETUP SCHEDULE
                                                $data_sche = [
                                                    "id" => $id_server,
                                                    "data" => array(
                                                        ".id" => $get_schedule[$key_sche[0]][".id"],
                                                        "start-date" => date("M/d/Y", strtotime("+" . $berlangganan . " month")),
                                                    )
                                                ];

                                                $action = $this->router_process("sche/update", $data_sche);
                                                if ($action == 200) {
                                                    $new_comment = [
                                                        "service" => "PPPoE",
                                                        "date_order" => date("d-m-Y"),
                                                        "berlangganan" => $berlangganan,
                                                        "expired_date" => date("d-m-Y", strtotime("+" . $berlangganan . " month")),
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

                                                    $action = $this->router_process("pppoe_client/update", $data_pppoe);
                                                    if ($action == 200) {
                                                        $new_saldo = [
                                                            "saldo" => $saldo - $biaya_langganan,
                                                        ];
                                                        $update_saldo = $this->model->update("user", "id = '$id_user'", $new_saldo);
                                                        $data_log = [
                                                            "id_user" => $id_user,
                                                            "id_mitra" => $this->id_mitra,
                                                            "tanggal" => date("Y-m-d H:i:s"),
                                                            "logs" => "Perpanjangan PPPoE " . $username,
                                                            "category" => "Update",
                                                        ];
                                                        $logs = $this->model->insert("log_activity_user", $data_log);
                                                        $to = $email;
                                                        $subject = "Sukses Perpanjangan PPPoE";
                                                        $message = "Proses perpanjangan PPPoE anda atas,<br>
                                                        Username : " . $username . "<br>
                                                        Sisa Saldo : " . $new_saldo["saldo"] . "<br><br>
                                                        Telah berhasil di lakukan dengan detail di bawah ini :<br>
                                                        Date Order : " . $new_comment["date_order"] . "<br>
                                                        Expired Date : " . $new_comment["expired_date"] . "<br>
                                                        Berlangganan : " . $new_comment["berlangganan"] . " Bulan<br>
                                                        Biaya Langganan : " . number_format($biaya_langganan, 0, "", ".") . "<br>
                                                        <br>Terimakasih";
                                                        $fb = [
                                                            "res" => 200,
                                                            "message" => "Sukses",
                                                        ];
                                                    } else {
                                                        $to = $email;
                                                        $subject = "Error Perpanjangan PPPoE";
                                                        $message = "Mohon maaf untuk PPPoE atas,<br>
                                                        Username : " . $username . "<br>
                                                        Mengalami error saat ingin melakukan aktivasi otomatis.<br>
                                                        <br>Err :<br>
                                                        " . $action["!trap"][0]["message"] . "<br>
                                                        Anda bisa melakukan aktivasi secara menual melalui website <a href='" . base_url() . "'>disini</a><br>
                                                        <br>Terimakasih";
                                                        $fb = [
                                                            "res" => 500,
                                                            "message" => "Error : " . $action["!trap"][0]["message"],
                                                        ];
                                                    }
                                                } else {
                                                    $to = $email;
                                                    $subject = "Error Perpanjangan PPPoE";
                                                    $message = "Mohon maaf untuk PPPoE atas,<br>
                                                    Username : " . $username . "<br>
                                                    Mengalami error saat ingin melakukan aktivasi otomatis.<br>
                                                    <br>Err :<br>
                                                    " . $action["!trap"][0]["message"] . "<br>
                                                    Anda bisa melakukan aktivasi secara menual melalui website <a href='" . base_url() . "'>disini</a><br>
                                                    <br>Terimakasih";
                                                    $fb = [
                                                        "res" => 500,
                                                        "message" => "Error : " . $action["!trap"][0]["message"],
                                                    ];
                                                }
                                            } else {
                                                $to = $email;
                                                $subject = "Perpanjangan PPPoE";
                                                $message = "Mohon maaf untuk PPPoE atas,<br>
                                                Username : " . $username . "<br>
                                                Telah di Non Aktifkan karena saldo anda kurang untuk melakukan aktivasi PPPoE anda.<br>
                                                Anda bisa melakukan aktivasi secara menual melalui website <a href='" . base_url() . "'>disini</a><br>
                                                <br>Terimakasih";
                                                $fb = [
                                                    "res" => 500,
                                                    "message" => "Saldo Kurang",
                                                ];
                                            }
                                            $send_email = $this->email_send($to, $subject, $message);
                                        } else {
                                            $fb = [
                                                "res" => 500,
                                                "message" => "Profile PPPoE Tidak Ditemukan",
                                            ];
                                        }
                                    } else {
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

    public function paket_hotspot($method, $p)
    {
        if ($method == "list") {
            if (!empty($p)) {
                $data_router_list = [
                    "id" => d_nzm($p),
                    "proses" =>  '/ip/hotspot/user/profile/getall',
                    "data" => array(),
                ];
                $list = $this->router_process("router", $data_router_list);
                if (!empty($list)) {
                    $td_load = '';
                    $option_load = '';
                    $no = 1;
                    foreach ($list as $key => $value) {
                        //LOAD TABLE ROW
                        $name_exp = explode("-", $value["name"]);
                        if (end($name_exp) == "Hotspot") {
                            $sesi = json_decode($value["outgoing-packet-mark"], true);
                            if (!empty($sesi)) {
                                if (!empty($sesi["d"])) {
                                    $days = $sesi["d"];
                                } else {
                                    $days = 0;
                                }
                                if (!empty($sesi["h"])) {
                                    $hours = $sesi["h"];
                                } else {
                                    $hours = 0;
                                }
                                if (!empty($sesi["m"])) {
                                    $minutes = $sesi["m"];
                                } else {
                                    $minutes = 0;
                                }
                            } else {
                                $days = 0;
                                $hours = 0;
                                $minutes = 0;
                            }

                            $limit = explode("/", $value["rate-limit"]);

                            $option_load .= '<option value="' . $value["name"] . '" data-h="' . $value["incoming-packet-mark"] . '" data-waktu="'.str_replace("d", " Hari ", str_replace("h", " Jam ", str_replace("m", " Menit ", $value["session-timeout"]))).'" data-download="'.$limit[0].'" data-upload="'.$limit[1].'" data-shared="' . $value["shared-users"] . '">' . $value["name"] . '</option>';

                            $id_hotspot = e_nzm($value[".id"]);
                            $td_load .= '
                            <tr align="center" id="row-paket-' . $id_hotspot . '">
                                <td class="align-middle">' . $no . '</td>
                                <td class="align-middle">' . $value["name"] . '</td>
                                <td class="align-middle">' . str_replace("d", " Hari ", str_replace("h", " Jam ", str_replace("m", " Menit ", $value["session-timeout"]))) . '</td>
                                <td class="align-middle">' . $value["shared-users"] . '</td>
                                <td class="align-middle">' . strtoupper($limit[0]) . '</td>
                                <td class="align-middle">' . strtoupper($limit[1]) . '</td>
                                <td class="align-middle">' . number_format($value["incoming-packet-mark"], 0, "", ".") . '</td>
                                <td class="align-middle">
                                    <a href="javascript:void(0)" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis text-dark"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item btn-edit-paket" id="btn-edit-paket-' . $id_hotspot . '" href="javascript:void(0)" data-i="' . $id_hotspot . '" data-days="' . $days . '" data-hours="' . $hours . '" data-minutes="' . $minutes . '" data-download="' . str_replace("M", "", strtoupper($limit[0])) . '" data-upload="' . str_replace("M", "", strtoupper($limit[1])) . '" data-shared="' . $value["shared-users"] . '" data-harga="' . number_format($value["incoming-packet-mark"], 0, "", ".") . '" data-name="' . str_replace("-Hotspot", "", $value["name"]) . '" data-address-pool="' . $value["address-pool"] . '">Edit</a>
                                        
                                        <a class="dropdown-item btn-delete-paket" data-i="' . $id_hotspot . '" href="javascript:void(0)">Hapus</a>
                                    </div>
                                </td>
                            </tr>';
                            $no++;
                        }
                    }
                } else {
                    $option_load = '<option value="">Tidak ada paket hotspot</option>';
                    $td_load = '<tr align="center"><td colspan="8" class="align-middle">Data Paket Hotspot Kosong</td></tr>';
                }
            } else {
                $option_load = '<option value="">Mohon Pilih Server</option>';
                $td_load = '<tr align="center"><td colspan="8" class="align-middle">Silahkan pilih server</td></tr>';
            }

            $fb = [
                "td_load" => $td_load,
                "option_load" => $option_load,
            ];
            echo json_encode($fb);
        } else if ($method == "save") {
            if (!empty($p)) {
                $this->form_validation->set_rules("id_server", "Server", "required|trim|xss_clean");
                $this->form_validation->set_rules("name_paket", "Nama Paket", "required|trim|xss_clean");
                $this->form_validation->set_rules("address_pool_paket", "Address Pool", "required|trim|xss_clean");
                $this->form_validation->set_rules("session_time_days", "Waktu Penggunaan Hari", "required|trim|xss_clean|integer");
                $this->form_validation->set_rules("session_time_hours", "Waktu Penggunaan Jam", "required|trim|xss_clean|integer");
                $this->form_validation->set_rules("session_time_minutes", "Waktu Penggunaan Menit", "required|trim|xss_clean|integer");
                $this->form_validation->set_rules("download_limit", "Download Limit", "required|trim|xss_clean|integer");
                $this->form_validation->set_rules("upload_limit", "Upload Limit", "required|trim|xss_clean|integer");
                $this->form_validation->set_rules("shared_users", "Shared Users", "required|trim|xss_clean|integer");
                $this->form_validation->set_rules("harga", "Harga", "required|trim|xss_clean");
                if ($this->form_validation->run() === TRUE) {
                    $id_server = $this->input->post("id_server");
                    $name_paket = $this->input->post("name_paket");
                    $address_pool_paket = $this->input->post("address_pool_paket");
                    $session_time_days = $this->input->post("session_time_days");
                    $session_time_hours = $this->input->post("session_time_hours");
                    $session_time_minutes = $this->input->post("session_time_minutes");
                    $download_limit = $this->input->post("download_limit") . "M";
                    $upload_limit = $this->input->post("upload_limit") . "M";
                    $shared_users = $this->input->post("shared_users");
                    $harga = str_replace(".", "", $this->input->post("harga"));
                    if (!empty($session_time_days)) {
                        $days = $session_time_days . "d ";
                    } else {
                        $days = "";
                    }
                    $session_time = $days . sprintf("%02d", $session_time_hours) . ":" . sprintf("%02d", $session_time_minutes) . ":00";
                    $json_time = json_encode(array("d" => $session_time_days, "h" => $session_time_hours, "m" => $session_time_minutes));
                    $rate_limit = $download_limit . "/" . $upload_limit;
                    if ($p == "add") {
                        $data_router = [
                            "id" => d_nzm($id_server),
                            "proses" =>  '/ip/hotspot/user/profile/add',
                            "data" => array(
                                "name" => $name_paket . "-Hotspot",
                                "address-pool" => $address_pool_paket,
                                "session-timeout" => $session_time,
                                "shared-users" => $shared_users,
                                "rate-limit" => $rate_limit,
                                "incoming-packet-mark" => $harga,
                                "outgoing-packet-mark" => $json_time,
                                "on-logout" => '/ip hotspot user disable [find name=$user]',
                            ),
                        ];
                        $action = $this->router_process("router", $data_router);
                        if ($action == 200) {
                            $fb = [
                                "title" => "Sukses",
                                "pesan" => "Paket Hotspot Berhasil Dibuat",
                                "icon" => "success",
                            ];
                        } else {
                            $fb = [
                                "title" => "Gagal",
                                "pesan" => "Paket Hotspot Gagal Dibuat",
                                "icon" => "error",
                            ];
                        }
                    } else {
                        $data_router = [
                            "id" => d_nzm($id_server),
                            "proses" =>  '/ip/hotspot/user/profile/set',
                            "data" => array(
                                ".id" => d_nzm($p),
                                "name" => $name_paket . "-Hotspot",
                                "address-pool" => $address_pool_paket,
                                "session-timeout" => $session_time,
                                "shared-users" => $shared_users,
                                "rate-limit" => $rate_limit,
                                "incoming-packet-mark" => $harga,
                                "outgoing-packet-mark" => $json_time,
                                "on-logout" => '/ip hotspot user disable [find name=$user]',
                            ),
                        ];
                        $action = $this->router_process("router", $data_router);
                        if ($action == 200) {
                            $fb = [
                                "title" => "Sukses",
                                "pesan" => "Paket Hotspot Berhasil Dirubah",
                                "icon" => "success",
                            ];
                        } else {
                            $fb = [
                                "title" => "Gagal",
                                "pesan" => "Paket Hotspot Gagal Dirubah",
                                "icon" => "error",
                            ];
                        }
                    }
                } else {
                    $fb = [
                        "title" => "Warning",
                        "pesan" => validation_errors(),
                        "icon" => "warning",
                    ];
                }
            } else {
                $fb = [
                    "title" => "Error",
                    "pesan" => "Parameter yang dikirim kosong",
                    "icon" => "error",
                ];
            }
            echo json_encode($fb);
        } else if ($method == "remove") {
            if (!empty($p)) {
                $this->form_validation->set_rules("id_server", "Server", "required|trim|xss_clean");
                if ($this->form_validation->run() === TRUE) {
                    $id_server = $this->input->post("id_server");
                    $data_router = [
                        "id" => d_nzm($id_server),
                        "proses" =>  '/ip/hotspot/user/profile/remove',
                        "data" => array(
                            ".id" => d_nzm($p),
                        ),
                    ];
                    $action = $this->router_process("router", $data_router);
                    if ($action == 200) {
                        $fb = [
                            "title" => "Sukses",
                            "pesan" => "Paket Berhasil Dihapus",
                            "icon" => "success",
                        ];
                    } else {
                        $fb = [
                            "title" => "Gagal",
                            "pesan" => "Paket Gagal Dihapus",
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
            } else {
                $fb = [
                    "title" => "Error",
                    "pesan" => "Parameter yang dikirim tidak valid",
                    "icon" => "error",
                ];
            }
            echo json_encode($fb);
        } else {
            $fb = [
                "title" => "Error",
                "pesan" => "Parameter yang dikirim tidak valid",
                "icon" => "error",
            ];
            echo json_encode($fb);
        }
        die();
    }
}
