<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800"><?= $nzm ?></h1>
    <div class="row">
        <div class="col-xl-4 col-lg-6">
            <div class="card shadow mb-4 bg-gradient-primary text-light">
                <div class="card-body">
                    <div style="position:relative;height:18rem;width:100%;">
                        <center>
                            <br>
                            <div class="text-center">
                                <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 13rem;" src="<?= base_url("assets/img/saldo.png") ?>" alt="">
                            </div>
                            <h2 class="card-title">
                                <?php
                                $saldo_user = $this->model->gd("user", "saldo", "id = '" . $this->id_user . "'", "row");
                                ?>
                                <div class="text-lg font-weight-bold mb-1" id="getFunds">Rp <?= number_format($saldo_user->saldo, 0, "", ".") ?></div>
                            </h2>
                            <p class="text-xs mb-1">Total Saldo di Akun kamu</p>
                        </center>
                    </div>
                    <div class="w-100 text-right"><a href="<?= base_url("saldo/history_topup"); ?>" class="btn btn-sm btn-success">History Topup</a></div>
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <small>
                                <i class="fas fa-dot-circle fa-fw" style="font-size:10px;" aria-hidden="true"></i>
                                <b>Top Up Saldo diatas jam 12 malam, akan diproses 8/10 pagi</b>
                            </small>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <?php
                    $check_pg = $this->model->gd("payment_gateway", "*", "id_mitra = '".$this->id_mitra."' AND is_active = '1'", "row");
                    if (!empty($check_pg)) {
                        if ($check_pg->id == "MD") {
                            $url = base_url("snap_md/finish");
                            $tipe_pg = "MD";
                        } else {
                            $url = base_url("topup_saldo");
                            $tipe_pg = "";
                        }
                    } else {
                        $url = base_url("topup_saldo");
                        $tipe_pg = "";
                    }
                    ?>
                    <form id="fom-topup" action="<?= $url ?>" method="post">
						<?= $this->csrf; ?>
                        <div class="form-group">
                            <?php
                            if (empty($check_pg)) {
                            ?>
                                <label for="email">
                                    <i class="fas fa-credit-card fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Silahkan Pilih Jenis Pembayaran" aria-hidden="true"></i> Jenis Pembayaran :
                                </label>
                                <select name="jenis_pembayaran" id="jenis_pembayaran" class="custom-select input-sm">
                                    <option selected="selected" disabled="disable">Please Choose Payment</option>
                                    <?php
                                    $payment = $this->model->gd("payment_method", "*", "id_mitra = '".$this->id_mitra."' AND status = 'Aktif' ORDER BY payment", "result");
                                    if (!empty($payment)) {
                                        foreach ($payment as $payment) {
                                            echo '<option value="' . $payment->payment . '" data-nomor-tujuan="' . $payment->nomor_payment . '" data-an="' . $payment->an . '">' . $payment->payment . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                                <label class="mt-2" id="info-pembayaran">
                                    Silahkan lakukan pembayaran di :<br>
                                    <table>
                                        <tr>
                                            <td>Nomor Tujuan</td>
                                            <td class="pl-2 pr-2 text-center">:</td>
                                            <td class="font-weight-bold" id="data-nomor-tujuan"></td>
                                        </tr>
                                        <tr>
                                            <td>A/N</td>
                                            <td class="text-center">:</td>
                                            <td class="font-weight-bold" id="data-an"></td>
                                        </tr>
                                    </table>
                                </label>
                                <input type="hidden" class="form-control" name="nomor_tujuan" id="nomor_tujuan">
                                <input type="hidden" class="form-control" name="an" id="penerima">
                            <?php
                            } else {
                            ?>
                                <input type="hidden" class="form-control" name="an" id="penerima" value="MIDTRANS">
                                <input type="hidden" name="result_type" id="result-type" value="">
                                <input type="hidden" name="result_data" id="result-data" value="">
                            <?php
                            }
                            ?>
                        </div>
                        <div class="form-group">
                            <label for="saldo">
                                <i class="fas fa-wallet fa-fw" style="font-size:13px;" data-toggle="tooltip" data-original-title="Masukan saldo" aria-hidden="true"></i> Saldo : </label>
                            <input type="text" inputmode="numeric" class="form-control harga" name="saldo" id="saldo" placeholder="Masukan jumlah top up disini" required="">
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 mb-3 mb-sm-0">
                                <div class="custom-control custom-checkbox">
                                    <input name="confirmterm" type="checkbox" class="custom-control-input" id="CheckTerm" required>
                                    <label name="confirmterm" class="custom-control-label" for="CheckTerm"> Saya setuju dan telah baca ketentuan topup.</label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" id="api_token" name="api_token" value="14da1df1b09409845ef7259122f8276d">
                        <div class="col-xl-12 col-sm-12 col-md-6 mb-4" id="result" role="alert"></div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-sm btn-secondary" data-dismiss="modal">
                                <i class="fas fa-undo" style="font-size:11px" aria-hidden="true"></i> Reset </button>
                            <?php
                            if ($tipe_pg == "MD") {
                                echo '
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary" id="btn-bayar">
                                        <i class="fas fa-coins" style="font-size:15px;" aria-hidden="true"></i>
                                        <i class="fas fa-plus fa-fw" style="font-size:7px;" aria-hidden="true"></i> Pay Now
                                    </a>';
                            } else {
                                echo '
                                    <button class="btn btn-sm btn-primary" id="btn-bayar">
                                        <i class="fas fa-coins" style="font-size:15px;" aria-hidden="true"></i>
                                        <i class="fas fa-plus fa-fw" style="font-size:7px;" aria-hidden="true"></i> Beli 
                                    </button>';
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
