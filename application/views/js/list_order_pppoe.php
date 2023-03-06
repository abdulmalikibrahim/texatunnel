<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/fh-3.2.4/r-2.3.0/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
<script>
    function get_all() {
        $.ajax({
            type:"post",
            url:"<?= base_url("get_list_order_pppoe") ?>",
            data:{
                id_server:$("#id_server").val(),
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                $("#data-body").html("<tr><td colspan='12' class='text-center'><i>Sedang Memuat...</i></td></tr>");
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                if(d.status == "success"){
                    $("#data-body").html(d.data);
                    $("#datatable").DataTable({
                        responsive: true,
                    });
                }else{
                    swal.fire({
                        title:d.title,
                        html:d.pesan,
                        icon:d.icon,
                    });
                }
                console.log(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }
    get_all();

    $(document).on('click','.btn-edit',function() {
        $("#form_edit").modal("show");
        $("#action").attr("data-i",$(this).attr("data-i"));
        $("#action").attr("data-sche",$(this).attr("data-sche"));
        $("#name").val($(this).attr("data-username"));
        $("#password").val($(this).attr("data-password"));
    });

    $(document).on('click','.btn-delete',function() {
        const id_server = $("#id_server").val();
        const id_pppoe = $(this).attr("data-i");
        const id_sche = $(this).attr("data-sche");
        const username = $(this).attr("data-username");
        Swal.fire({
            title: 'Yakin hapus PPPoE '+username+'?',
            html:'Data akan di hapus secara permanen',
            showCancelButton: true,
            cancelButtonText:"Tidak",
            confirmButtonColor:"red",
            confirmButtonText: 'Ya, Hapus',
            icon:"question",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type:"POST",
                    url:"<?= base_url("hapus_order_pppoe") ?>",
                    data:{
                        id_server:id_server,
                        id_pppoe:id_pppoe,
                        id_sche:id_sche,
                        username:username,
						<?= "'".$this->name_token."':'".$this->token."'"; ?>
                    },
					timeout:0,
                    dataType:"JSON",
                    beforeSend:function(){
                        show_loading("Loading...","Sedang proses penghapusan");
                    },
                    success:function(r){
                        d = JSON.parse(JSON.stringify(r));
                        swal_alert(d.title,d.pesan,d.icon);
                        if(d.icon == "success"){
                            $("#row-"+id_pppoe).remove();
                        }
                    },
                    error:function(a,b,c){
                        swal_alert("Error",a.responseText,"error");
                    }
                })
            }
        });
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
                    swal.fire("Error",a.responseText,"error");
                }
            })
        }else{
            console.log("ID Server  Kosong");
        }
    }
    get_paket();

    $(document).on('click','.btn-active',function() {
        const id_server = $("#id_server").val();
        const id_pppoe = $(this).attr("data-i");
        const expired = $(this).attr("data-e");
        const status = $(this).attr("data-s");
        const now = <?= strtotime(date("Y-m-d")); ?>;
        $("#paket").val($(this).attr("data-paket"));
        $("#berlangganan").val($(this).attr("data-berlangganan"));
        if($(this).attr("data-debit") == "1"){
            $("#status_debit").attr("checked",true);
        }else{
            $("#status_debit").attr("checked",false);
        }
        if(status == "0"){
            update_status();
        }else{
            if(parseInt(expired) >= parseInt(now)){
                update_status();
            }else{
                Swal.fire({
                    title: 'Konfirmasi',
                    html:'Apakah anda ingin merubah paket atau langganan?',
                    showCancelButton: false,
                    showDenyButton:true,
                    denyButtonText:"Tidak",
                    denyButtonColor:"red",
                    confirmButtonText: 'Ya',
                    icon:"question",
                }).then((result) => {
                    /* Read more about isConfirmed, isDenied below */
                    if (result.isConfirmed) {
                        $("#form_edit_paket").modal("show");
                        check_saldo("current_saldo");
                        $("#paket").val("");
                        $("#berlangganan").val("");
                        $("#total_bayar").html("");
                        $("#status_debit").attr("checked",true);
                        $("#btn-save-paket").attr("data-i",id_pppoe);
                    }else if(result.isDenied){
                        show_loading("Mengaktifkan...","Mohon tunggu sebentar");
                        $("#btn-save-paket").attr("data-i",id_pppoe);
                        $("#btn-save-paket").trigger("click");
                    }
                });
            }
        }

        $("#paket, #berlangganan").change(function(){
            const harga = parseInt($("#paket").find(":selected").attr("data-h"));
            const berlangganan = parseInt($("#berlangganan").val());
            const total_bayar = formatharga(harga*berlangganan,'');
            $("#total_bayar").html(total_bayar);
        });

        $("#btn-save-paket").click(function(){
            save_paket();
        });

        function save_paket() {
            const id_server = $("#id_server").val();
            const id_pppoe = $("#btn-save-paket").attr("data-i");
            const paket = $("#paket").val();
            const berlangganan = $("#berlangganan").val();
            if($("#status_debit").prop("checked")){
                var status_debit = "1";
            }else{
                var status_debit = "0";
            }
            $.ajax({
                type:"post",
                url:"<?= base_url("rubah_paket_pppoe") ?>",
                data:{
                    id_server:id_server,
                    id_pppoe:id_pppoe,
                    paket:paket,
                    berlangganan:berlangganan,
                    status_debit:status_debit,
					<?= "'".$this->name_token."':'".$this->token."'"; ?>
                },
				timeout:0,
                dataType:"JSON",
                beforeSend:function(){
                    $("#btn-save-paket").html("Mengaktifkan...");
                    $("#btn-save-paket").attr("disabled",true);
                },
                success:function(r){
                    d = JSON.parse(JSON.stringify(r));
                    swal.fire({
                        title:d.title,
                        html:d.pesan,
                        icon:d.icon
                    });

                    if(d.icon == "success"){
                        $("#form_edit_paket").modal("hide");
                        $("#paket-"+id_pppoe).html(d.data.paket);
                        $("#auto-debit-"+id_pppoe).html(d.data.auto_debit);
                        $("#date-order-"+id_pppoe).html(d.data.date_order);
                        $("#expired-date-"+id_pppoe).html(d.data.expired_date);
                        $("#berlangganan-"+id_pppoe).html(d.data.berlangganan);
                        $("#status-"+id_pppoe).html(d.data.status_disabled);
                    }
                    $("#btn-save-paket").html("Aktifkan");
                    $("#btn-save-paket").attr("disabled",false);
                },
                error:function(a,b,c){
                    swal.fire("Error",a.responseText,"error");
                    $("#btn-save-paket").html("Aktifkan");
                    $("#btn-save-paket").attr("disabled",false);
                }
            });
        }

        function update_status() {
            $.ajax({
                type:"POST",
                url:"<?= base_url("update_status_pppoe") ?>",
                data:{
                    id_server:id_server,
                    id_pppoe:id_pppoe,
                    status:status,
					<?= "'".$this->name_token."':'".$this->token."'"; ?>
                },
				timeout:0,
                dataType:"JSON",
                beforeSend:function(){
                    show_loading("Loading...","Mohon tunggu sebentar");
                },
                success:function(r){
                    d = JSON.parse(JSON.stringify(r));
                    swal_alert(d.title,d.pesan,d.icon);
                    if(d.icon == "success"){
                        $("#status-"+id_pppoe).html(d.icon_disable);
                        if(status == "0"){
                            $("#btn-active-"+id_pppoe).attr("data-s","1");
                            $("#btn-active-"+id_pppoe).html("Re-Active");
                        }else{
                            $("#btn-active-"+id_pppoe).attr("data-s","0");
                            $("#btn-active-"+id_pppoe).html("Disable");
                        }
                    }
                },
                error:function(a,b,c){
                    swal_alert("Error",a.responseText,"error");
                }
            });
        }
    });

    function simpan_edit() {
        const id_pppoe = $("#action").attr("data-i");
        const id_sche = $("#action").attr("data-sche");
        const id_server = $("#id_server").val();
        const username = $("#name").val();
        const password = $("#password").val();
        $.ajax({
            type:"post",
            url:"<?= base_url("simpan_edit_pppoe") ?>",
            data:{
                id_pppoe:id_pppoe,
                id_sche:id_sche,
                id_server:id_server,
                username:username,
                password:password,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                btn_loading();
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                swal.fire({
                    title:d.title,
                    html:d.pesan,
                    icon:d.icon,
                });

                if(d.icon == "success"){
                    $("#form_edit").modal("toggle");
                    $("#name-"+id_pppoe).html(username);
                    $("#password-"+id_pppoe).html(password);
                    $("#btn-edit-"+id_pppoe).attr("data-username",username);
                    $("#btn-edit-"+id_pppoe).attr("data-password",password);
                    $("#check-username").html("");
                }
                btn_finish();
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
                btn_finish();
            }
        })
    }

    $("#action").click(function() {
        simpan_edit();
    })

    function btn_loading() {
        $("#action").html("Menyimpan...");
        $("#action").attr("disabled",true);
    }

    function btn_finish() {
        $("#action").html("Simpan");
        $("#action").attr("disabled",false);
    }

    var loading = '<i class="fas fa-spinner fa-pulse text-info m-2"></i>';
    var checked = '<i class="fas fa-check-circle text-success m-2"></i>';
    var failed = '<i class="fas fa-times-circle text-danger m-2"></i>';
    function check_username() {
        const id_pppoe = $("#action").attr("data-i");
        const username = $("#name").val();
        const id_server = $("#id_server").val();
        $.ajax({
            type:'post',
            url:'<?= base_url("check_username_pppoe") ?>',
            data:{
                id_pppoe:id_pppoe,
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
                            swal.fire({
                                title:d.title,
                                html:d.pesan,
                                icon:d.icon
                            });
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
</script>
