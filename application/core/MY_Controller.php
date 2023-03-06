<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
 
// Load Composer's autoloader
require 'vendor/autoload.php';

class MY_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
        $this->p1 = $this->uri->segment(1);
        $this->p2 = $this->uri->segment(2);
        $this->p3 = $this->uri->segment(3);
        $this->idusernzm = $this->session->userdata("id_user");
        if($this->session->userdata("id_mitra") > 0){
            $this->id_mitra = $this->session->userdata("id_mitra");
        }else{
            $this->id_mitra = $this->session->userdata("id_user");
        }
        $this->id_user = $this->session->userdata("id_user");
        $this->email = $this->session->userdata("email");
        $this->role_id = $this->session->userdata("role_id");
        $this->name = $this->session->userdata("name");
        $this->phone_number = $this->session->userdata("phone_number");
        $this->image_user = $this->session->userdata("image_user");
		$this->user_remote = $this->user_remote("user_remote");
		$this->server = $this->user_remote("server");
        $this->token_telegram = '5915969600:AAFhpKJhtMY6Nsp-qp7c-NsSlrY84-lwCEs';
        $this->harga_server = 10000;
		$this->domain = 'olean';
		$this->token_olt = 'bd7bd914098141a397711520c605da0d';
		$this->name_token = $this->security->get_csrf_token_name();
		$this->token = $this->security->get_csrf_hash();
		$this->csrf = $this->csrf();
    }
	public function csrf()
	{
		$element = '<input type="hidden" name="'.$this->name_token.'" value="'.$this->token.'">';
		return $element;
	}
	public function swal($title, $text, $icon)
	{
		$this->session->set_flashdata("swal", '
		<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
		<script>
			var text = "' . $text . '";
			swal.fire({title:"' . $title . '",html:text,icon:"' . $icon . '"});
		</script>');
	}
    public function encryption_elbark($value)
    {
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '1234567891011121';
        $encryption_key = 'ElbarkCruises';
        $encryption = openssl_encrypt($value, $ciphering, $encryption_key, $options, $encryption_iv);
        return $encryption;
    }
    public function decryption_elbark($value)
    {
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $decryption_iv = '1234567891011121';
        $decryption_key = 'ElbarkCruises';
        $decryption = openssl_decrypt($value, $ciphering, $decryption_key, $options, $decryption_iv);
        return $decryption;
    }

    public function send_telegram($pesan)
    {
        $apiToken = $this->token_telegram;
        $chat_id = $this->model->gd("user","id,id_telegram","id_telegram IS NOT NULL AND role_id = '1'","row");
        if(!empty($chat_id->id)){
            $chat_id = $chat_id->id_telegram;
        }else{
            $chat_id = '1313520747';
        };
        $text = $pesan;
        $response = file_get_contents("https://api.telegram.org/bot".$apiToken."/sendMessage?chat_id=".$chat_id."&text=".$text);
    }

    public function router_process($proses,$data)
    {
		ini_set('max_execution_time', 0);
        require_once(APPPATH."third_party/routeros_api.class.php");
        // echo APPPATH."third_party/routeros_api.class.php";
        // die();
        $id = $data["id"];
        $data_koneksi = $this->model->gd("api_routeros","*","id_mitra = '".$this->id_mitra."' AND id = '$id'","row");
        if(!empty($data_koneksi)){
            $API = new RouterosAPI();
            //$API->debug = true;
            if(!empty($data_koneksi->port)){
                $ip_address = $data_koneksi->ip_address.":".$data_koneksi->port;
            }else{
                $ip_address = $data_koneksi->ip_address;
            }
            $username = d_nzm($data_koneksi->username);
            $password = d_nzm($data_koneksi->password);
            $koneksi = $API->connect($ip_address, $username, $password);
            if ($koneksi) {
                //KONEKSI VPN
                if($proses == "koneksi"){
                    $status = 200;
                }

                //ADDITIONAL PPP SECRET
                if($proses == "ppp_secret"){
                    if(!empty($data)){
                        $API->comm('/ppp/secret/add', array(
                            'local-address' => '27.27.27.1',
                            'name' => $data["name"],
                            'password' => $data["password"],
                            'profile' => 'default',
                            'remote-address' => $data["address"],
                            'service' => 'l2tp',
                            'disabled' => 'no',
                        ));
                        $data_secret = $API->comm('/ppp/secret/print');
                        //print_r($data_secret);
                        $search_data = $this->search_array($data_secret,"name",$data["name"]);
                        if(!empty($search_data)){
                            $status = 200;
                        }else{
                            $this->send_telegram('Proses pembuatan PPP Secret Gagal, atas\nNama : '.$data["name"].'\nPassword : '.$data["password"]);
                            $status = 500;
                        }
                    }
                }

                //ADDITIONAL SCHEDULER
                if($proses == "scheduler"){
                    $proses = $API->comm('/system/scheduler/add', array(
                        'name' => $data["name"],
                        'on-event' => '/ppp secret remove [find name='.$data["name"].']; /system sche remove [find name='.$data["name"].']; /ip firewall nat remove [ find comment='.$data["name"].'];',
                        'policy' => 'ftp,reboot,read,write,policy,test,password,sniff,sensitive',
                        'start-date' => $data["start_date"],
                        'start-time' => '00:00:10',
                        'disabled' => 'no',
                    ));
                    $data_sheduler = $API->comm('/system/scheduler/print');
                    $search_data = $this->search_array($data_sheduler,"name",$data["name"]);
                    if(!empty($search_data)){
                        $status = 200;
                    }else{
                        $this->send_telegram('Proses pembuatan Scheduler Gagal, atas\nNama : '.$data["name"].'\nStart Date : '.$data["start_date"].'\nError : '.$proses);
                        $status = 500;
                    }
                }

                //ADDITIONAL FIREWALL NAT
                if($proses == "firewall_nat"){
                    $API->write('/ip/firewall/nat/add', false);
                    $API->write('=action=dst-nat', false);
                    $API->write('=chain=dstnat',false);
                    $API->write('=comment='.$data["name"],false);
                    $API->write('=dst-address=10.20.120.187',false);
                    $API->write('=dst-port='.$data["dst_port"],false);
                    $API->write('=protocol=tcp',false);
                    $API->write('=to-addresses='.$data["address"],false);
                    $API->write('=to-ports='.$data["port"],false);
                    $API->write('=disabled=no');
                    $data_firewall = $API->comm('/ip/firewall/nat/print');
                    //print_r($data_firewall);
                    if($data_firewall){
                        $status = 200;
                    }else{
                        $this->send_telegram('Proses pembuatan Firewall Nat Gagal, atas\nNama : '.$data["name"].'\nNomor : '.$data["nomor"].'\nPort : '.$data["port"]);
                        $status = 500;
                    }
                }

                //EDIT PPP SECRET
                if($proses == "edit_ppp"){
                    $arrID = $API->comm("/ppp/secret/getall", 
                        array(
                            ".proplist"=> ".id",
                            "?name" => $data["name"],
                        ));

                    $proses_edit_ppp = $API->comm("/ppp/secret/set",
                        array(
                                ".id" => $arrID[0][".id"],
                                "password" => $data["password"]
                            )
                        );
                    if($proses_edit_ppp){
                        $status = 200;
                    }else{
                        $status = 500;
                    }
                }

                //REMOVE FIREWAL NAT
                if($proses == "remove_firewall"){
                    $API->write('/ip/firewall/nat/print', false);
                    $API->write('?comment='.$data["name"], false);
                    $API->write('=.proplist=.id');
                    $ARRAYS = $API->read();
                    
                    $API->write('/ip/firewall/nat/remove', false);
                    $API->write('=.id=' . $ARRAYS[0]['.id']);
                    $READ = $API->read();
                    if($READ){
                        $status = 200;
                    }else{
                        $status = 500;
                    }
                }

                //REMOVE PPP
                if($proses == "remove_ppp"){
                    $arrID = $API->comm("/ppp/secret/getall", 
                        array(
                            ".proplist"=> ".id",
                            "?name" => $data["name"],
                        ));

                    $proses_remove_ppp = $API->comm("/ppp/secret/remove",
                        array(
                                ".id" => $arrID[0][".id"]
                            )
                        );
                    if($proses_remove_ppp){
                        $status = 200;
                    }else{
                        $status = 500;
                    }
                }

                //REMOVE SCHEDULE
                if($proses == "remove_schedule"){
                    $arrID = $API->comm("/system/scheduler/getall", 
                        array(
                            ".proplist"=> ".id",
                            "?name" => $data["name"],
                        ));

                    $proses_remove_schedule = $API->comm("/system/scheduler/remove",
                        array(
                                ".id" => $arrID[0][".id"]
                            )
                        );
                    if($proses_remove_schedule){
                        $status = 200;
                    }else{
                        $status = 500;
                    }
                }

                //INTERFACE LIST
                if($proses == "print_interface"){
                    $interface = $API->comm('/interface/print');
                    $status = $interface;
                }

                //PARAMETER PROSES EXPLODE
                $proses = explode("/",$proses);
                //PPPOE SERVER
                if($proses[0] == "pppoe_servers"){
                    if($proses[1] == "list"){
                        $arrID = $API->comm("/interface/pppoe-server/server/getall");
                        $status = $arrID;
                    }else if($proses[1] == "add"){
                        if(!empty($data)){
                            $API->comm('/interface/pppoe-server/server/add', array(
                                'service-name' => $data["service_name"],
                                'interface' => $data["interface"],
                                'disabled' => $data["disabled"],
                            ));
                            $print_data = $API->comm('/interface/pppoe-server/server/print');
                            $search_data = $this->search_array($print_data,"service-name",$data["service_name"]);
                            if(!empty($search_data)){
                                $status = 200;
                            }else{
                                $status = 500;
                            }
                        }
                    }else if($proses[1] == "update"){
                        if(!empty($data)){
                            $proses_edit_pppoe_servers = $API->comm("/interface/pppoe-server/server/set",
                            array(
                                    ".id" => $data[".id"],
                                    "service-name" => $data["service_name"],
                                    "interface" => $data["interface"],
                                    "disabled" => $data["disabled"]
                                )
                            );
                            if(!$proses_edit_pppoe_servers){
                                $status = 200;
                            }else{
                                $status = 500;
                            }
                        }
                    }else if($proses[1] == "remove"){
                        if(!empty($data)){
                            $remove = $API->comm("/interface/pppoe-server/server/remove",
                                array(
                                        ".id" => $data[".id"]
                                    )
                                );
                            if(!$remove){
                                $status = 200;
                            }else{
                                $status = 500;
                            }
                        }
                    }
                }

                //IP Pool
                if($proses[0] == "ip_pool"){
                    if($proses[1] == "list"){
                        $arrID = $API->comm("/ip/pool/getall");
                        $status = $arrID;
                    }else if($proses[1] == "add"){
                        if(!empty($data)){
                            $API->comm('/ip/pool/add', array(
                                'name' => $data["name"],
                                'ranges' => $data["addresses"],
                            ));
                            $print_data = $API->comm('/ip/pool/print');
                            $search_data = $this->search_array($print_data,"name",$data["name"]);
                            if(!empty($search_data)){
                                $status = 200;
                            }else{
                                $status = 500;
                            }
                        }
                    }else if($proses[1] == "update"){
                        if(!empty($data)){
                            $update = $API->comm("/ip/pool/set",
                            array(
                                    ".id" => $data[".id"],
                                    'name' => $data["name"],
                                    'ranges' => $data["addresses"],
                                )
                            );
                            if(!$update){
                                $status = 200;
                            }else{
                                $status = 500;
                            }
                        }
                    }else if($proses[1] == "remove"){
                        if(!empty($data)){
                            $remove = $API->comm("/ip/pool/remove",
                                array(
                                        ".id" => $data[".id"]
                                    )
                                );
                            if(!$remove){
                                $status = 200;
                            }else{
                                $status = 500;
                            }
                        }
                    }
                }

                //PPPOE CLIENT
                if($proses[0] == "pppoe_client"){
                    if($proses[1] == "list"){
                        $arrID = $API->comm("/ppp/secret/getall");
                        $status = $arrID;
                    }else{
                        if($proses[1] == "add"){
                            if(!empty($data)){
                                $action = $API->comm('/ppp/secret/add', $data["data"]);
                            }
                        }else if($proses[1] == "update"){
                            if(!empty($data)){
                                $action = $API->comm("/ppp/secret/set", $data["data"]);
                            }
                        }else if($proses[1] == "remove"){
                            if(!empty($data)){
                                $action = $API->comm("/ppp/secret/remove", $data["data"]);
                            }
                        }

                        if($action || empty($action)){
                            $status = 200;
                        }else{
                            $status = $action;
                        }
                    }
                }

                //ProfilePPPoE
                if($proses[0] == "profile_pppoe"){
                    if($proses[1] == "list"){
                        $arrID = $API->comm("/ppp/profile/getall");
                        $status = $arrID;
                    }else if($proses[1] == "add"){
                        if(!empty($data)){
                            $add = $API->comm('/ppp/profile/add', array(
                                'name' => $data["name"],
                                'local-address' => $data["local_address"],
                                'remote-address' => $data["remote_address"],
                                'rate-limit' => $data["rate_limit"],
                                'only-one' => strtolower($data["only_one"]),
                                'comment' => $data["comment"]
                            ));
                            if($add){
                                $status = 200;
                            }else{
                                $status = 500;
                            }
                        }
                    }else if($proses[1] == "update"){
                        if(!empty($data)){
                            $update = $API->comm("/ppp/profile/set",
                            array(
                                    ".id" => $data[".id"],
                                    'name' => $data["name"],
                                    'local-address' => $data["local_address"],
                                    'remote-address' => $data["remote_address"],
                                    'rate-limit' => $data["rate_limit"],
                                    'only-one' => strtolower($data["only_one"]),
                                    'comment' => $data["comment"],
                                )
                            );
                            if(!$update){
                                $status = 200;
                            }else{
                                $status = 500;
                            }
                        }
                    }else if($proses[1] == "remove"){
                        if(!empty($data)){
                            $remove = $API->comm("/ppp/profile/remove",
                                array(
                                        ".id" => $data[".id"]
                                    )
                                );
                            if(!$remove){
                                $status = 200;
                            }else{
                                $status = 500;
                            }
                        }
                    }
                }

                //SCHEDULER
                if($proses[0] == "sche"){
                    if($proses[1] == "list"){
                        $list = $API->comm("/system/scheduler/getall");
                        $status = $list;
                    }else{
                        if($proses[1] == "add"){
                            if(!empty($data)){
                                $action = $API->comm('/system/scheduler/add', $data["data"]);
                            }
                        }else if($proses[1] == "update"){
                            if(!empty($data)){
                                $action = $API->comm("/system/scheduler/set",$data["data"]);
                            }
                        }else if($proses[1] == "remove"){
                            if(!empty($data)){
                                $action = $API->comm("/system/scheduler/remove", $data["data"]);
                            }
                        }

                        if($action || empty($action)){
                            $status = 200;
                        }else{
                            $status = $action;
                        }
                    }
                }

                //ALL PROCESS
                if($proses[0] == "router"){
                    $action = $API->comm($data["proses"], $data["data"]);
                    if(!empty($action["!trap"])){
                        $status = $action;
                    }else{
                        if(empty($data["data"])){
                            $status = $action;
                        }else{
                            if(substr_count($data["proses"],"monitor-traffic") > 0){
                                $status = $action;
                            }else{
                                $status = 200;
                            }
                        }
                    }
                }
            }else{
                $this->send_telegram('Koneksi Routeros API tidak terhubung, mohon periksa IP Address, Port, Username, dan Password anda.');
                $status = $koneksi;
            }
            $API->disconnect();
        }else{
            $this->send_telegram('Koneksi Routeros API tidak terhubung, Sistem tidak dapat menemukan data IP Address, Username, dan Password, mohon check settingan API Routeros Anda');
            $status = 500;
        }
        return $status;
    }

    public function search_array($array,$column,$search)
    {
        $return = '';
        if(is_array($array)){
            foreach ($array as $key => $value) {
                foreach ($value as $column_name => $value_column) {
                    if($value_column == $search){
                        $return .= $value[".id"];
                    }
                }
            }
        }
        return $return;
    }

    public function user_remote($p)
    {
        $get_user = $this->model->gd("user","id,user_remote,server","id_mitra = '".$this->id_mitra."' AND role_id = '1'","row");
        if(!empty($get_user->id)){
            if($p == "user_remote"){
                $data = "@".$get_user->user_remote;
            }else if($p == "server"){
                $data = $get_user->server;
            }
        }else{
            if($p == "user_remote"){
                $data = "@Texa.net";
            }else if($p == "server"){
                $data = "Texa.my.id";
            }
        }
        return $data;
    }

    public function check_id_routeros($id_server)
    {
        $data_server = $this->model->gd("vpn_master","id_server","id_mitra = '".$this->id_mitra."' AND id = '$id_server'","row");
        if(!empty($data_server->id_server)){
            $data_routeros = $this->model->gd("api_routeros","id","id_mitra = '".$this->id_mitra."' AND id_server = '".$data_server->id_server."'","row");
            if(!empty($data_routeros->id)){
                $id_routeros = $data_routeros->id;
            }else{
                $id_routeros = 0;
            }
        }else{
            $id_routeros = 0;
        }
        return $id_routeros;
    }

    public function email_send($to,$subject,$message)
    {
        $this->load->config('email');
        $this->load->library('email');
        
        $from = $this->config->item('smtp_user');

        $this->email->set_newline("\r\n");
        $this->email->from($from);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if($this->email->send()){
            $status = 200;
        }else{
            $status = 500;
        }

        return $status;
    }

    public function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array($needle, $item, $strict))) {
                return true;
            }
        }
    
        return false;
    }

    public function sendemail($to,$subject,$body)
    {
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();       
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );                                  // Send using SMTP
        $mail->Host       = 'mikrotik.texa.my.id';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'no-reply@mikrotik.texa.my.id';                     // SMTP username
        $mail->Password   = 'Optimissukses100%';                               // SMTP password
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->Timeout = 60; // timeout pengiriman (dalam detik)
        $mail->SMTPKeepAlive = true; 

        //Recipients
        $mail->setFrom('no-reply@mikrotik.texa.my.id','TEXAID');
        $mail->addAddress($to);     // Add a recipient
        $mail->addReplyTo('no-reply@mikrotik.texa.my.id','TEXAID');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        // Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        if($mail->send()){
            $return = 200;
        }else{
            $return = $mail->ErrorInfo;
        }

        return $return;
    }

    public function getStartAndEndDate($week, $year) {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
    }
}
?>
