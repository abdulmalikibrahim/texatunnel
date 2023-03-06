<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block" style="position:relative;" align="center">
                    <?php
                    if(empty($this->input->get("reg"))){
                        ?>
                        <img src="<?= base_url("assets/img/login.jpg"); ?>" width="75%" style="position:absolute; top: 30px; right: 50px; z-index:10;"/>
                        <?php
                    }
                    ?>
                    <div class="d-flex">
                        <img src="https://static.vecteezy.com/system/resources/previews/004/491/283/original/people-in-free-internet-zone-using-mobile-gadgets-tablet-pc-and-smartphone-big-wifi-sign-free-wifi-hotspot-wifi-bar-public-assess-zone-portable-device-concept-illustration-vector.jpg" width="100%" style="position:absolute; bottom:0;"/>
                    </div>
                </div>
                <?php
                if(empty($this->input->get("reg"))){
                    ?>
                    <div class="col-lg-7">
                        <div class="pt-5 pl-lg-5 pr-lg-5 pr-2 pl-2 pb-2">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Form Registrasi Mitra</h1>
                                <?= $this->session->flashdata('message'); ?>
                            </div>
                            <form class="user" id="form_registrasi" method="post" action="<?= base_url('auth/registration'); ?>">
								<?= $this->csrf; ?>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap" value="<?= set_value('name'); ?>" required>
                                    <?= form_error('name', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="nama_mitra" name="nama_mitra" placeholder="Nama Mitra, Contoh : TEXA TUNNEL" value="<?= set_value('nama_mitra'); ?>" required>
                                    <?= form_error('nama_mitra', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Alamat Mitra" value="<?= set_value('alamat'); ?>" required>
                                    <?= form_error('alamat', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="input-group">
                                    <select class="form-control mb-3" id="provinsi" name="provinsi" value="<?= set_value('provinsi'); ?>" required></select>
                                    <?= form_error('provinsi', '<small class="text-danger pl-3">', '</small>'); ?>
                                    <select class="form-control mb-3" id="kabupaten" name="kabupaten" value="<?= set_value('kabupaten'); ?>" required></select>
                                    <?= form_error('kabupaten', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="input-group">
                                    <select class="form-control mb-3" id="kecamatan" name="kecamatan" value="<?= set_value('kecamatan'); ?>" required></select>
                                    <?= form_error('kecamatan', '<small class="text-danger pl-3">', '</small>'); ?>
                                    <select class="form-control mb-3" id="kelurahan" name="kelurahan" value="<?= set_value('kelurahan'); ?>" required></select>
                                    <?= form_error('kelurahan', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Alamat Email" value="<?= set_value('email'); ?>" required>
                                    <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="form-group">
                                    <input type="number" class="form-control" id="phone_number" name="phone_number" placeholder="Nomor WA, Contoh : 087708770877" value="<?= set_value('phone_number'); ?>" required>
                                    <?= form_error('phone_number', '<small class="text-danger pl-3">', '</small>'); ?>
                                </div>
                                <div class="input-group mb-3">
                                    <input type="password" class="form-control" id="password1" name="password1" placeholder="Password" required>
                                    <?= form_error('password1', '<small class="text-danger pl-3">', '</small>'); ?>
                                    <input type="password" class="form-control" id="password2" name="password2" placeholder="Repeat Password" required>
                                </div>
                                <div class="mb-2 bg-info w-100 font-weight-bold text-center text-light rounded">Jasa Kamu</div>
                                <div class="input-group mb-3">
                                    <select class="form-control" id="vpn_remote" name="vpn_remote" required>
                                        <option value="" disabled selected>VPN Remote</option>
                                        <option value="YES">YES</option>
                                        <option value="NO">NO</option>
                                    </select>
                                    <select class="form-control" id="pppoe" name="pppoe" required>
                                        <option value="" disabled selected>PPPoE</option>
                                        <option value="YES">YES</option>
                                        <option value="NO">NO</option>
                                    </select>
                                    <select class="form-control" id="hotspot" name="hotspot" required>
                                        <option value="" disabled selected>Hotspot</option>
                                        <option value="YES">YES</option>
                                        <option value="NO">NO</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="kode_referal" name="kode_referal" placeholder="Kode Referal (Optional)">
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                            </form>
                            <div class="text-center mt-3">
                                <a class="small" href="<?= base_url("forget_password"); ?>">Lupa Password?</a>
                            </div>
                            <div class="text-center">
                                <a class="small" href="<?= base_url('auth'); ?>">Sudah Mempunyai akun? Login!</a>
                            </div>
                        </div>
                    </div>
                    <?php
                }else{
                    $id_mitra = $this->input->get("reg");
                    $mitra = $this->model->gd("user","nama_mitra","id = '".d_nzm($id_mitra)."'","row");
                    if(!empty($mitra->nama_mitra)){
                        ?>
                        <div class="col-lg-7">
                            <div class="pt-5 pl-lg-5 pr-lg-5 pr-2 pl-2 pb-2">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">FORM REGISTRASI<br><?= strtoupper($mitra->nama_mitra); ?></h1>
                                    <?= $this->session->flashdata('message'); ?>
                                </div>
                                <form class="user" id="form_registrasi" method="post" action="<?= base_url('auth/registration?reg='.$id_mitra); ?>">
									<?= $this->csrf; ?>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Lengkap" value="<?= set_value('name'); ?>">
                                        <?= form_error('name', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" id="email" name="email" placeholder="Alamat Email" value="<?= set_value('email'); ?>">
                                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" class="form-control" id="phone_number" name="phone_number" placeholder="Nomor WA, Contoh : 087708770877" value="<?= set_value('phone_number'); ?>">
                                        <?= form_error('phone_number', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-6">
                                            <input type="password" class="form-control" id="password1" name="password1" placeholder="Password">
                                            <?= form_error('password1', '<small class="text-danger pl-3">', '</small>'); ?>
                                        </div>
                                        <div class="col-6">
                                            <input type="password" class="form-control" id="password2" name="password2" placeholder="Repeat Password">
                                        </div>
                                    </div>
                                    <button type="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                        Register Account
                                    </button>
                                </form>
                                <div class="text-center mt-3">
                                    <a class="small" href="<?= base_url("forget_password"); ?>">Lupa Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth'); ?>">Sudah Mempunyai akun? Login!</a>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>
