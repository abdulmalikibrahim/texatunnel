<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12 col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <div class="row">
                        <div class="col-8">
                            <h6 class="m-0 font-weight-bold">
                                <i class="fas fa-fw fa-history" style="font-size:15px;" aria-hidden="true"></i> Histori Topup
                            </h6>
                        </div>
                        <div class="col-4 text-right"><a href="<?= base_url("saldo/isi_saldo"); ?>" class="btn btn-sm btn-danger">Kembali</a></div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table dataTable no-footer" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%; font-size:10pt;">
                                        <thead class="thead-light">
                                            <tr role="row">
                                                <th class="text-center">No</th>
                                                <th class="text-center">Order ID</th>
                                                <th class="text-center">Jenis Pembayaran</th>
                                                <th class="text-center">Jumlah </th>
                                                <th class="text-center">Tanggal Topup</th>
                                                <th class="text-center">Nomor Tujuan</th>
                                                <th class="text-center">A/N</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if($this->role_id == "1"){
                                                $history_topup = $this->model->gd("top_up","*","id_mitra = '".$this->id_mitra."' AND id_user != ''","result");
                                            }else{
                                                $history_topup = $this->model->gd("top_up","*","id_mitra = '".$this->id_mitra."' AND id_user = '".$this->id_user."'","result");
                                            }
                                            $load = '';
                                            if(!empty($history_topup)){
                                                $no = 1;
                                                foreach ($history_topup as $ht) {
                                                    if($ht->status == "Pending"){
                                                        $badge = "warning";
                                                        $action_btn = '<a href="'.base_url("cancel_topup/".e_nzm($ht->id)).'" class="btn btn-sm btn-danger">Batal</a>';
                                                    }else if($ht->status == "Cancel"){
                                                        $badge = "danger";
                                                        $action_btn = '<a href="javascript:void(0)" class="btn btn-sm btn-secondary">Batal</a>';
                                                    }else if($ht->status == "Sukses"){
                                                        $badge = "success";
                                                        $action_btn = '<a href="javascript:void(0)" class="btn btn-sm btn-secondary">Batal</a>';
                                                    }else{
                                                        $badge = "danger";
                                                        $action_btn = '
                                                        <div style="min-width:125px;">
                                                            <a href="javascript:void(0)" class="btn btn-sm btn-success btn-bayar" data-total="'.$ht->nominal.'" data-order="'.$ht->order_id.'">Bayar</a>
                                                            <a href="'.base_url("cancel_topup/".e_nzm($ht->id)).'" class="btn btn-sm btn-danger">Batal</a>
                                                        </div>';
                                                    }
                                                    $load .= '
                                                    <tr>
                                                        <td class="text-center align-middle">'.$no.'</td>
                                                        <td class="text-center align-middle">'.$ht->order_id.'</td>
                                                        <td class="text-center align-middle">'.$ht->jenis_pembayaran.'</td>
                                                        <td class="text-center align-middle">'.number_format($ht->nominal,0,"",".").'</td>
                                                        <td class="text-center align-middle">'.date("d M Y H:i:s",strtotime($ht->tanggal)).'</td>
                                                        <td class="text-center align-middle">'.$ht->nomor_tujuan.'</td>
                                                        <td class="text-center align-middle">'.$ht->an.'</td>
                                                        <td class="text-center align-middle"><span class="badge badge-'.$badge.'">'.$ht->status.'</span></td>
                                                        <td class="text-center align-middle">'.$action_btn.'</td>
                                                    </tr>';
                                                    $no++;
                                                }
                                                echo $load;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>