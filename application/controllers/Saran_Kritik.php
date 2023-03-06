<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Saran_Kritik extends MY_Controller
{
    public function submit_saran_kritik()
    {
        $this->form_validation->set_rules("saran_kritik","Saran & Kritik","required|xss_clean|trim");
        if($this->form_validation->run() === TRUE){
            $data = [
                'date_submit' => date("Y-m-d H:i:s"),
                'id_pengirim' => $this->id_user,
                'dari' => $this->name,
                'saran_kritik' => htmlentities($this->input->post("saran_kritik")),
                'status' => 'OPEN',
            ];
            $action = $this->model->insert("saran_kritik",$data);
            if(!$action){
                $this->swal("Sukses","Saran & Kritik Berhasil Di Kirim, Terimakasih atas support anda untuk kami.","success");
            }else{
                $this->swal("Gagal","Saran & Kritik Gagal Di Kirim, Terimakasih atas support anda untuk kami.","error");
            }
        }else{
            $this->swal("Peringatan",str_replace("\n","",validation_errors()),"warning");
        }
        redirect("saran_kritik");
    }
}
