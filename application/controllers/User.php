<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{
    public function save_user($p)
    {
        $this->form_validation->set_rules("name","Nama","required|xss_clean|trim");
        $this->form_validation->set_rules("email","Email","valid_email|required|xss_clean|trim");
        $this->form_validation->set_rules("phone_number","Nomor WA","integer|required|xss_clean|trim");
        // $this->form_validation->set_rules("saldo","Saldo","required|xss_clean|trim");
        $this->form_validation->set_rules("is_active","Status","integer|xss_clean|trim");
        if($this->form_validation->run() === TRUE){
            if(!empty($this->input->post("is_active"))){
                $is_active = 1;
            }else{
                $is_active = 0;
            }
            $data = [
                "name" => htmlentities($this->input->post("name")),
                "email" => htmlentities($this->input->post("email")),
                "phone_number" => htmlentities($this->input->post("phone_number")),
                // "saldo" => htmlentities(str_replace(".","",$this->input->post("saldo"))),
                "is_active" => $is_active,
            ];
			if($p != "0"){
				$id_user = d_nzm($p);
				$action = $this->model->update("user","id = '$id_user'",$data);
				$pesan = "Rubah";
			}else{
				$data["date_created"] = time();
				$data["role_id"] = 2;
				$data["password"] = password_hash("Texa123",PASSWORD_DEFAULT);
				$data["image"] = "default.jpg";
				$data["id_mitra"] = $this->id_mitra;
				$action = $this->model->insert("user",$data);
				$pesan = "Tambah";
			}
            if(!$action){
                $this->swal("Sukses","User Berhasil Di ".$pesan,"success");
            }else{
                $this->swal("Gagal","User Gagal Di ".$pesan,"error");
            }
        }else{
            $this->swal("Warning",str_replace("\n","",validation_errors()),"warning");
        }
        redirect("user_list");
    }

	public function delete($p)
	{
		if(!empty($p)){
			$id = d_nzm($p);
			$data = [
				"deleted_date" => time(),
				"is_active" => "0",
			];
			$action = $this->model->update("user","id = '$id'",$data);
			if(!$action){
                $this->swal("Sukses","User Berhasil Di Hapus","success");
			}else{
                $this->swal("Gagal","User Gagal Di Hapus","error");
			}
		}else{
			$this->swal("Gagal","Parameter anda kosong","error");
		}
		redirect("user_list");
	}
}
