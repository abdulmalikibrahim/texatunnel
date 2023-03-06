<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/fh-3.2.4/r-2.3.0/datatables.min.js"></script>
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
            $('.btn-bayar').click(function (event) {
                event.preventDefault();
                $(this).attr("disabled", "disabled");
                
                $.ajax({
                    type:"get",
                    url: '<?=base_url("snap_md/token")?>',
                    data:{
                        total: $(this).attr("data-total"),
                        biaya_penanganan: <?= $check_pg->biaya_penanganan; ?>,
                        order_id:$(this).attr("data-order"),
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
                                swal.fire("Error",result.status_message,"error");
                                $("#payment-form").submit();
                            }
                        });
                    },
                    error:function(a,b,c) {
                        swal.fire("Error","Terjadi kesalahan, Metode payment gateway Midtrans mengalami error,  silahkan buat pembelian baru.","error");
                    }
                });
            });
        </script>
        <?php
    }
}
?>
<script>
    $("#datatable").DataTable({
        responsive:true,
    });
</script>
