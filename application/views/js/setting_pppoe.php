<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
<script>
    $(".form-clear").val("");

    function check_interface() {
        if($("#server").find(":selected").attr("data-id") != ''){
            $.ajax({
                type:'get',
                url:'<?= base_url("data_interface") ?>',
                data:{
                    id: $("#server").find(":selected").attr("data-id"),
					<?= "'".$this->name_token."':'".$this->token."'"; ?>
                },
				timeout:0,
                beforeSend:function(){
                    $("#interface").html("<option>Sedang Memuat...</option>");
                },
                success:function(r){
                    $("#interface").html('<option value="" disabled>- Pilih Interface -</option>'+r);
                },
                error:function(a,b,c){
                    $("#interface").html("<option>Error</option>");
                    $("#interface_print").html(a.responseText);
                }
            });
        }
    }

    check_interface();

    //PPPoE SERVER
    function form_add_pppoe() {
        $(".form-clear").val("");
        $("#form_pppoe").modal("show");
        $("#title-pppoe").html("Tambah PPPoE Servers");
        $("#is_active").attr("checked",true);
        $("#btn-simpan-pppoe-servers").attr("onclick","simpan_pppoe_server('add')");
    }

    function list_pppoe_servers() {
        id = $("#server").find(":selected").attr("data-id");
        $.ajax({
            type:"post",
            url:"<?= base_url("list_pppoe_servers/") ?>"+id,
            beforeSend:function(){
                $("#data-pppoe-servers").html('<tr><td colspan="5" align="center"><i>Sedang Memuat PPPoE Server...</i></td></tr>');
            },
            success:function(r){
                $("#data-pppoe-servers").html(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }
    list_pppoe_servers();
    
    function change_status() {
        if($("#is_active").prop("checked") == true){
            $("#is_active").val("1");
            $(".custom-control-label").html("Aktif");
        }else{
            $("#is_active").val("0");
            $(".custom-control-label").html("Non Aktif");
        }
    }

    function simpan_pppoe_server(p) {
        if(p == "add"){
            url = '<?= base_url("simpan_pppoe_servers/add"); ?>';
        }else{
            url = '<?= base_url("simpan_pppoe_servers/"); ?>'+p;
        }
        var id_server = $("#server").find(":selected").attr("data-id");
        var service_name = $("#service_name").val();
        var interface = $("#interface").val();
        var is_active = $("#is_active").val();
        $.ajax({
            type:"post",
            url:url,
            data:{
                id_server:id_server,
                service_name:service_name,
                interface:interface,
                is_active:is_active,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                $("#btn-simpan-pppoe-servers").html("Menyimpan...");
                $("#btn-simpan-pppoe-servers").attr("disabled",true);
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                swal.fire({
                    title:d.title,
                    html:d.pesan,
                    icon:d.icon,
                });

                if(d.icon == "success"){
                    if(p == "add"){
                        list_pppoe_servers();
                    }else{
                        list_pppoe_servers();
                        $("#form_pppoe").modal("toggle");
                    }
                    $(".form-clear").val("");
                }
                $("#btn-simpan-pppoe-servers").html("Simpan");
                $("#btn-simpan-pppoe-servers").attr("disabled",false);
                console.log(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
                $("#btn-simpan-pppoe-servers").html("Simpan");
                $("#btn-simpan-pppoe-servers").attr("disabled",false);
            }
        })
    }
    
    $(document).on("click",".btn-edit-pppoe-servers",function() {
        console.log("click")
        $("#form_pppoe").modal("show");
        $("#title-pppoe").html("Edit PPPoE Servers");
        $("#service_name").val($(this).attr("data-service_name"));
        $("#service_name_bef").val($(this).attr("data-service_name"))
        $("#interface").val($(this).attr("data-interface"));
        if($(this).attr("data-is_active") == "false"){
            $("#is_active").attr("checked",true);
            $(".custom-control-label").html("Aktif");
        }else{
            $("#is_active").attr("checked",false);
            $(".custom-control-label").html("Non Aktif");
        }
        $("#btn-simpan-pppoe-servers").attr("onclick","simpan_pppoe_server('"+$(this).attr("data-id")+"')");
    });

    $(document).on("click",".btn-delete-pppoe-servers",function() {
        id = $(this).attr("data-id");
        Swal.fire({
            title: 'Yakin ingin menghapus data ini?',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            icon:"question",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type:"post",
                    url:'<?= base_url("delete_pppoe_servers/") ?>'+id,
                    data:{
                        id:$("#server").find(":selected").attr("data-id"),
						<?= "'".$this->name_token."':'".$this->token."'"; ?>
                    },
					timeout:0,
                    dataType:"JSON",
                    success:function(r){
                        d = JSON.parse(JSON.stringify(r));
                        swal.fire({
                            title:d.title,
                            html:d.pesan,
                            icon:d.icon,
                        });

                        if(d.icon == "success"){
                            list_pppoe_servers();
                        }
                        console.log(r);
                    },
                    error:function(a,b,c){
                        swal.fire("Error",a.responseText,"error");
                    }
                });
            }
        });
    });


    //IP POOL
    function list_ippool() {
        id = $("#server").find(":selected").attr("data-id");
        $.ajax({
            type:"post",
            url:"<?= base_url("ip_pool/list/") ?>"+id,
            dataType:"JSON",
            beforeSend:function(){
                $("#data-ip-pool").html('<tr><td colspan="4" align="center"><i>Sedang Memuat IP Pool...</i></td></tr>');
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                $("#data-ip-pool").html(d.td_load);
                $("#local_address").html(d.option_load);
                $("#remote_address").html(d.option_load);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }
    list_ippool();

    function form_add_ippool() {
        $(".form-clear").val("");
        $("#form_ippool").modal("show");
        $("#title-ip-pool").html("Tambah IP Pool PPPoE");
        $("#btn-simpan-ip-pool").attr("onclick","simpan_ip_pool('add')");
    }

    function simpan_ip_pool(p) {
        if(p == "add"){
            url = '<?= base_url("simpan_ip_pool/add"); ?>';
        }else{
            url = '<?= base_url("simpan_ip_pool/"); ?>'+p;
        }
        var id_server = $("#server").find(":selected").attr("data-id");
        var name = $("#name_ip_pool").val();
        var addresses = $("#addresses").val();
        $.ajax({
            type:"post",
            url:url,
            data:{
                id_server:id_server,
                name:name,
                addresses:addresses,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                $("#btn-simpan-ip-pool").html("Menyimpan...");
                $("#btn-simpan-ip-pool").attr("disabled",true);
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                swal.fire({
                    title:d.title,
                    html:d.pesan,
                    icon:d.icon,
                });

                if(d.icon == "success"){
                    if(p == "add"){
                        list_ippool();
                    }else{
                        list_ippool();
                        $("#form_ippool").modal("toggle");
                    }
                }
                $(".form-clear").val("");
                $("#btn-simpan-ip-pool").html("Simpan");
                $("#btn-simpan-ip-pool").attr("disabled",false);
                console.log(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
                $("#btn-simpan-ip-pool").html("Simpan");
                $("#btn-simpan-ip-pool").attr("disabled",false);
            }
        })
    }
    
    $(document).on("click",".btn-edit-ip-pool",function() {
        console.log("click")
        $("#form_ippool").modal("show");
        $("#title-ippool").html("Edit IP Pool");
        $("#name_ip_pool").val($(this).attr("data-name"));
        $("#addresses").val($(this).attr("data-addresses"))
        $("#btn-simpan-ip-pool").attr("onclick","simpan_ip_pool('"+$(this).attr("data-id")+"')");
    });

    $(document).on("click",".btn-delete-ip-pool",function() {
        id = $(this).attr("data-id");
        Swal.fire({
            title: 'Yakin ingin menghapus data ini?',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            icon:"question",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type:"post",
                    url:'<?= base_url("ip_pool/remove/") ?>'+id,
                    data:{
                        id:$("#server").find(":selected").attr("data-id"),
						<?= "'".$this->name_token."':'".$this->token."'"; ?>
                    },
					timeout:0,
                    dataType:"JSON",
                    success:function(r){
                        d = JSON.parse(JSON.stringify(r));
                        swal.fire({
                            title:d.title,
                            html:d.pesan,
                            icon:d.icon,
                        });

                        if(d.icon == "success"){
                            list_ippool();
                        }
                        console.log(r);
                    },
                    error:function(a,b,c){
                        swal.fire("Error",a.responseText,"error");
                    }
                });
            }
        });
    });


    //PPP PROFILE
    function list_profile_pppoe() {
        id = $("#server").find(":selected").attr("data-id");
        $.ajax({
            type:"post",
            url:"<?= base_url("profile_pppoe/list/") ?>"+id,
            beforeSend:function(){
                $("#data-profile-pppoe").html('<tr><td colspan="11" align="center"><i>Sedang Memuat Profile PPPoE...</i></td></tr>');
            },
            success:function(r){
                $("#data-profile-pppoe").html(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }
    list_profile_pppoe();

    function form_add_profile_pppoe() {
        $(".form-clear").val("");
        $("#form_profile_pppoe").modal("show");
        $("#title-profile-pppoe").html("Tambah Profile PPPoE");
        $("#btn-simpan-profile-pppoe").attr("onclick","simpan_profile_pppoe('add')");
    }

    function simpan_profile_pppoe(p) {
        if(p == "add"){
            url = '<?= base_url("simpan_profile_pppoe/add"); ?>';
        }else{
            url = '<?= base_url("simpan_profile_pppoe/"); ?>'+p;
        }
        var id_server = $("#server").find(":selected").attr("data-id");
        var name = $("#name_profile_pppoe").val();
        var local_address = $("#local_address").val();
        var remote_address = $("#remote_address").val();
        var download_rate = $("#download_rate").val();
        var upload_rate = $("#upload_rate").val();
        var only_one = $("#only_one").val();
        var harga = $("#harga").val();
        console.log(id_server);
        $.ajax({
            type:"post",
            url:url,
            data:{
                id_server:id_server,
                name:name,
                local_address:local_address,
                remote_address:remote_address,
                download_rate:download_rate,
                upload_rate:upload_rate,
                only_one:only_one,
                harga:harga,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                $("#btn-simpan-profile-pppoe").html("Menyimpan...");
                $("#btn-simpan-profile-pppoe").attr("disabled",true);
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                swal.fire({
                    title:d.title,
                    html:d.pesan,
                    icon:d.icon,
                });

                if(d.icon == "success"){
                    if(p != "add"){
                        $("#form_profile_pppoe").modal("toggle");
                    }
                    list_profile_pppoe();
                    $(".form-clear").val("");
                }
                $("#btn-simpan-profile-pppoe").html("Simpan");
                $("#btn-simpan-profile-pppoe").attr("disabled",false);
                console.log(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
                $("#btn-simpan-profile-pppoe").html("Simpan");
                $("#btn-simpan-profile-pppoe").attr("disabled",false);
            }
        })
    }

    $(document).on("click",".btn-edit-profile-pppoe",function() {
        $("#form_profile_pppoe").modal("show");
        $("#title-profile-pppoe").html("Edit IP Pool");
        $("#name_profile_pppoe").val($(this).attr("data-name"));
        $("#local_address").val($(this).attr("data-local-address"));
        $("#remote_address").val($(this).attr("data-remote-address"));
        $("#download_rate").val($(this).attr("data-download-rate"));
        $("#upload_rate").val($(this).attr("data-upload-rate"));
        $("#only_one").val($(this).attr("data-only-one"));
        $("#harga").val($(this).attr("data-harga"));
        $("#btn-simpan-profile-pppoe").attr("onclick","simpan_profile_pppoe('"+$(this).attr("data-id")+"')");
    });

    $(document).on("click",".btn-delete-profile-pppoe",function() {
        id = $(this).attr("data-id");
        Swal.fire({
            title: 'Yakin ingin menghapus data ini?',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            icon:"question",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type:"post",
                    url:'<?= base_url("profile_pppoe/remove/") ?>'+id,
                    data:{
                        id:$("#server").find(":selected").attr("data-id"),
						<?= "'".$this->name_token."':'".$this->token."'"; ?>
                    },
					timeout:0,
                    dataType:"JSON",
                    success:function(r){
                        d = JSON.parse(JSON.stringify(r));
                        swal.fire({
                            title:d.title,
                            html:d.pesan,
                            icon:d.icon,
                        });

                        if(d.icon == "success"){
                            list_profile_pppoe();
                        }
                        console.log(r);
                    },
                    error:function(a,b,c){
                        swal.fire("Error",a.responseText,"error");
                    }
                });
            }
        });
    });

    //ALL IN ONE
    function change_server() {
        check_interface();
        list_pppoe_servers();
        list_ippool();
        list_profile_pppoe();
    }
</script>
