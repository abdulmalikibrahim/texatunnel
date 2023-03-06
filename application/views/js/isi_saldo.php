<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
<?php
$check_pg = $this->model->gd("payment_gateway","*","is_active = '1'","row");
if(!empty($check_pg)){
    if($check_pg->id == "MD"){
        if($check_pg->status == "Sandbox"){
            $url = 'https://app.sandbox.midtrans.com/snap/snap.js';
            $client_key = $check_pg->client_key_sand;
        }else{
            $url = 'https://app.midtrans.com/snap/snap.js';
            $client_key = $check_pg->client_key_prod;
        }
        echo '<script type="text/javascript" src="'.$url.'" data-client-key="'.$client_key.'"></script>';
        ?>
        <script>
            $('#btn-bayar').click(function (event) {
                if($("#CheckTerm").prop("checked") == true){
                    event.preventDefault();
                    $(this).attr("disabled", "disabled");
                    
                    $.ajax({
                        type:"get",
                        url: '<?=base_url("snap_md/token")?>',
                        data:{
                            total: $("#saldo").val(),
                            biaya_penanganan: <?= $check_pg->biaya_penanganan; ?>
							<?= "'".$this->name_token."':'".$this->token."'"; ?>
                        },
						timeout:0,
                        cache: false,

                        success: function(data) {
                            //location = data;

                            console.log('token = '+data);
                            
                            var resultType = document.getElementById('result-type');
                            var resultData = document.getElementById('result-data');

                            function changeResult(type,data){
                            $("#result-type").val(type);
                            $("#result-data").val(JSON.stringify(data));
                            //resultType.innerHTML = type;
                            //resultData.innerHTML = JSON.stringify(data);
                            }

                            snap.pay(data, {
                            
                            onSuccess: function(result){
                                changeResult('success', result);
                                console.log(result.status_message);
                                console.log(result);
                                $("#payment-form").submit();
                            },
                            onPending: function(result){
                                changeResult('pending', result);
                                console.log(result.status_message);
                                $("#payment-form").submit();
                            },
                            onError: function(result){
                                changeResult('error', result);
                                console.log(result.status_message);
                                $("#payment-form").submit();
                            }
                            });
                        }
                    });
                }else{
                    swal.fire("Warning","Mohon ceklis persetujuan ketentuan top up terlebih dahulu","warning");
                }
            });
        </script>
        <?php
    }
}else{
    ?>
    <script>
        $("#info-pembayaran").hide();
        $("#jenis_pembayaran").change(function(){
            $("#info-pembayaran").show();
            $("#data-nomor-tujuan").html($(this).find(":selected").attr("data-nomor-tujuan"));
            $("#nomor_tujuan").val($(this).find(":selected").attr("data-nomor-tujuan"));
            $("#data-an").html($(this).find(":selected").attr("data-an"));
            $("#penerima").val($(this).find(":selected").attr("data-an"));
        });
    </script>
    <?php
}
?>
