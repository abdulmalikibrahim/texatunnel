<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Routeros extends MY_Controller
{
    public function save($p)
    {
        $this->form_validation->set_rules("country","Country","required|xss_clean|trim");
        $this->form_validation->set_rules("nama_server","Nama Server","required|xss_clean|trim");
        $this->form_validation->set_rules("ip_address","IP Address","required|xss_clean|trim");
        $this->form_validation->set_rules("username","Username","required|xss_clean|trim");
        if($this->form_validation->run() === TRUE){
            $check_saldo = $this->model->gd("user","saldo","id = '".$this->id_user."'","row");
            if(!empty($check_saldo->saldo)){
                if($check_saldo->saldo >= $this->harga_server){
                    $check_ip_port = $this->model->gd("api_routeros","id","ip_address = '".$this->input->post("ip_address")."' AND port = '".$this->input->post("port")."'","row");
                    if(empty($check_ip_port)){
                        if(empty($this->input->post("is_active"))){
                            $is_active = "0";
                        }else{
                            $is_active = "1";
                        }
                        $data = [
                            'id_server' => date("ymdhis"),
                            'id_mitra' => $this->id_mitra,
                            'nama_server' => $this->input->post("nama_server"),
                            'ip_address' => $this->input->post("ip_address"),
                            'port' => $this->input->post("port"),
                            'username' => e_nzm($this->input->post("username")),
                            'password' => e_nzm($this->input->post("password")),
                            'country' => $this->input->post("country"),
                            'is_active' => $is_active,
                        ];
                        if(!empty($p)){
                            $action = $this->model->update("api_routeros","id_server = '".$data["id_server"]."'",$data);
                            if(!$action){
                                $this->swal("Sukses","Server Berhasil Di Rubah","success");
                            }else{
                                $this->swal("Gagal","Server Gagal Di Rubah","error");
                            }
                            $redirect = "routeros_list";
                        }else{
                            $check_id = $this->model->gd("api_routeros","id","id_mitra = '".$this->id_mitrar."' AND id_server = '".$data["id_server"]."'","row");
							if(empty($check_id->id)){
								$action = $this->model->insert("api_routeros",$data);
								if(!$action){
									$this->swal("Sukses","Server Berhasil Di Tambah","success");
								}else{
									$this->swal("Gagal","Server Gagal Di Tambah","error");
								}
							}else{
								$this->swal("Gagal","ID Server tidak boleh sama","error");
							}
                        }
                    }else{
                        $this->swal("Gagal","IP Address dan port anda sudah ada.","error");
                    }
                    $redirect = "routeros/0";
                }else{
                    $this->swal("Gagal","Saldo anda tidak mencukupi, mohon isi saldo terlebih dahulu minimal Rp.".number_format($this->harga_server,0,"","."),"error");
                    $redirect = "routeros/0";
                }
            }else{
                $this->swal("Gagal","Saldo anda tidak mencukupi, mohon isi saldo terlebih dahulu minimal Rp.".number_format($this->harga_server,0,"","."),"error");
                $redirect = "routeros/0";
            }
        }else{
            $this->swal("Peringatan",str_replace("\n","",validation_errors()),"warning");
            $redirect = "routeros/".$this->p2;
        }
        redirect($redirect);
    }

    public function test($p)
    {
        $data = [
            "id" => d_nzm($p),
        ];
        $koneksi_router = $this->router_process("koneksi",$data);
        if($koneksi_router == "200"){
            $status = 200;
        }else{
            $status = 500;
        }
        echo $status;
        die();
    }

    public function checking_routeros()
    {
        $routeros = $this->model->gd("api_routeros","id,expired_date","expired_date < '".date("Y-m-d")."' AND is_active = '1'","result");
        if(!empty($routeros)){
            $data_id = '';
            foreach ($routeros as $routeros) {
                $data_id .= $routeros->id.",";
            }
            if(!empty($data_id)){
                $update_data = [
                    "is_active" => "0"
                ];
                $data_id = substr_replace($data_id,"",-1);
                $update = $this->model->update("api_routeros","id IN(".$data_id.")",$update_data);
                if(!$update){
                    echo "Sukses";
                }else{
                    echo "Gagal";
                }
            }
        }
    }
}
