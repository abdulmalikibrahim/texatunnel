<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Api_WA extends MY_Controller
{

	public function warobot(){
        $harga_server = $this->harga_server;
        $app = $this->input->post("app");
        $sender = $this->input->post("sender");
        $message = $this->input->post("message");
        if($this->input->post("phone") == "WhatsAuto"){
            $phone = "087708763253";
        }else{
            $phone = str_replace("-","",str_replace("+62 ","0",$this->input->post("phone")));
        }
        // $app = "Whats";
        // $phone = "087708763253";
        // $message = "Nonactive Server 103.13.206.221";
        if(is_numeric($phone)){
            if(substr_count($app,"Whats") > 0){
                if($message == "Halo TEXA, aktifkan akun saya sekarang" || $message == "Activation" || $message == "Aktifkan akun saya sekarang"){ // Proses Aktivasi akun
                    $check_status = $this->model->gd("user","id,id_mitra,is_active,role_id","phone_number = '$phone'","row");
                    if(!empty($check_status)){
                        if($check_status->is_active == "0"){
                            if($check_status->role_id == "2"){
                                if($check_status->id_mitra != "0"){
                                    $rule_active = "OK";
                                }else{
                                    $rule_active = "NOT";
                                }
                            }else{
                                if($check_status->id_mitra != "0"){
                                    $rule_active = "NOT";
                                }else{
                                    $rule_active = "OK";
                                }
                            }

                            if($rule_active == "OK"){
                                if($check_status->role_id == "1"){
                                    $data_update = [
                                        "is_active" => "1",
                                        "saldo" => $harga_server,
                                    ];
                                }else{
                                    $data_update = [
                                        "is_active" => "1",
                                    ];
                                }
                                $proses = $this->model->update("user","phone_number = '$phone'", $data_update);
                                if(!$proses){
                                    if($message == "Aktifkan akun saya sekarang"){
                                        $mitra = $this->model->gd("user","nama_mitra,phone_number","id = '".$check_status->id_mitra."'","row");
                                        if(!empty($mitra)){
                                            $reply = "Selamat bergabung di ".$mitra->nama_mitra."\nAkun anda berhasil di aktifkan\n\nContact ".$mitra->nama_mitra." :\n".$mitra->phone_number."\n\nSilahkan klik link dibawah ini untuk login\n".base_url();
                                        }else{
                                            $proses = $this->model->delete("user","phone_number = '$phone'");
                                            $reply = "Mohon maaf, Akun anda kami hapus karena anda bergabung dengan mitra yang tidak terdaftar.";
                                        }
                                    }else{
                                        $reply = "Selamat, akun anda berhasil di aktifkan\nSilahkan klik link dibawah ini untuk login\n".base_url();
                                    }
                                }else{
                                    $reply = "Akun anda gagal di aktifkan, Silahkan coba beberapa saat lagi.";
                                }
                            }else{
                                $reply = "Rule activation not valid";
                            }
                        }else{
                            $reply = "Akun anda sudah aktif\nSilahkan klik link dibawah ini untuk login\n".base_url();
                        }
                    }else{
                        $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                    }
                }else if(substr_count($message,"TEXA REGISTRASI") > 0){
                    $get_user = $this->model->gd("user","id,is_active,saldo","phone_number = '$phone'","row");
                    if(empty($get_user)){
                        $pesan_explode = explode("\n",$message);
                        foreach ($pesan_explode as $key => $value) {
                            if(!empty($value)){
                                $data_get = explode(":",$value);
                                if(!empty($data_get[1])){
                                    $data_wa = str_replace("\r","",$data_get[1]);
                                    if(!empty(str_replace(" ","",$data_wa))){
                                        $data_wa = trim($data_wa);
                                    }else{
                                        $data_wa = "";
                                    }
                                }else{
                                    $data_wa = "";
                                }
                                if(!empty($data_wa)){
                                    if(substr_count($value,"Nama Lengkap") > 0){
                                        $data_reg_wa["name"] = htmlspecialchars($data_wa);
                                    }else if(substr_count($value,"Email") > 0){
                                        $data_reg_wa["email"] = htmlspecialchars($data_wa);
                                    }else if(substr_count($value,"Password") > 0){
                                        $data_reg_wa["password"] = password_hash(htmlspecialchars($data_wa),PASSWORD_DEFAULT);
                                    }else if(substr_count($value,"Nama Mitra") > 0){
                                        $data_reg_wa["nama_mitra"] = htmlspecialchars($data_wa);
                                    }else if(substr_count($value,"Alamat Mitra") > 0){
                                        $data_reg_wa["alamat"] = htmlspecialchars($data_wa);
                                    }else if(substr_count($value,"Provinsi") > 0){
                                        $data_reg_wa["provinsi"] = htmlspecialchars($data_wa);
                                    }else if(substr_count($value,"Kabupaten") > 0){
                                        $data_reg_wa["kabupaten"] = htmlspecialchars($data_wa);
                                    }else if(substr_count($value,"Kecamatan") > 0){
                                        $data_reg_wa["kecamatan"] = htmlspecialchars($data_wa);
                                    }else if(substr_count($value,"Kelurahan") > 0){
                                        $data_reg_wa["kelurahan"] = htmlspecialchars($data_wa);
                                    }
                                }
                            }
                        }
                
                        $pesan_error = "";
                        if(empty($data_reg_wa["name"])){
                            $pesan_error .= "Nama Lengkap tidak boleh kosong\n";
                        }
                        if(empty($data_reg_wa["email"])){
                            $pesan_error .= "Email tidak boleh kosong\n";
                        }
                        if(empty($data_reg_wa["password"])){
                            $pesan_error .= "Password tidak boleh kosong\n";
                        }
                        if(empty($data_reg_wa["nama_mitra"])){
                            $pesan_error .= "Nama Mitra tidak boleh kosong\n";
                        }
                        if(empty($data_reg_wa["alamat"])){
                            $pesan_error .= "Alamat tidak boleh kosong\n";
                        }
                        if(empty($data_reg_wa["provinsi"])){
                            $pesan_error .= "Provinsi tidak boleh kosong\n";
                        }
                        if(empty($data_reg_wa["kabupaten"])){
                            $pesan_error .= "Kabupaten tidak boleh kosong\n";
                        }
                        if(empty($data_reg_wa["kecamatan"])){
                            $pesan_error .= "Kecamatan tidak boleh kosong\n";
                        }
                        if(empty($data_reg_wa["kelurahan"])){
                            $pesan_error .= "Kelurahan tidak boleh kosong\n";
                        }
                        if(empty($pesan_error)){
                            //Check email
                            $email = $data_reg_wa["email"];
                            $check_email = $this->model->gd("user","email","email = '$email'","row");
                            if(!empty($check_email->email)){
                                $reply = "Email sudah terdaftar di TEXA.\nSilahkan gunakan email lain untuk proses registrasi";
                            }else{
                                $data_reg_wa["phone_number"] = $phone;
                                $data_reg_wa["image"] = "default.jpg";
                                $data_reg_wa["role_id"] = "1";
                                $data_reg_wa["date_created"] = time();
                                $data_reg_wa["saldo"] = $harga_server;
                                $proses = $this->model->insert("user",$data_reg_wa);
                                if(!$proses){
                                    if($data_reg_wa["saldo"] > 0){
                                        $reply = "Selamat,\nAkun anda berhasil di buat.\nAnda juga mendapatkan free saldo registrasi awal dari kami sebesar ".number_format($data_reg_wa["saldo"],0,"",".")."\nSilahkan klik link dibawah ini untuk login.\n\n".base_url();
                                    }else{
                                        $reply = "Selamat,\nAkun anda berhasil di buat.\nSilahkan klik link dibawah ini untuk login.\n\n".base_url();
                                    }
                                }else{
                                    $reply = "Maaf, akun anda gagal kami buat. Mohon ulangi proses beberap saat lagi.\nAtau bisa mendaftar melalui Website kami.\n\n".base_url();
                                }
                            }
                        }else{
                            $reply = $pesan_error;
                        }
                    }else{
                        $reply = "No WA ini sudah terdaftar di TEXA.\nMohon gunakan no WA yang lain untuk registrasi.";
                    }
                }else if(substr_count($message,"Buat Server") > 0){
                    $get_user = $this->model->gd("user","id,is_active,saldo","phone_number = '$phone'","row");
                    if(!empty($get_user)){
                        if($get_user->is_active == "1"){ //Jika user sudah aktif
                            $pesan_explode = explode("\n",$message);
                            foreach ($pesan_explode as $key => $value) {
                                if(!empty($value)){
                                    $data_get = explode(":",$value);
                                    if(!empty($data_get[1])){
                                        $data_wa = str_replace("\r","",$data_get[1]);
                                        if(!empty(str_replace(" ","",$data_wa))){
                                            $data_wa = trim($data_wa);
                                        }else{
                                            $data_wa = "";
                                        }
                                    }else{
                                        $data_wa = "";
                                    }
                                    if(!empty($data_wa)){
                                        if(substr_count($value,"Nama Server") > 0){
                                            $data_reg_wa["nama_server"] = htmlspecialchars($data_wa);
                                        }else if(substr_count($value,"IP Address") > 0){
                                            $data_reg_wa["ip_address"] = htmlspecialchars($data_wa);
                                        }else if(substr_count($value,"Port") > 0){
                                            $data_reg_wa["port"] = htmlspecialchars($data_wa);
                                        }else if(substr_count($value,"Username") > 0){
                                            $data_reg_wa["username"] = e_nzm(htmlspecialchars($data_wa));
                                        }else if(substr_count($value,"Password") > 0){
                                            $data_reg_wa["password"] = e_nzm(htmlspecialchars($data_wa));
                                        }else if(substr_count($value,"Country") > 0){
                                            $data_reg_wa["country"] = htmlspecialchars($data_wa);
                                        }
                                    }
                                }
                            }
                    
                            $pesan_error = "";
                            if(empty($data_reg_wa["nama_server"])){
                                $pesan_error .= "Nama Server tidak boleh kosong\n";
                            }
                            if(empty($data_reg_wa["ip_address"])){
                                $pesan_error .= "IP Address tidak boleh kosong\n";
                            }
                            if(!empty($data_reg_wa["port"])){
                                if(is_numeric($data_reg_wa["port"])){
                                    $pesan_error .= "";
                                }else{
                                    $pesan_error .= "Port harus berisi angka\n";
                                }
                                $port = $data_reg_wa["port"];
                            }else{
                                $port = "";
                            }
                            if(empty($data_reg_wa["username"])){
                                $pesan_error .= "Username tidak boleh kosong\n";
                            }
                            if(empty($data_reg_wa["password"])){
                                $pesan_error .= "Password tidak boleh kosong\n";
                            }
                            if(empty($data_reg_wa["country"])){
                                $pesan_error .= "Country tidak boleh kosong\n";
                            }
                            if(empty($pesan_error)){
                                $id_user = $get_user->id;
                                $saldo = $get_user->saldo;
                                $data_reg_wa["id_mitra"] = $id_user;
                                $data_reg_wa["id_server"] = time().$id_user;
                                $data_reg_wa["is_active"] = "1";
                                $data_reg_wa["expired_date"] = date("Y-m-d",strtotime("+1 month"));
                                if($saldo >= $harga_server){
                                    $tambah_server = $this->model->insert("api_routeros",$data_reg_wa);
                                    if(!$tambah_server){
                                        $saldo_new = [
                                            "saldo" => ($saldo - $harga_server),
                                        ];
                                        $update_saldo = $this->model->update("user","id = '$id_user'",$saldo_new);
                                        $reply = "*Server Berhasil Dibuat*\nNama Server : ".$data_reg_wa["nama_server"]."\nIP Address : ".$data_reg_wa["ip_address"]."\nPort : ".$port."\nExpired : ".date("d-m-Y",strtotime($data_reg_wa["expired_date"]))."\nStatus : Aktif";
                                    }else{
                                        $reply = "*Server Gagal Dibuat*, Mohon coba beberapa saat lagi.";
                                    }
                                }else{
                                    $reply = "Saldo anda kurang.\nSaldo saat ini : ".number_format($saldo,0,"",".");
                                }
                            }else{
                                $reply = $pesan_error;
                            }
                        }else{
                            $reply = "Akun anda belum aktif\nSilahkan balas dengan keyword Activation untuk mengaktifkan akun anda";
                        }
                    }else{
                        $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                    }
                }else if(substr_count($message,"Extend Server")){ //Action to extending server
                    $get_user = $this->model->gd("user","id,is_active,saldo","phone_number = '$phone'","row");
                    if(!empty($get_user)){
                        if($get_user->is_active == "1"){
                            if($get_user->saldo >= $harga_server){
                                $explode_massage = explode(" ",$message);
                                $ip_address = trim($explode_massage[2]);
                                $server = $this->model->gd("api_routeros","nama_server,ip_address,id,expired_date","id_mitra = '".$get_user->id."' AND ip_address = '$ip_address'","row");
                                if(!empty($server)){
                                    $id_server = $server->id;
                                    if(strtotime($server->expired_date) <= strtotime(date("Y-m-d"))){ //Jika expired date sudah terlewat atau sama dengan hari ini
                                        $data_update = [
                                            "expired_date" => date("Y-m-d",strtotime("+1 month")),
                                            "is_active" => "1",
                                        ];
                                        $new_date = date("d-m-Y",strtotime("+1 month"));
                                    }else if(strtotime($server->expired_date) > strtotime(date("Y-m-d"))){ //Jika expired date belum terlewat dari hari ini
                                        $data_update = [
                                            "expired_date" => date("Y-m-d",strtotime("+1 month",strtotime($server->expired_date))),
                                            "is_active" => "1",
                                        ];
                                        $new_date = date("d-m-Y",strtotime("+1 month",strtotime($server->expired_date)));
                                    }
                                    $action = $this->model->update("api_routeros","id = '$id_server'",$data_update);
                                    if(!$action){
                                        $saldo_new = [
                                            "saldo" => ($get_user->saldo - $harga_server),
                                        ];
                                        $action = $this->model->update("user","id = '".$get_user->id."'",$saldo_new);
                                        $reply = "*Perpanjangan Berhasil*\nNama Server : ".$server->nama_server."\nIP Address : ".$server->ip_address."\nExpired : ".$new_date."\nStatus : Aktif\n\nSaldo anda : ".number_format($saldo_new["saldo"],0,"",".");
                                    }else{
                                        $reply = "*Perpanjangan Gagal*\nMohon coba beberapa saat lagi.";
                                    }
                                }else{
                                    $reply = "IP Address yang anda masukkan tidak valid";
                                }
                            }else{
                                $reply = "Maaf saldo anda kurang.\nSaldo saat ini : ".number_format($get_user->saldo,0,"",".");
                            }
                        }else{
                            $reply = "Akun anda belum aktif\nSilahkan balas dengan keyword Activation untuk mengaktifkan akun anda";
                        }
                    }else{
                        $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                    }
                }else if(substr_count($message,"Nonactive Server")){ //Action to non activating server
                    $get_user = $this->model->gd("user","id,is_active,saldo","phone_number = '$phone'","row");
                    if(!empty($get_user)){
                        if($get_user->is_active == "1"){
                            $explode_massage = explode(" ",$message);
                            $ip_address = trim($explode_massage[2]);
                            $server = $this->model->gd("api_routeros","nama_server,ip_address,id,expired_date","id_mitra = '".$get_user->id."' AND ip_address = '$ip_address'","row");
                            if(!empty($server)){
                                $id_server = $server->id;
                                $data_update = [
                                    "is_active" => "0",
                                ];
                                $action = $this->model->update("api_routeros","id = '$id_server'",$data_update);
                                if(!$action){
                                    $reply = "*Server Berhasil Dimatikan*\nNama Server : ".$server->nama_server."\nIP Address : ".$server->ip_address."\nExpired : ".date("d-m-Y",strtotime($server->expired_date))."\nStatus : Non Aktif";
                                }else{
                                    $reply = "*Server Gagal Dimatikan*\nMohon coba beberapa saat lagi.";
                                }
                            }else{
                                $reply = "IP Address yang anda masukkan tidak valid";
                            }
                        }else{
                            $reply = "Akun anda belum aktif\nSilahkan balas dengan keyword Activation untuk mengaktifkan akun anda";
                        }
                    }else{
                        $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                    }
                }else if(substr_count($message,"Active Server")){ //Action to activating server
                    $get_user = $this->model->gd("user","id,is_active","phone_number = '$phone'","row");
                    if(!empty($get_user)){
                        if($get_user->is_active == "1"){ //Jika user sudah aktif
                            $explode_massage = explode(" ",$message); //Partialkan  pesan dengan parameter spasi
                            $ip_address = trim($explode_massage[2]); //mengambil value ip address dari partial pesan
                            $server = $this->model->gd("api_routeros","nama_server,ip_address,id,expired_date","id_mitra = '".$get_user->id."' AND ip_address = '$ip_address'","row"); //Cari server berdasarkan user id dan ip address
                            if(!empty($server)){ //jika server tidak ditemukan di database
                                if(strtotime($server->expired_date) < strtotime(date("Y-m-d"))){ //Jika expired date sudah terlewat atau sama dengan hari ini
                                    $reply = "Server anda sudah expired, mohon gunakan keyword \nExtend Server ipaddress_server\nuntuk mengaktifkan server anda.";
                                }else{ //Jika expired date belum terlewat dari hari ini
                                    $id_server = $server->id; //id server
                                    $data_update = [ //Data update untuk activating server
                                        "is_active" => "1",
                                    ];
                                    $action = $this->model->update("api_routeros","id = '$id_server'",$data_update); //Eksekusi activating server
                                    if(!$action){ //jika proses activating berhasil
                                        $reply = "*Server Berhasil Diaktifkan*\nNama Server : ".$server->nama_server."\nIP Address : ".$server->ip_address."\nExpired : ".date("d-m-Y",strtotime($server->expired_date))."\nStatus : Aktif";
                                    }else{
                                        $reply = "*Server Gagal Diaktifkan*\nMohon coba beberapa saat lagi.";
                                    }
                                }
                            }else{
                                $reply = "IP Address yang anda masukkan tidak valid";
                            }
                        }else{
                            $reply = "Akun anda belum aktif\nSilahkan balas dengan keyword Activation untuk mengaktifkan akun anda";
                        }
                    }else{
                        $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                    }
                }else if($message == "3"){ //Check Saldo
                    $check_status = $this->model->gd("user","id,is_active,saldo","phone_number = '$phone'","row");
                    if(!empty($check_status)){
                        if($check_status->is_active == "1"){
                            $reply = "Saldo Anda Saat Ini :\nRp. ".number_format($check_status->saldo,0,"",".");
                        }else{
                            $reply = "Akun anda belum aktif\nSilahkan balas dengan keyword Activation untuk mengaktifkan akun anda";
                        }
                    }else{
                        $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                    }
                }else if(substr_count($message,"4") > 0){ //Server Menu
                    if($message == "4.1" || $message == "4.3" || $message = "4.4"){ //Check Server atau non aktifkan server atau aktifkan server
                        $get_user = $this->model->gd("user","id,is_active","phone_number = '$phone'","row");
                        if(!empty($get_user)){
                            if($get_user->is_active == "1"){
                                if($message == "4.1"){
                                    $server = $this->model->gd("api_routeros","nama_server,ip_address,is_active,expired_date","id_mitra = '".$get_user->id."'","result");
                                }else if($message == "4.3"){
                                    $server = $this->model->gd("api_routeros","nama_server,ip_address,is_active,expired_date","id_mitra = '".$get_user->id."' AND is_active = '1'","result");
                                }else if($message == "4.4"){
                                    $server = $this->model->gd("api_routeros","nama_server,ip_address,is_active,expired_date","id_mitra = '".$get_user->id."' AND is_active = '0'","result");
                                }
                                if(!empty($server)){
                                    $reply = "";
                                    foreach ($server as $server) {
                                        if($server->is_active > 0){
                                            $status = "Aktif";
                                        }else{
                                            $status = "Non Aktif";
                                        }
                                        $reply .= "\nNama Server : ".$server->nama_server."\nIP Address : ".$server->ip_address."\nExpired : ".date("d-m-Y",strtotime($server->expired_date))."\nStatus : ".$status."\n________________________\n";
                                    }
                                    if($message == "4.3"){
                                        $reply .= "\nBalas dengan format :\nNonactive Server ipaddress_server";
                                    }else if($message == "4.4"){
                                        $reply .= "\nBalas dengan format :\nActive Server ipaddress_server";
                                    }
                                }else{
                                    $reply = "Anda belum memiliki server, Silahkan login dan tambahkan server anda.\n\n".base_url();
                                }
                            }else{
                                $reply = "Akun anda belum aktif\nSilahkan balas dengan keyword Activation untuk mengaktifkan akun anda";
                            }
                        }else{
                            $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                        }
                    }
                }else if(substr_count($message,"5") > 0){ //Check Pendapatan
                    $get_user = $this->model->gd("user","id,is_active","phone_number = '$phone'","row");
                    if(!empty($get_user)){
                        if($get_user->is_active == "1"){
                            if($message == "5.1"){ //Pendapatan Bulan Ini
                                $pendapatan = $this->model->gd("top_up","SUM(nominal) as total","id_mitra = '".$get_user->id."' AND tanggal LIKE '%".date("Y-m-")."%' AND status = 'Sukses'","row");
                                $header_text = "*Pendapatan Bulan ".date("F")."*";
                            }else if($message == "5.2"){// Pendapatan Minggu Ini
                                $week_number = date("W");
                                $week_array = $this->getStartAndEndDate($week_number, date("Y"));
                                $start_date = $week_array["week_start"]." 00:00:00";
                                $end_date = $week_array["week_end"]." 00:00:00";
                                $pendapatan = $this->model->gd("top_up","SUM(nominal) as total","id_mitra = '".$get_user->id."' AND tanggal BETWEEN '$start_date' AND '$end_date' AND status = 'Sukses'","row");
                                $header_text = "*Pendapatan Minggu Ke ".$week_number."*\n".date("d-m-Y",strtotime($week_array["week_start"]))." - ".date("d-m-Y",strtotime($week_array["week_end"]));
                            }else{ //Pendapatan Hari Ini
                                $pendapatan = $this->model->gd("top_up","SUM(nominal) as total","id_mitra = '".$get_user->id."' AND tanggal LIKE '%".date("Y-m-d")."%' AND status = 'Sukses'","row");
                                $header_text = "*Pendapatan Hari Ini*\n".date("d-m-Y");
                            }
                            $reply = $header_text."\n\nRp. ".number_format(($pendapatan->total*1),0,"",".");
                        }else{
                            $reply = "Akun anda belum aktif\nSilahkan balas dengan keyword Activation untuk mengaktifkan akun anda";
                        }
                    }else{
                        $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                    }
                }else if($message == "6"){ //Perpanjangan masa aktif Server
                    $get_user = $this->model->gd("user","id,is_active","phone_number = '$phone'","row");
                    if(!empty($get_user)){
                        if($get_user->is_active == "1"){
                            $server = $this->model->gd("api_routeros","nama_server,ip_address,is_active,expired_date","id_mitra = '".$get_user->id."'","result");
                            if(!empty($server)){
                                $reply = "";
                                foreach ($server as $server) {
                                    if($server->is_active > 0){
                                        $status = "Aktif";
                                    }else{
                                        $status = "Non Aktif";
                                    }
                                    $reply .= "\nNama Server : ".$server->nama_server."\nIP Address : ".$server->ip_address."\nExpired : ".date("d-m-Y",strtotime($server->expired_date))."\nStatus : ".$status."\n________________________\n";
                                }
                                $reply .= "\n\nBalas dengan format :\nExtend Server ipaddress_server";
                            }else{
                                $reply = "Anda belum memiliki server, Silahkan login dan tambahkan server anda.\n\n".base_url();
                            }
                        }else{
                            $reply = "Akun anda belum aktif\nSilahkan balas dengan keyword Activation untuk mengaktifkan akun anda";
                        }
                    }else{
                        $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                    }
                }else if($message == "7"){ //Lupa Password
                    $get_user = $this->model->gd("user","id,is_active","phone_number = '$phone'","row");
                    if(!empty($get_user)){
                        $new_pass_rand = rand(111111,999999);
                        $new_pass = password_hash($new_pass_rand,PASSWORD_BCRYPT);
                        $data_password = [
                            "password" => $new_pass,
                        ];
                        $action = $this->model->update("user","id = '".$get_user->id."'",$data_password);
                        if(!$action){
                            $reply = "*Reset Password Berhasil*\nSilahkan login menggunakan password dibawah ini :\n".$new_pass_rand."\n\nHarap ganti password anda kembali setelah login.";
                        }else{
                            $reply = "*Reset Password Gagal*\nSilahkan coba beberapa saat lagi.";
                        }
                    }else{
                        $reply = "No WA ini tidak terdaftar dalam akun TEXA.\nSilahkan gunakan No WA yang anda daftarkan di TEXA.";
                    }
                }else{
                    $reply = "Keyword tidak valid";
                }
            }else{
                $reply = "*Sumber tidak diketahui*";
            }
        }else{
            $reply = "*No HP harus berisi angka*";
        }
        $res = array("reply" => $reply);
        
        echo json_encode($res);
        die();
    }      
}