<?php
defined('BASEPATH') or exit('No direct script access allowed');
class OLT extends MY_Controller
{
	public function get_list_olt(){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://'.$this->domain.'.smartolt.com/api/system/get_olts',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => 
			array(
				'X-Token: '.$this->token_olt
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$data = json_decode($response,true);
		$no = 1;
		$row = '';
		if($data["status"] === true){
			foreach ($data["response"] as $key => $value) {
				$row .= '
				<tr>
					<td class="text-center">'.$no++.'</td>
					<td class="text-center" id="data-name-'.$value["id"].'">'.$value["name"].'</td>
					<td class="text-center" id="data-olt-hardware-version-'.$value["id"].'">'.$value["olt_hardware_version"].'</td>
					<td class="text-center" id="data-ip-'.$value["id"].'">'.$value["ip"].'</td>
					<td class="text-center" id="data-telnet-port-'.$value["id"].'">'.$value["telnet_port"].'</td>
					<td class="text-center" id="data-snmp-port-'.$value["id"].'">'.$value["snmp_port"].'</td>
					<td class="text-center">
						<button class="btn btn-sm btn-info" data-id="'.$value["id"].'" title="Edit"><i class="fas fa-pencil-alt m-0 p-1"></i></button>
						<button class="btn btn-sm btn-danger" data-id="'.$value["id"].'" title="Delete"><i class="fas fa-trash-alt m-0 p-1"></i></button>
						<button class="btn btn-sm btn-success" data-id="'.$value["id"].'" title="Test Connection"><i class="fas fa-wifi m-0 p-1"></i></button>
					</td>
				</tr>';
			}
			$fb = [
				"status" => true,
				"row" => $row,
			];
		}else{
			$fb = [
				"status" => false,
				"error" => $data["error"],
			];
		}
		echo json_encode($fb);
		die();
	}

	//{PROSES ADD TYPE ONU}//
	public function add_onu_type()
	{
		$this->form_validation->set_rules("name","Name","required|trim|xss_clean");
		$this->form_validation->set_rules("pon_type","PON Type","required|trim|xss_clean");
		$this->form_validation->set_rules("ethernet_ports_nr","Ethernet Ports NR","required|trim|xss_clean|integer|max_length[2]|callback_check_ethernet_ports_nr");
		$this->form_validation->set_rules("wifi_ssids_nr","Wifi SSIDS NR","required|trim|xss_clean|integer|max_length[1]|callback_check_wifi_ssids_nr");
		$this->form_validation->set_rules("voip_ports_nr","Voip Ports NR","required|trim|xss_clean|integer|max_length[1]|callback_check_voip_ports_nr");
		$this->form_validation->set_rules("catv","CATV","required|trim|xss_clean|integer|max_length[1]|callback_check_catv");
		$this->form_validation->set_rules("allow_custom_profiles","Allow Custom Profile","required|trim|xss_clean|integer|max_length[1]|callback_check_allow_custom_profiles");
		$this->form_validation->set_rules("capability","Capability","required|trim|xss_clean|callback_check_capability");
		if($this->form_validation->run === TRUE){
			$data = [
				"name" => $this->input->post("name"),
				"pon_type" => $this->input->post("pon_type"),
				"ethernet_ports_nr" => $this->input->post("ethernet_ports_nr"),
				"wifi_ssids_nr" => $this->input->post("wifi_ssids_nr"),
				"voip_ports_nr" => $this->input->post("voip_ports_nr"),
				"catv" => $this->input->post("catv"),
				"allow_custom_profiles" => $this->input->post("allow_custom_profiles"),
				"capability" => $this->input->post("capability"),
			];
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://'.$this->domain.'.smartolt.com/api/system/add_onu_type',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => array(
					'X-Token: '.$this->token_olt
				),
			));
	
			$response = curl_exec($curl);
	
			curl_close($curl);
			$data = json_decode($response);
			if($data->status == true){
				$fb = [
					"status" => "success",
					"pesan" => $data->response,
				];
			}else{
				$fb = [
					"status" => "error",
					"pesan" => $data->response,
				];
			}
		}else{
			$fb = [
				"status" => "error",
				"pesan" => validation_errors(),
			];
		}
		echo json_encode($fb);
		die();
	}

	public function check_ethernet_ports_nr($str)
	{
		$array_rules = ["1"=>"OK","2"=>"OK","3"=>"OK","4"=>"OK","5"=>"OK","8"=>"OK","16"=>"OK","24"=>"OK"];
		if(!empty($array_rules[$str])){
			return TRUE;
		}else{
			$this->form_validation->set_message('check_ethernet_ports_nr', '{field} hanya boleh diisi dengan angka 0,1,2,3,4,5,8,16,24');
			return FALSE;
		}
	}

	public function check_wifi_ssids_nr($str)
	{
		$array_rules = ["0"=>"OK","1"=>"OK","2"=>"OK","3"=>"OK","4"=>"OK","5"=>"OK","6"=>"OK","7"=>"OK","8"=>"OK"];
		if(!empty($array_rules[$str])){
			return TRUE;
		}else{
			$this->form_validation->set_message('check_wifi_ssids_nr', '{field} hanya boleh diisi dengan angka 0,1,2,3,4,5,6,7,8');
			return FALSE;
		}
	}

	public function check_voip_ports_nr($str)
	{
		$array_rules = ["0"=>"OK","1"=>"OK","2"=>"OK"];
		if(!empty($array_rules[$str])){
			return TRUE;
		}else{
			$this->form_validation->set_message('check_voip_ports_nr', '{field} hanya boleh diisi dengan angka 0,1,2');
			return FALSE;
		}
	}

	public function check_catv($str)
	{
		$array_rules = ["0"=>"OK","1"=>"OK"];
		if(!empty($array_rules[$str])){
			return TRUE;
		}else{
			$this->form_validation->set_message('check_catv', '{field} tidak sesuai dengan rules');
			return FALSE;
		}
	}

	public function check_allow_custom_profiles($str)
	{
		$array_rules = ["0"=>"OK","1"=>"OK"];
		if(!empty($array_rules[$str])){
			return TRUE;
		}else{
			$this->form_validation->set_message('check_allow_custom_profiles', '{field} tidak sesuai dengan rules');
			return FALSE;
		}
	}

	public function check_capability($str)
	{
		$array_rules = ["Bridging/Routing"=>"OK","Bridging"=>"OK"];
		if(!empty($array_rules[$str])){
			return TRUE;
		}else{
			$this->form_validation->set_message('check_capability', '{field} tidak sesuai dengan rules');
			return FALSE;
		}
	}
	//{END PROSES ADD TYPE ONU}//

	public function get_onu_type_list()
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://'.$this->domain.'.smartolt.com/api/system/get_onu_types',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'X-Token: '.$this->token_olt
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($response,true);
		$row = '';
		if($data["status"] === true){
			$no = 1;
			foreach ($data["response"] as $key => $value) {
				if($value["catv"] == "1"){
					$catv = '<i class="fas fa-check-circle text-success" title="YES"></i>';
				}else{
					$catv = '<i class="fas fa-times-circle text-danger" title="NO"></i>';
				}
				if($value["allow_custom_profiles"] == "1"){
					$allow_custom_profiles = '<i class="fas fa-check-circle text-success" title="YES"></i>';
				}else{
					$allow_custom_profiles = '<i class="fas fa-times-circle text-danger" title="NO"></i>';
				}
				$row .= '
				<tr>
					<td class="text-center p-2">'.$no++.'</td>
					<td class="text-center p-2" id="data-name-'.$value["id"].'">'.$value["name"].'</td>
					<td class="text-center p-2" id="data-pon-type-'.$value["id"].'">'.$value["pon_type"].'</td>
					<td class="text-center p-2" id="data-capability-'.$value["id"].'">'.$value["capability"].'</td>
					<td class="text-center p-2" id="data-ethernet-ports-'.$value["id"].'">'.$value["ethernet_ports"].'</td>
					<td class="text-center p-2" id="data-wifi-ports-'.$value["id"].'">'.$value["wifi_ports"].'</td>
					<td class="text-center p-2" id="data-voip-ports-'.$value["id"].'">'.$value["voip_ports"].'</td>
					<td class="text-center p-2" id="data-catv-'.$value["id"].'">'.$catv.'</td>
					<td class="text-center p-2" id="data-allow-custom-profiles-'.$value["id"].'">'.$allow_custom_profiles.'</td>
					<td class="text-center p-2">
						<button class="btn btn-sm btn-info" data-id="'.$value["id"].'" title="Edit"><i class="fas fa-pencil-alt m-0 p-1"></i></button>
						<button class="btn btn-sm btn-danger" data-id="'.$value["id"].'" title="Delete"><i class="fas fa-trash-alt m-0 p-1"></i></button>
					</td>
				</tr>';
			}
			$fb = [
				"status" => true,
				"row" => $row,
			];
		}else{
			$fb = [
				"status" => false,
				"row" => '<tr><td colspan="10" class="text-center">'.$data["response"].'</td></tr>',
			];
		}
		echo json_encode($fb);
		die();
	}

	public function get_onu_list()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://'.$this->domain.'.smartolt.com/api/onu/get_all_onus_details',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'X-Token: '.$this->token_olt
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($response,true);
		$row = '';
		if($data["status"] === true){
			$no = 1;
			foreach ($data["onus"] as $key => $value) {
				if($value["status"] == "Online"){
					$status = '<i class="fa-solid fa-earth-asia text-success"></i> '.$value["status"].'';
				}else{
					$status = '<i class="fa-solid fa-plug-circle-exclamation"></i> '.$value["status"].'';
				}

				if($value["signal"] == "Very good"){
					$signal = '<i class="fa-solid fa-signal text-success" title='.$value["signal"].'"></i>';
				}else if($value["signal"] == "Warning"){
					$signal = '<i class="fa-solid fa-signal text-warning" title='.$value["signal"].'"></i>';
				}else{
					$signal = '<i class="fa-solid fa-signal text-danger" title='.$value["signal"].'"></i>';
				}
				$row .= '
				<tr>
					<td class="text-center p-2">'.$no++.'</td>
					<td class="text-center p-2">'.$value["name"].'</td>
					<td class="text-center p-2">'.$value["sn"].'</td>
					<td class="text-center p-2">'.$value["olt_name"].'</td>
					<td class="text-center p-2">'.$value["board"].'</td>
					<td class="text-center p-2">'.$value["port"].'</td>
					<td class="text-center p-2">'.$value["onu_type_name"].'</td>
					<td class="text-center p-2">'.$value["ip_address"].'</td>
					<td class="text-center p-2">'.$value["username"].'</td>
					<td class="text-center p-2">'.$value["password"].'</td>
					<td class="text-center p-2">'.$value["service_ports"][0]["upload_speed"].'</td>
					<td class="text-center p-2">'.$value["service_ports"][0]["download_speed"].'</td>
					<td class="text-center p-2">'.$value["zone_name"].'</td>
					<td class="text-center p-2">'.$value["address"].'</td>
					<td class="text-center p-2">'.$signal.'</td>
					<td class="text-center p-2">'.$status.'</td>
					<td class="text-center p-2">
						<button class="btn btn-sm btn-info" data-id="'.$value["unique_external_id"].'" title="Edit"><i class="fas fa-pencil-alt m-0 p-1"></i></button>
						<button class="btn btn-sm btn-danger" data-id="'.$value["unique_external_id"].'" title="Delete"><i class="fas fa-trash-alt m-0 p-1"></i></button>
					</td>
				</tr>';
			}
			$fb = [
				"status" => true,
				"onus" => $data["onus"],
				"row" => $row,
			];
		}else{
			$fb = [
				"status" => false,
				"row" => '<tr><td colspan="17" class="text-center">'.$data["error"].'</td></tr>',
			];
		}
		echo json_encode($fb);
		die();
	}
	
	//START ONU//
	public function add_onu()
	{
		$this->form_validation->set_rules("olt_id","OLT","required|trim|xss_clean|integer");
		$this->form_validation->set_rules("pon_type","PON Type","required|trim|xss_clean|callback_check_pon_type");
		$this->form_validation->set_rules("board","Board","trim|xss_clean|integer");
		$this->form_validation->set_rules("port","Port","trim|xss_clean|integer");
		$this->form_validation->set_rules("sn","SN","required|trim|xss_clean");
		$this->form_validation->set_rules("onu_type","ONU Type","required|trim|xss_clean");
		$this->form_validation->set_rules("onu_mode","ONU Mode","required|trim|xss_clean|callback_check_onu_mode");
		$this->form_validation->set_rules("vlan","User VLAN","required|trim|xss_clean|integer");
		$this->form_validation->set_rules("zone","Zone","required|trim|xss_clean");
		$this->form_validation->set_rules("name","Name","required|trim|xss_clean");
		$this->form_validation->set_rules("upload_speed_profile_name","Upload Speed","required|trim|xss_clean");
		$this->form_validation->set_rules("download_speed_profile_name","Upload Speed","required|trim|xss_clean");
		if($this->form_validation->run() === TRUE){
			$olt_id = $this->input->post("olt_id");
			$pon_type = $this->input->post("pon_type");
			$board = $this->input->post("board");
			$port = $this->input->post("port");
			$sn = $this->input->post("sn");
			$onu_type = $this->input->post("onu_type");
			$onu_mode = $this->input->post("onu_mode");
			$vlan = $this->input->post("vlan");
			$zone = $this->input->post("zone");
			$name = $this->input->post("name");
			$address_or_comment = $this->input->post("address_or_comment");
			$upload_speed_profile_name = $this->input->post("upload_speed_profile_name");
			$download_speed_profile_name = $this->input->post("download_speed_profile_name");
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://'.$this->domain.'.smartolt.com/api/onu/authorize_onu',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array(
					'olt_id' => $olt_id,
					'pon_type' => $pon_type,
					'board' => $board,
					'port' => $port,
					'sn' => $sn,
					'vlan' => $vlan,
					'onu_type' => $onu_type,
					'zone' => $zone,
					'name' => $name,
					'address_or_comment' => $address_or_comment,
					'onu_mode' => $onu_mode,
					'onu_external_id' => hash('crc32',date("YmdHis"))
				),
				CURLOPT_HTTPHEADER => array(
					'X-Token: '.$this->token_olt
				),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$fb = json_decode($response,true);
			if($fb["status"] === true){
				$this->swal("Sukses","ONU berhasil ditambahkan","success");
			}else{
				$this->swal("Gagal",$fb["response"],"error");
			}
		}else{
			$this->swal("Warning",validation_errors(),"warning");
		}
	}
	public function check_pon_type($str)
	{
		$array_rules = ["gpon"=>"OK","epon"=>"OK"];
		if(!empty($array_rules[$str])){
			return TRUE;
		}else{
			$this->form_validation->set_message('check_pon_type', '{field} hanya boleh diisi dengan GPON atau EPON');
			return FALSE;
		}
	}
	public function check_onu_mode($str)
	{
		$array_rules = ["Routing"=>"OK","Bridging"=>"OK"];
		if(!empty($array_rules[$str])){
			return TRUE;
		}else{
			$this->form_validation->set_message('check_pon_type', '{field} hanya boleh diisi dengan GPON atau EPON');
			return FALSE;
		}
	}
	public function get_all_onu_statuses()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://'.$this->domain.'.smartolt.com/api/onu/get_onus_statuses',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'X-Token: '.$this->token_olt
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		echo $response;
	}
	//END ONU//

	public function add_zone()
	{
		$this->form_validation->set_rules("zone","Zone","required|trim|xss_clean");
		if($this->form_validation->run() === TRUE){
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://{{subdomain}}.smartolt.com/api/system/add_zone',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array('zone' => 'City center'),
				CURLOPT_HTTPHEADER => array(
					'X-Token: {{api_key}}'
				),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$fb = json_decode($response,true);
			if($fb["status"] === true){
				$this->swal("Sukses",$fb["response"],"success");
			}else{
				$this->swal("Gagal",$fb["response"],"error");
			}
		}else{
			$this->swal("Gagal",validation_errors(),"error");
		}
		redirect();
	}
	public function get_zone_list()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://'.$this->domain.'.smartolt.com/api/system/get_zones',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'X-Token: '.$this->token_olt
			),
		));
		$response = curl_exec($curl);
		curl_close($curl);
		$data = json_decode($response,true);
		$row = '';
		if($data["status"] === true){
			$no = 1;
			foreach ($data["response"] as $key => $value) {
				$row .= '
				<tr>
					<td class="text-center p-2">'.$no++.'</td>
					<td class="text-center p-2" id="data-name-'.$value["id"].'">'.$value["name"].'</td>
					<td class="text-center p-2">
						<button class="btn btn-sm btn-info" data-id="'.$value["id"].'" title="Edit"><i class="fas fa-pencil-alt m-0 p-1"></i></button>
						<button class="btn btn-sm btn-danger" data-id="'.$value["id"].'" title="Delete"><i class="fas fa-trash-alt m-0 p-1"></i></button>
					</td>
				</tr>';
			}
			$fb = [
				"status" => true,
				"row" => $row,
			];
		}else{
			$fb = [
				"status" => false,
				"row" => '<tr><td colspan="10" class="text-center">'.$data["response"].'</td></tr>',
			];
		}
		echo json_encode($fb);
		die();

	}

	public function trial_snmp()
	{
		require_once(APPPATH."third_party/snmp.class.php");
		$session = new SNMP(SNMP::VERSION_2C, "127.0.0.1", "public");
		$sysdescr = $session->get("sysDescr.0");
		echo "$sysdescr\n";
		$sysdescr = $session->get(array("sysDescr.0"));
		print_r($sysdescr);
	}
}
