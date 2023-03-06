<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
<script>
    function total_bayar() {
        const harga = $("#id_server").find(":selected").attr("data-harga");
        const berlangganan = $("#berlangganan").find(":selected").val();
        const total_bayar = formatharga(parseInt(harga)*parseInt(berlangganan),'');
        console.log(total_bayar);
        $("#total_bayar").html(total_bayar);
    }

    $("#id_server, #berlangganan").change(function() {
        total_bayar();
    });
    
    function get_paket() {
        var id_server = $("#id_server").val();
        if(id_server){
            $.ajax({
                type:'post',
                url:'<?= base_url("get_paket_pppoe") ?>',
                data:{
                    id_server:id_server,
					<?= "'".$this->name_token."':'".$this->token."'"; ?>
                },
				timeout:0,
                beforeSend:function(){
                    $("#paket").html('<option>Sedang Memuat...</option>');
                },
                success:function(r){
                    $("#paket").html(r);
                },
                error:function(a,b,c){
                    swal.fire("Error","Tidak dapat terhubung ke server mikrotik","error");
                }
            })
        }else{
            console.log("ID Server  Kosong");
        }
    }
    get_paket();

    var loading = '<i class="fas fa-spinner fa-pulse text-info m-2"></i>';
    var checked = '<i class="fas fa-check-circle text-success m-2"></i>';
    var failed = '<i class="fas fa-times-circle text-danger m-2"></i>';
    function check_username() {
        const username = $("#username").val();
        const id_server = $("#id_server").val();
        $.ajax({
            type:'post',
            url:'<?= base_url("check_username_pppoe") ?>',
            data:{
                id_server:id_server,
                username:username,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                $("#check-username").html(loading);
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                if(d.status == "ok"){
                    $("#check-username").html(checked);
                }else{
                    if(d.icon){
                        if(username){
                            swal.fire(d.title,d.pesan,d.icon);
                        }
                        $("#check-username").html("");
                    }else{
                        $("#check-username").html(failed);
                    }
                }
                console.log(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }

    $("#paket, #berlangganan, #id_server").change(function() {
        total_bayar();
    });
    function total_bayar() {
        const harga = $("#paket").find(":selected").attr("data-h");
        const berlangganan = $("#berlangganan").val();
        const total_bayar = parseInt(harga * berlangganan);
        console.log(harga+","+berlangganan+'='+total_bayar);
        if(!harga || !berlangganan){
            $("#total_bayar").html("-");
        }else{
            $("#total_bayar").html(formatharga(total_bayar,''));
        }
    }

    $("#reset").click(function(){
        $(".form-clear").val("");
        $("#status_debit").attr("checked",true);
    })

    function simpan_order() {
        var id_server = $("#id_server").val();
        var paket = $("#paket").val();
        var username = $("#username").val();
        var password = $("#password").val();
        var berlangganan = $("#berlangganan").val();
        if($("#status_debit").prop("checked")){
            var status_debit = "1";
        }else{
            var status_debit = "0";
        }

        $.ajax({
            type:"post",
            url:"<?= base_url("simpan_order_pppoe") ?>",
            data:{
                id_server:id_server,
                paket:paket,
                username:username,
                password:password,
                berlangganan:berlangganan,
                status_debit:status_debit,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                Swal.fire({
                    title:"Loading...",
                    html: "Sedang membuat PPPoE anda",
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                    },
                    allowOutsideClick: false
                })
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                swal.fire({
                    title:d.title,
                    html:d.pesan,
                    icon:d.icon,
                });

                if(d.icon == "success"){
                    $(".form-clear").val("");
                    $("#status_debit").attr("checked",true);
                    $("#total_bayar").html("");
                    $("#check-username").html("");
                }

                console.log(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }
</script>
