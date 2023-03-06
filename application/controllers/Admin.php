<?php
require 'vendor/autoload.php';
use FreeDSx\Snmp\SnmpClient;
use FreeDSx\Snmp\Exception\SnmpRequestException;
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
class Admin extends MY_Controller
{

    public function index()
    {
        $data['nzm'] = 'Dashboard TEXA';
        $data["js_add"] = "index";
        $data["page"] = "index";
        $week_number = date("W");
        $week_array = $this->getStartAndEndDate($week_number, date("Y"));
        $data["start_date"] = $week_array["week_start"]." 00:00:00";
        $data["end_date"] = $week_array["week_end"]." 00:00:00";
        $this->load->view('templates/index', $data);
    }

    public function list_vpn()
    {
        $data['nzm'] = 'List VPN';
		$data["js_add"] = "list_vpn";
        $data["page"] = "list_vpn";
        $this->load->view('templates/index', $data);
    }

    public function list_payment()
    {
        $data['nzm'] = 'Payment Method';
		$data["js_add"] = "list_payment";
        $data["page"] = "list_payment";
        $this->load->view('templates/index', $data);
    }

    public function history_topup()
    {
        $data['nzm'] = 'History Topup';
		$data["js_add"] = "history_topup";
        $data["page"] = "history_topup";
        $this->load->view('templates/index', $data);
    }

    public function payment_gateway()
    {
        $data['nzm'] = 'Payment Gateway';
		$data["js_add"] = "payment_gateway";
        $data["page"] = "payment_gateway";
        $this->load->view('templates/index', $data);
    }

    public function list_user()
    {
        $data['nzm'] = 'Data Client';
		$data["js_add"] = "list_user";
        $data["page"] = "list_user";
        $this->load->view('templates/index', $data);
    }

    public function user_edit($p)
    {
        if(!empty($p)){
            $data['nzm'] = 'Edit User';
            $data["js_add"] = "edit_user";
            $data["page"] = "edit_user";
            $this->load->view('templates/index', $data);
        }else{
            redirect("user/list");
        }
    }

    public function user_add()
    {
		$data['nzm'] = 'Tambah Client';
		$data["js_add"] = "edit_user";
		$data["page"] = "edit_user";
		$this->load->view('templates/index', $data);
    }

    public function list_order_vpn()
    {
        $data['nzm'] = 'List Order VPN';
		$data["js_add"] = "list_order_vpn";
        $data["page"] = "list_order_vpn";
        $this->load->view('templates/index', $data);
    }

    public function manage_vpn($p)
    {
        $data['nzm'] = 'List Order VPN';
		$data["js_add"] = "manage_vpn";
		$data["id_order"] = d_nzm($p);
        $data["page"] = "manage_vpn";
        $this->load->view('templates/index', $data);
    }

    public function order_vpn()
    {
        $data['nzm'] = 'Order Remote VPN';
		$data["js_add"] = "order_vpn";
        $data["page"] = "order_vpn";
        $this->load->view('templates/index', $data);
    }

    public function form_vpn($p)
    {
        if(!empty($p) || $p == "new"){
            $data['nzm'] = 'Edit VPN Master';
        }else{
            $data['nzm'] = 'Create VPN Master';
        }
		$data["js_add"] = "form_vpn";
        $data["page"] = "form_vpn";
        $this->load->view('templates/index', $data);
    }

    public function form_payment($p)
    {
        if(!empty($p)){
            $data['nzm'] = 'Edit Payment';
        }else{
            $data['nzm'] = 'Create Payment';
        }
		$data["js_add"] = "form_payment";
        $data["page"] = "form_payment";
        $this->load->view('templates/index', $data);
    }

    public function account_setting()
    {
        $data['nzm'] = 'Akun';
        $data["page"] = "account_setting";
        $this->load->view('templates/index', $data);
    }

    public function isi_saldo()
    {
        $data['nzm'] = 'Saldo';
		$data["js_add"] = "isi_saldo";
        $data["page"] = "isi_saldo";
        $this->load->view('templates/index', $data);
    }

    public function list_topup($p)
    {
        if($p == "pending"){
            $data_list = $this->model->gd("top_up","*,(SELECT email FROM user WHERE id = id_user) as email","id_mitra = '".$this->id_mitra."' AND status = 'Pending'","result");
            $status = '<span class="badge badge-warning">Pending</span>';
            $data['nzm'] = 'List Top Up Pending';
        }else if($p == "success"){
            $data_list = $this->model->gd("top_up","*,(SELECT email FROM user WHERE id = id_user) as email","id_mitra = '".$this->id_mitra."' AND status = 'Sukses'","result");
            $status = '<span class="badge badge-success">Success</span>';
            $data['nzm'] = 'List Top Up Sukses';
        }else if($p == "cancel"){
            $data_list = $this->model->gd("top_up","*,(SELECT email FROM user WHERE id = id_user) as email","id_mitra = '".$this->id_mitra."' AND status = 'Cancel'","result");
            $status = '<span class="badge badge-danger">Cancel</span>';
            $data['nzm'] = 'List Top Up Cancel';
        }else{
            $data_list = "Parameter Not Registered";
        }
        if($data_list != "Parameter Not Registered"){
            $data["js_add"] = "list_topup";
            $data["data"] = $data_list;
            $data["status"] = $status;
            $data["page"] = "list_topup";
            $this->load->view('templates/index', $data);
        }else{
            redirect("auth/logout");
        }
    }

    public function routeros($p)
    {
        if($p == "list"){
            $data['nzm'] = 'List Server';
            $data["js_add"] = "list_routeros";
            $data["page"] = "list_routeros";
            $this->load->view('templates/index', $data);
        }else{
            if($p == "0"){
                $data['nzm'] = 'Add Server';
                $data["js_add"] = "routeros_edit";
                $data["page"] = "routeros_edit";
                $this->load->view('templates/index', $data);
            }else{
                $data['nzm'] = 'Edit Server';
                $data["js_add"] = "routeros_edit";
                $data["id"] = d_nzm($p);
                $data["page"] = "routeros_edit";
                $this->load->view('templates/index', $data);
            }
        }
    }

    public function konfirmasi_saldo()
    {
        $data['nzm'] = 'Konfirmasi Top Up';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('admin/konfirmasi_saldo');
        $this->load->view('templates/auth_footer');
    }

    //PPPoE
    public function setting_pppoe()
    {
        $data['nzm'] = 'PPPoE Setting ';
		$data["js_add"] = "setting_pppoe";
        $data["page"] = "setting_pppoe";
        $this->load->view('templates/index', $data);
    }

    public function order_pppoe()
    {
        $data['nzm'] = 'PPPoE Order';
		$data["js_add"] = "order_pppoe";
        $data["page"] = "order_pppoe";
        $this->load->view('templates/index', $data);
    }

    public function list_order_pppoe()
    {
        $data['nzm'] = 'List Order PPPoE';
		$data["js_add"] = "list_order_pppoe";
        $data["page"] = "list_order_pppoe";
        $this->load->view('templates/index', $data);
    }

    //Hotspot
	public function voucher_hotspot()
	{
        $data['nzm'] = 'Voucher Hotspot';
		$data["js_add"] = "voucher_hotspot";
        $data["page"] = "voucher_hotspot";
        $this->load->view('templates/index', $data);
	}

    public function setting_hotspot()
    {
        $data['nzm'] = 'Hotspot Setting';
		$data["js_add"] = "setting_hotspot";
        $data["page"] = "setting_hotspot";
        $this->load->view('templates/index', $data);
    }

    public function order_hotspot()
    {
        $data['nzm'] = 'Hotspot Order';
		$data["js_add"] = "order_hotspot";
        $data["page"] = "order_hotspot";
        $this->load->view('templates/index', $data);
    }

    public function user_order_hotspot()
    {
        $data['nzm'] = 'User Order Hotspot';
		$data["js_add"] = "list_order_hotspot";
        $data["page"] = "list_order_hotspot";
        $this->load->view('templates/index', $data);
    }

    public function user_active_hotspot()
    {
        $data['nzm'] = 'User Active Hotspot';
		$data["js_add"] = "list_active_hotspot";
        $data["page"] = "list_active_hotspot";
        $this->load->view('templates/index', $data);
    }

    public function saran_kritik()
    {
        $data['nzm'] = 'Saran & Kritik';
        $data["page"] = "saran_kritik";
        $this->load->view('templates/index', $data);
    }

	//OLT
    public function setting_olt()
    {
		$snmp = new SnmpClient([
			'host' => '127.0.0.1:8080',
			'version' => 2,
			'community' => 'public',
		]);
		$data["test"] = $snmp->get('1.3.6.1.2.1').PHP_EOL;

        $data['nzm'] = 'OLT Setting';
		$data["js_add"] = "OLT/setting_olt";
        $data["page"] = "OLT/setting_olt";
        $this->load->view('templates/index', $data);
    }
    public function onu_type_olt()
    {
        $data['nzm'] = 'ONU Type';
		$data["js_add"] = "OLT/onu_type_olt";
        $data["page"] = "OLT/onu_type_olt";
        $this->load->view('templates/index', $data);
    }
    public function zone_olt()
    {
        $data['nzm'] = 'Zone OLT';
		$data["js_add"] = "OLT/zone_olt";
        $data["page"] = "OLT/zone_olt";
        $this->load->view('templates/index', $data);
    }
    public function onu_olt()
    {
        $data['nzm'] = 'ONU OLT';
		$data["js_add"] = "OLT/onu_olt";
        $data["page"] = "OLT/onu_olt";
        $this->load->view('templates/index', $data);
    }
}
