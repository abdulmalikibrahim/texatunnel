<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Auth extends MY_Controller
{
    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->load->library('form_validation');
    // }
	// public function swal($title, $text, $icon)
	// {
	// 	$this->session->set_flashdata("swal", '
	// 	<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
	// 	<script>
	// 		var text = "' . $text . '";
	// 		swal.fire({title:"' . $title . '",html:text,icon:"' . $icon . '"});
	// 	</script>');
	// }
    public function index()
    {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['nzm'] = 'Login Page';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            // validasinya success
            $this->_login();
        }
    }

    public function admin()
    {
        redirect('admin');
    }


    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');

        $user = $this->db->get_where('user', ['email' => $email])->row_array();

        // jika usernya ada
        if ($user) {
            //jika usernya aktif
            if ($user['is_active'] == 1) {
                // cek password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'id_user' => $user['id'],
                        'id_mitra' => $user['id_mitra'],
                        'email' => $user['email'],
                        'role_id' => $user['role_id'],
                        'name' => $user['name'],
                        'phone_number' => $user['phone_number'],
                        'image_user' => base_url('assets/img/profile/'.$user['image']),
						'expired_date' => $user['expired_data'],
                    ];
                    $this->session->set_userdata($data);
                    $last_login = [
                        "last_login" => date("Y-m-d H:i:s"),
                    ];
                    $this->model->update("user","id = '".$user['id']."'",$last_login);
                    redirect('admin');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">passwordnya salah sobat</div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akun belum di aktivasi<br>Silahkan click button Whatsapp dibawah ini untuk proses aktivasi<br><br><a href="https://wa.me/+6287708763253?text=Halo%20TEXA%2C%20aktifkan%20akun%20saya%20sekarang" class="btn btn-sm btn-success"><i class="fab fa-whatsapp mr-2"></i>0877-0876-3253</a></div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Akun Belum Terdaftar</div>');
            redirect('auth');
        }
    }

    public function registration()
    {
        $id_mitra = $this->input->get("reg");
        if(!empty($id_mitra)){
            $this->form_validation->set_rules('name', 'Name', 'required|xss_clean|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email|is_unique[user.email]', [
                'is_unique' => 'Maaf.... email sudah terdaftar sobat'
            ]);
            $this->form_validation->set_rules('phone_number', 'No Telepon / WA', 'required|xss_clean|trim|integer');
            $this->form_validation->set_rules('password1', 'Password', 'required|xss_clean|trim|min_length[3]|matches[password2]', [
                'matches' => 'Maaf...password tidak sama',
                'min_length' => 'password min 3 huruf/angka ya sobat...'
            ]);
            $this->form_validation->set_rules('password2', 'Password', 'required|xss_clean|trim|matches[password1]');
        }else{
            $this->form_validation->set_rules('name', 'Name', 'required|xss_clean|trim');
            $this->form_validation->set_rules('alamat', 'Alamat', 'required|xss_clean|trim');
            $this->form_validation->set_rules('provinsi', 'Provinsi', 'required|xss_clean|trim');
            $this->form_validation->set_rules('kabupaten', 'Kabupaten', 'required|xss_clean|trim');
            $this->form_validation->set_rules('kecamatan', 'Kecamatan', 'required|xss_clean|trim');
            $this->form_validation->set_rules('kelurahan', 'Kelurahan', 'required|xss_clean|trim');
            $this->form_validation->set_rules('phone_number', 'No Telepon / WA', 'required|xss_clean|trim|integer');
            $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim|valid_email|is_unique[user.email]', [
                'is_unique' => 'Maaf.... email sudah terdaftar sobat'
            ]);
            $this->form_validation->set_rules('nama_mitra', 'Nama Mitra', 'required|xss_clean|trim|is_unique[user.nama_mitra]', [
                'is_unique' => 'Maaf.... nama mitra sudah terdaftar sobat'
            ]);
            $this->form_validation->set_rules('password1', 'Password', 'required|xss_clean|trim|min_length[3]|matches[password2]', [
                'matches' => 'Maaf...password tidak sama',
                'min_length' => 'password min 3 huruf/angka ya sobat...'
            ]);
            $this->form_validation->set_rules('password2', 'Password', 'required|xss_clean|trim|matches[password1]');
            $this->form_validation->set_rules('vpn_remote', 'Jasa VPN Remote', 'required|xss_clean|trim');
            $this->form_validation->set_rules('pppoe', 'Jasa PPPoE', 'required|xss_clean|trim');
            $this->form_validation->set_rules('hotspot', 'Jasa Hotspot', 'required|xss_clean|trim');
        }

        if ($this->form_validation->run() == false) {
            if(isset($_POST["submit"])){
                $this->session->set_flashdata('message', '<div class="alert alert-warning" role="alert">'.validation_errors().'</div>');
                redirect('auth/registration?reg='.$id_mitra);
            }else{
                $data['nzm'] = 'TEXA Registration';
                $this->load->view('templates/auth_header', $data);
                $this->load->view('auth/registration');
                $this->load->view('templates/auth_footer');
            }
        } else {
            if(!empty($id_mitra)){
                $id_mitra = d_nzm($id_mitra);
                $data = [
                    'name' => htmlspecialchars($this->input->post('name', true)),
                    'email' => htmlspecialchars($this->input->post('email', true)),
                    'id_mitra' => $id_mitra,
                    'image' => 'default.jpg',
                    'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                    'role_id' => 2,
                    'is_active' => 0,
                    'date_created' => time()
                ];
            }else{
                if(!empty($this->input->post("kode_referal"))){
                    $saldo_awal = ($this->harga_server*3)+$this->harga_server;
                    $using_referal_code = $this->input->post("kode_referal");
                }else{
                    $saldo_awal = $this->harga_server*3;
                    $using_referal_code = NULL;
                }
                $data = [
                    'name' => htmlspecialchars($this->input->post('name', true)),
                    'email' => htmlspecialchars($this->input->post('email', true)),
                    'alamat' => htmlspecialchars($this->input->post('alamat', true)),
                    'provinsi' => htmlspecialchars($this->input->post('provinsi', true)),
                    'kabupaten' => htmlspecialchars($this->input->post('kabupaten', true)),
                    'kecamatan' => htmlspecialchars($this->input->post('kecamatan', true)),
                    'kelurahan' => htmlspecialchars($this->input->post('kelurahan', true)),
                    'id_mitra' => 0,
                    'nama_mitra' => htmlspecialchars($this->input->post('nama_mitra', true)),
                    'phone_number' => htmlspecialchars($this->input->post('phone_number', true)),
                    'image' => 'default.jpg',
                    'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                    'role_id' => 1,
                    'saldo' => $saldo_awal,
                    'is_active' => 0,
                    'date_created' => time(),
                    'vpn_remote' => htmlspecialchars($this->input->post('vpn_remote', true)),
                    'pppoe' => htmlspecialchars($this->input->post('pppoe', true)),
                    'hotspot' => htmlspecialchars($this->input->post('hotspot', true)),
                    'your_referal_code' => date("ymdhis"),
                    'using_referal_code' => $using_referal_code,
                ];
            }

            $proses = $this->db->insert('user', $data);
            if($proses){
                if(empty($id_mitra)){
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"><b>Proses registrasi berhasil.</b><br>Silahkan click button Whatsapp dibawah ini untuk proses aktivasi<br><br><a href="https://wa.me/+6287708763253?text=Halo%20TEXA%2C%20aktifkan%20akun%20saya%20sekarang" class="btn btn-sm btn-success"><i class="fab fa-whatsapp mr-2"></i>Active Now</a></div>');
                }else{
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert"><b>Proses registrasi berhasil.</b><br>Silahkan click button Whatsapp dibawah ini untuk proses aktivasi<br><br><a href="https://wa.me/+6287708763253?text=Aktifkan%20akun%20saya%20sekarang" class="btn btn-sm btn-success"><i class="fab fa-whatsapp mr-2"></i>Active Now</a></div>');
                }
                // $to = $this->input->post('email', true);
                // $subject = "Activation Account TEXA ".$this->input->post('name', true);
                // $message = '
                // Terimakasih anda telah mendaftar di TEXA,<br>
                // Mohon untuk bisa klik link di bawah ini agar akun anda segera di aktifkan<br><br>
                // Link Aktivasi : '.base_url("activation/".e_nzm($this->db->insert_id())).'<br><br>
                // Mohon abaikan pesan ini jika akun anda sudah di aktivasi.<br>
                // Terimakasih dan semoga hari anda menyenangkan.';

                // $send_email = $this->sendemail($to,$subject,$message);
                // if ($send_email == 200) {
                //     $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Proses regitrasi selesai, Mohon untuk check email anda dan klik link aktivasi nya.</div>');
                // }else{
                //     $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">'.show_error($this->email->print_debugger()).'<br><br>Mohon hubungi admin untuk proses aktivasi, klik tombol di bawan ini<br><br><a href="https://wa.me/+6287708763253?text=Dear%20Admin%2C%0ASaya%20telah%20melakukan%20registrasi%20namun%20pengiriman%20link%20aktivasi%20gagal%0AMohon%20untuk%20bisa%20di%20bantu%20proses%20aktivasi%20atas%20%3A%0ANama%20%3A%20'.urlencode($this->input->post('name', true)).'%0AEmail%20%3A%20'.urlencode($this->input->post('email', true)).'%0A%0ATerimakasih" class="btn btn-sm btn-success"><i class="fab fa-whatsapp mr-2"></i>0852-9018-9491</a></div>');
                // }
            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Proses regitrasi gagal, Mohon dicoba kembali</div>');
            }
            redirect('auth');
        }
    }

    public function activation($p)
    {
        $id_user = d_nzm($p);
        $data = [
            "is_active" => 1,
        ];
        $update = $this->model->update("user","id = '$id_user'",$data);
        if(!$update){
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Proses aktivasi selesai, Sekarang anda bisa melakukan login.</div>');
        }else{
            $user = $this->model->gd("user","*","id = '$id_user'","row");
            if(!empty($user->id)){
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Proses aktivasi gagal, Mohon hubungi admin untuk proses aktivasi, klik tombol di bawah ini<br><br><a href="https://wa.me/+6285290189491?text=Dear%20Admin%2C%0ASaya%20telah%20melakukan%20registrasi%20namun%20pengiriman%20link%20aktivasi%20gagal.%0AMohon%20untuk%20bisa%20di%20bantu%20proses%20aktivasi%20atas%20%3A%0ANama%20%3A%20'.urlencode($user->name).'%0AEmail%20%3A%20'.urlencode($user->email).'%0A%0ATerimakasih" class="btn btn-sm btn-success"><i class="fab fa-whatsapp mr-2"></i>0852-9018-9491</a>.</div>');
            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Proses aktivasi gagal, Mohon hubungi admin untuk proses aktivasi, klik tombol di bawah ini<br><br><a href="https://wa.me/+6285290189491" class="btn btn-sm btn-success"><i class="fab fa-whatsapp mr-2"></i>0852-9018-9491</a>.</div>');
            }
        }
        redirect('auth');
    }

    public function logout()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        if(!empty($this->id_user)){
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Sobat sudah Berhasil Keluar</div>');
        }else{
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Sesi login terakhir anda telah berakhir</div>');
        }
        redirect('auth');
    }

    public function forget_password()
    {
        $data['nzm'] = 'Forget Password';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/forget_password');
        $this->load->view('templates/auth_footer');
    }

    public function fpass()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|xss_clean');
        if($this->form_validation->run() === TRUE){
            $email = $this->input->post("email");
            $check_email = $this->model->gd("user","id","email = '$email'","row");
            if(!empty($check_email->id)){
                $new_pass1 = rand(111111,999999);
                $new_pass = password_hash($new_pass1,PASSWORD_BCRYPT);
                $data_password = [
                    "password" => $new_pass,
                ];
                $action = $this->model->update("user","id = '".$check_email->id."'",$data_password);
                if(!$action){
                    $this->load->config('email');
                    $this->load->library('email');
                    
                    $from = $this->config->item('smtp_user');
                    $to = $email;
                    $subject = "Forget Password ".$this->server;
                    $message = '
                    <h2 style="margin-bottom:0px;">'.$this->server.'</h2>,<br>
                    Berikut adalah password baru anda<br><br>
                    <div style="width:100%;" align="center"><h1 style="margin-bottom:0px;">'.$new_pass1.'</h1></div><br><br>
                    Silahkan login menggunakan password di atas, setelah itu mohon langsung mengganti password anda, supaya lebih mudah dalam di ingat.<br>
                    Terimakasih dan semoga hari anda menyenangkan.';

                    $this->email->set_newline("\r\n");
                    $this->email->from($from);
                    $this->email->to($to);
                    $this->email->subject($subject);
                    $this->email->message($message);

                    if ($this->email->send()) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Password baru anda sudah kami kirim melalui email, silahkan check email anda.</div>');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal mengirimkan email untuk password baru anda.<br><br>Mohon untuk bisa hubungi admin, klik button di bawah ini<br><a href="https://wa.me/+6285290189491" class="btn btn-sm btn-success"><i class="fab fa-whatsapp mr-2"></i>0852-9018-9491</a></div>');
                    }
                }else{
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Gagal membuat password baru, cobalah beberapa saat lagi </div>');
                }
            }else{
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">Alamat email tidak terdaftar</div>');
            }
            redirect('auth');
        }
    }
}
