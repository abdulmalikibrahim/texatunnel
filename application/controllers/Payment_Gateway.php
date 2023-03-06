<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Payment_Gateway extends MY_Controller
{
    public function save($p)
    {
        if(!empty($this->idusernzm)){
            if($p == "md"){
                $this->form_validation->set_rules("status","Status","required|trim|xss_clean");
                $this->form_validation->set_rules("biaya_penanganan","Biaya Penanganan","required|trim|xss_clean");
                $status = $this->input->post("status");
                if(!empty($status)){
                    if($status == "Sandbox"){
                        $this->form_validation->set_rules("id_merchant_sand","ID Merchant","required|trim|xss_clean");
                        $this->form_validation->set_rules("client_key_sand","Client Key","required|trim|xss_clean");
                        $this->form_validation->set_rules("server_key_sand","Server Key","required|trim|xss_clean");
                    }else{
                        $this->form_validation->set_rules("id_merchant_prod","ID Merchant","required|trim|xss_clean");
                        $this->form_validation->set_rules("client_key_prod","Client Key","required|trim|xss_clean");
                        $this->form_validation->set_rules("server_key_prod","Server Key","required|trim|xss_clean");
                    }
                }
                if($this->form_validation->run() === TRUE){
                    if(empty($this->input->post("is_active"))){
                        $is_active = "0";
                    }else{
                        $is_active = "1";
                    }
                    $data_input = [
                        "status" => $status,
                        "is_active" => $is_active,
                        "biaya_penanganan" => $this->input->post("biaya_penanganan"),
                    ];
                    if($status == "Sandbox"){
                        $data_input["id_merchant_sand"] = $this->input->post("id_merchant_sand");
                        $data_input["client_key_sand"] = $this->input->post("client_key_sand");
                        $data_input["server_key_sand"] = $this->input->post("server_key_sand");
                    }else{
                        $data_input["id_merchant_prod"] = $this->input->post("id_merchant_prod");
                        $data_input["client_key_prod"] = $this->input->post("client_key_prod");
                        $data_input["server_key_prod"] = $this->input->post("server_key_prod");
                    }
                    $check_md = $this->model->gd("payment_gateway","id","id_mitra = '".$this->id_mitra."' AND id = 'MD'","row");
                    if(!empty($check_md->id)){
                        $action = $this->model->update("payment_gateway","id_mitra = '".$this->id_mitra."' AND id = 'MD'",$data_input);
                        $pesan = "Rubah";
                    }else{
                        $data_input["id"] = "MD";
                        $data_input["id_mitra"] = $this->id_mitra;
                        $data_input["gateway"] = "Midtrans";
                        $action = $this->model->insert("payment_gateway",$data_input);
                        $pesan = "Tambah";
                    }
                    if(!$action){
                        $this->swal("Sukses","Data Berhasil Di ".$pesan,"success");
                    }else{
                        $this->swal("Gagal","Data Gagal Di ".$pesan,"error");
                    }
                }else{
                    $this->swal("Peringatan",str_replace("\n","",validation_errors()),"warning");
                }
                redirect("payment_gateway");
            }
        }else{
            redirect("");
        }
    }

    public function snap($pg,$action)
    {
        if(!empty($pg)){
            if($pg == "md"){
                $payment_gateway = $this->model->gd("payment_gateway","*","id_mitra = '".$this->id_mitra."' AND id = 'MD' AND is_active = '1'","row");
                if(!empty($payment_gateway)){
                    if($payment_gateway->status == "Sandbox"){
                        $production = false;
                        $server_key = $payment_gateway->server_key_sand;
                    }else{
                        $production = true;
                        $server_key = $payment_gateway->server_key_prod;
                    }
                    $params = array('server_key' => $server_key, 'production' => $production);
                    $this->load->library('midtrans');
                    $this->midtrans->config($params);

                    if($action == "token"){
                        if(!empty($this->input->get("order_id"))){
                            $order_id = $this->input->get("order_id");
                        }else{
                            $order_id = uniqid();
                        }
                        $total_biaya = $this->input->get('biaya_penanganan') + str_replace(".","",$this->input->get('total'));
                        // Required
                        $transaction_details = array(
                            'order_id' => $order_id,
                            'gross_amount' => $total_biaya, // no decimal allowed for creditcard
                        );
                
                        // Optional
                        $item_details[] = array(
                            'id' => 'topup_'.date("Ymd"),
                            'price' => str_replace(".","",$this->input->get('total')),
                            'quantity' => 1,
                            'name' => "Top Up Saldo"
                        );
                
                        if(!empty($this->input->get('biaya_penanganan'))){
                            // Optional
                            $item_details[] = array(
                                'id' => 'penanganan_topup_'.date("Ymd"),
                                'price' => $this->input->get('biaya_penanganan'),
                                'quantity' => 1,
                                'name' => "Biaya Penanganan"
                            );
                        }
                
                        // Optional
                        $billing_address = array(
                            'first_name'    => $this->name,
                            'last_name'     => "",
                            'address'       => $this->email,
                            'city'          => "",
                            'postal_code'   => "",
                            'phone'         => $this->phone_number,
                            'country_code'  => ''
                        );
                
                        // Optional
                        $shipping_address = array(
                            'first_name'    => $this->name,
                            'last_name'     => "",
                            'address'       => $this->email,
                            'city'          => "",
                            'postal_code'   => "",
                            'phone'         => $this->phone_number,
                            'country_code'  => ''
                        );
                
                        // Optional
                        $customer_details = array(
                            'first_name'    => $this->name,
                            'last_name'     => "",
                            'email'         => $this->email,
                            'phone'         => $this->phone_number,
                            'billing_address'  => $billing_address,
                            'shipping_address' => $shipping_address
                        );
                
                        // Fill transaction details
                        $transaction = array(
                            'transaction_details' => $transaction_details,
                            'customer_details' => $customer_details,
                            'item_details' => $item_details,
                        );
                        //error_log(json_encode($transaction));
                        $snapToken = $this->midtrans->getSnapToken($transaction);
                        error_log($snapToken);
                        echo $snapToken;
                        
                        if($snapToken){
                            $data = [
                                'tanggal' => date("Y-m-d H:i:s"),
                                'id_mitra' => $this->id_mitra,
                                'order_id' => $order_id,
                                'id_user' => $this->id_user,
                                'nominal' => htmlentities(str_replace(".","",$this->input->get('total'))),
                                "jenis_pembayaran" => 'MIDTRANS',
                                "an" => 'MIDTRANS',
                            ];
                            if(!empty($this->input->get("order_id"))){
                                $action = $this->model->update("top_up","order_id = '".$this->input->get("order_id")."'",$data);
                            }else{
                                $action = $this->model->insert("top_up",$data);
                            }
                        }else{
                            $this->swal("Gagal","Terjadi kesalahan, Metode payment gateway Midtrans mengalami error,  silahkan buat pembelian baru.","error");
                        }
                    }
                }else{
                    $this->swal("Gagal","Terjadi kesalahan, Metode payment gateway Midtrans tidak di aktifkan oleh admin.","error");
                }
            }
        }else{
            $this->swal("Gagal","Terjadi kesalahan pada payment gateway, silahkan coba beberapa saat lagi","error");
            redirect("saldo/isi_saldo");
        }
        die();
    }

    public function notification($pg)
    {
        if(!empty($pg)){
            if($pg == "md"){
                $payment_gateway = $this->model->gd("payment_gateway","*","id_mitra = '".$this->id_mitra."' AND id = 'MD' AND is_active = '1'","row");
                if(!empty($payment_gateway)){
                    if($payment_gateway->status == "Sandbox"){
                        $production = false;
                        $server_key = $payment_gateway->server_key_sand;
                    }else{
                        $production = true;
                        $server_key = $payment_gateway->server_key_prod;
                    }
                    $params = array('server_key' => $server_key, 'production' => $production);
                    $this->load->library('veritrans');
                    $this->veritrans->config($params);
                    
                    echo 'test notification handler';
                    $method = "production";
                    if($method == "production"){
                        $json_result = file_get_contents('php://input');
                        $result = json_decode($json_result);
                        $order_id = $result->order_id;
                        
                
                        if($result){
                            $notif = $this->veritrans->status($order_id);
                        }
                        print_r($notif);
                
                        error_log(print_r($result,TRUE));
                    }else{
                        $notif = $this->veritrans->status('63a3dc4b62f5c');
                        echo json_encode($notif);
                    }
            
                    //notification handler sample
            
                    $transaction = $notif->transaction_status;
                    $type = ucwords(str_replace("_"," ",$notif->payment_type));
                    if($type == "Echannel"){
                        $bank = "Mandiri";
                        $va_number = $notif->bill_key;
                    }else if($type == "Cstore"){
                        $bank = $notif->store;
                        $va_number = $notif->payment_code;
                    }else if($type == "Bank Transfer"){
                        if(!empty($notif->permata_va_number)){
                            $bank = "Permata";
                            $va_number = $notif->permata_va_number;
                        }else{
                            $bank = $notif->va_numbers[0]->bank;
                            $va_number = $notif->va_numbers[0]->va_number;
                        }
                    }else if($type == "Credit Card"){
                        $bank = $notif->bank;
                        $va_number = $notif->masked_card;
                    }else if($type == "Bca Klikpay"){
                        $bank = "";
                        $va_number = "";
                    }else if($type == "Akulaku"){
                        $bank = "";
                        $va_number = "";
                    }else{
                        $bank = "";
                        $va_number = "";
                    }
                    $transaction_time = $notif->transaction_time;
                    $order_id = $notif->order_id;
                    $fraud = $notif->fraud_status;
                    if(!empty($payment_gateway->biaya_penanganan)){
                        $gross_amount = $notif->gross_amount - $payment_gateway->biaya_penanganan;
                    }else{
                        $gross_amount = $notif->gross_amount;
                    }

                    $get_id_user = $this->model->gd("top_up","id_user","id_mitra = '".$this->id_mitra."' AND order_id = '".$order_id."'","row");
                    if(!empty($get_id_user->id_user)){
                        $id_user = $get_id_user->id_user;
                        $saldo_awal = $this->model->gd("user","saldo","id = '$id_user'","row");
                        if(!empty($saldo_awal)){
                            $saldo_awal = $saldo_awal->saldo;
                        }else{
                            $saldo_awal = 0;
                        }
                    }else{
                        $id_user = '0';
                        $saldo_awal = 0;
                    }
            
                    $data_update = [
                        "jenis_pembayaran" => $type." ".strtoupper($bank),
                        "nomor_tujuan" => $va_number,
                        "nominal" => $gross_amount,
                    ];
                    if ($transaction == 'capture') {
                        // For credit card transaction, we need to check whether transaction is challenge by FDS or not
                        if ($notif->payment_type == 'credit_card'){
                            echo "ok";
                            if($fraud == 'challenge'){
                                // TODO set payment status in merchant's database to 'Challenge by FDS'
                                // TODO merchant should decide whether this transaction is authorized or not in MAP
                                $data_update["status"] =  'Pending';
                                echo "Transaction order_id: " . $order_id ." is challenged by FDS";
                            } 
                            else {
                                // TODO set payment status in merchant's database to 'Success'
                                $data_update["status"] =  'Sukses';
                                $data_saldo["saldo"] = $saldo_awal + $gross_amount;
                                $update_saldo = $this->model->update("user","id = '$id_user'",$data_saldo);
                                $data_log = [
                                    "id_user" => $id_user,
                                    "id_mitra" => $this->id_mitra,
                                    "tanggal" => $transaction_time,
                                    "logs" => "Isi Saldo ".number_format($gross_amount,0,"","."),
                                    "category" => "Create",
                                ];
                                $logs = $this->model->insert("log_activity_user",$data_log);
                                echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
                            }
                        }
                    }
                    else if ($transaction == 'settlement'){
                        $data_update["status"] =  'Sukses';
                        $data_saldo["saldo"] = $saldo_awal + $gross_amount;
                        $update_saldo = $this->model->update("user","id = '$id_user'",$data_saldo);
                        
                        $data_log = [
                            "id_user" => $id_user,
                            "id_mitra" => $this->id_mitra,
                            "tanggal" => $transaction_time,
                            "logs" => "Isi Saldo ".number_format($gross_amount,0,"","."),
                            "category" => "Create",
                        ];
                        $logs = $this->model->insert("log_activity_user",$data_log);
                        // TODO set payment status in merchant's database to 'Settlement'
                        echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;
                    } 
                    else if($transaction == 'pending'){
                        $data_update["status"] =  'Pending';
                        // TODO set payment status in merchant's database to 'Pending'
                        echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
                    } 
                    else if ($transaction == 'deny') {
                        $data_update["status"] =  'Cancel';
                        // TODO set payment status in merchant's database to 'Denied'
                        echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
                    }
                    else if($transaction == "success"){
                        //Just for credit card method
                        $data_update["status"] =  'Sukses';
                        $data_saldo["saldo"] = $saldo_awal + $gross_amount;
                        $update_saldo = $this->model->update("user","id = '$id_user'",$data_saldo);
                        $data_log = [
                            "id_user" => $id_user,
                            "id_mitra" => $this->id_mitra,
                            "tanggal" => $transaction_time,
                            "logs" => "Isi Saldo ".number_format($gross_amount,0,"","."),
                            "category" => "Create",
                        ];
                        $logs = $this->model->insert("log_activity_user",$data_log);
                    }

                    $update_data = $this->model->update("top_up","id_mitra = '".$this->id_mitra."' AND order_id = '".$order_id."'",$data_update);
                }
            }
        }
    }
}