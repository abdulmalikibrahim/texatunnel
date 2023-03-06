<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="card">
		<div class="card-header">
			<div class="row">
				<div class="col-9"><h1 class="h3 m-0 text-gray-800"><?=$nzm?></h1></div>
				<div class="col-3 text-right"><a href="<?= base_url("user/add/0") ?>" class="btn btn-sm btn-info"><i class="fas fa-plus mr-2"></i>Tambah</a></div>
			</div>
		</div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered table-horvered w-100" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th class="p-3 text-center">No</th>
                                    <th class="p-3 text-center">Tanggal Buat</th>
                                    <th class="p-3 text-center">Nama</th>
                                    <th class="p-3 text-center">Email</th>
                                    <th class="p-3 text-center">Whatsapp</th>
                                    <!-- <th class="p-3 text-center">Saldo</th> -->
                                    <th class="p-3 text-center">Status</th>
                                    <!-- <th class="p-3 text-center">Login Terakhir</th> -->
                                    <th class="p-3 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $data = $this->model->gd("user","*","deleted_date IS NULL AND id_mitra = '".$this->id_mitra."' AND id != '' AND role_id != '1'","result");
                                $load = '';
                                if(!empty($data)){
                                    $no = 1;
                                    foreach ($data as $data) {
                                        if($data->is_active == "1"){
                                            $status = '<span class="badge badge-success">Aktif</span>';
                                        }else{
                                            $status = '<span class="badge badge-danger">Non Aktif</span>';
                                        }
                                        $load .= '
                                        <tr>
                                            <td class="text-center">'.$no.'</td>
                                            <td class="text-center">'.date("d-M-Y H:i:s",$data->date_created).'</td>
                                            <td class="text-center">'.$data->name.'</td>
                                            <td class="text-center">'.$data->email.'</td>
                                            <td class="text-center">'.$data->phone_number.'</td>
                                            <!--<td class="text-center">'.number_format($data->saldo,0,"",",").'</td>-->
                                            <td class="text-center">'.$status.'</td>
                                            <!--<td class="text-center">'.date("d-M-Y H:i:s",strtotime($data->last_login)).'</td>-->
                                            <td class="text-center">
                                                <a href="'.base_url("user/edit/".e_nzm($data->id)).'" class="btn btn-sm btn-info" title="Rubah"><i class="fas fa-pencil-alt m-1"></i></a>
                                                <a href="javascript:void(0)" data-id="'.e_nzm($data->id).'" class="btn btn-sm btn-danger btn-hapus" title="Hapus"><i class="fas fa-trash-alt m-1"></i></a>
                                            </td>
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
<!-- End of Main Content -->
