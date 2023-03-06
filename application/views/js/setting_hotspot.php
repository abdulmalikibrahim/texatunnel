<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
<script>
    $(".form-clear").val("");

    function check_interface() {
        if($("#server").find(":selected").attr("data-id") != ''){
            $.ajax({
                type:'get',
                url:'<?= base_url("data_interface_hotspot") ?>',
                data:{
                    id: $("#server").find(":selected").attr("data-id"),
                },
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

    function check_ip_address(p) {
        $.ajax({
            type:'post',
            url:'<?= base_url("ip_address_hotspot") ?>',
            data:{
                id_server: $("#server").find(":selected").attr("data-id"),
                interface: $("#interface").val(),
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            beforeSend:function(){
                $("#local-address").html("<option>Sedang Memuat...</option>");
            },
            success:function(r){
                $("#local-address").html('<option value="" disabled>- Pilih Local Address -</option>'+r);
                if(p == "element"){
                    element_ip_pool_hotspot();
                }else{
                    return "no-element";
                }
            },
            error:function(a,b,c){
                $("#local-address").html("<option>Error</option>");
            }
        });
    }

    function element_ip_pool_hotspot() {
        if($("#local-address").val() != ""){
            $.ajax({
                type:'post',
                url:'<?= base_url("element_ip_pool_hotspot") ?>',
                data:{
                    local_address: $("#local-address").val(),
					<?= "'".$this->name_token."':'".$this->token."'"; ?>
                },
				timeout:0,
                beforeSend:function(){
                    $("#element-ip-pool").html("<option>Sedang Memuat...</option>");
                },
                success:function(r){
                    $("#element-ip-pool").html(r);
                },
                error:function(a,b,c){
                    $("#element-ip-pool").html(a.responseText);
                }
            });
        }else{
            $("#element-ip-pool").html("");
        }
    }

    //HOTSPOT SERVER
    function form_add_hotspot() {
        $(".form-clear").val("");
        $("#form_hotspot").modal("show");
        $("#title-hotspot").html("Tambah Hotspot Servers");
        $("#is_active").attr("checked",true);
        $("#btn-simpan-hotspot-servers").attr("onclick","simpan_hotspot_server('add')");
        $("#select-idle-timeout").val("00:05:00");
        $("#select-addresses-mac").val("2");
    }

    function list_hotspot_servers() {
        id = $("#server").find(":selected").attr("data-id");
        $.ajax({
            type:"post",
            url:"<?= base_url("list_hotspot_servers/") ?>"+id,
            dataType:"JSON",
            beforeSend:function(){
                $("#data-hotspot-servers").html('<tr><td colspan="7" align="center"><i>Sedang Memuat Hotspot Server...</i></td></tr>');
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                $("#data-hotspot-servers").html(d.td_load);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        });
    }
    
    function change_status() {
        if($("#is_active").prop("checked") == true){
            $("#is_active").val("1");
            $(".custom-control-label").html("Aktif");
        }else{
            $("#is_active").val("0");
            $(".custom-control-label").html("Non Aktif");
        }
    }

    function simpan_hotspot_server(p) {
        const ip_pool_address=$('.ip-pool-address')
        .map(function(){ return this.value; })
        .get().join(';');
        console.log(ip_pool_address);
        if(p == "add"){
            url = '<?= base_url("simpan_hotspot_servers/add"); ?>';
        }else{
            url = '<?= base_url("simpan_hotspot_servers/"); ?>'+p;
        }
        const id_server = $("#server").find(":selected").attr("data-id");
        const service_name = $("#service_name").val();
        const interface = $("#interface").val();
        const local_address = $("#local-address").val();
        const dns_name_server = $("#dns_name_server").val();
        const username = $("#username").val();
        const password = $("#password").val();
        const cookie = $("#cookie").val();
        const time_cookie_hours = $("#time_cookie_hours").val();
        const time_cookie_minutes = $("#time_cookie_minutes").val();
        const time_cookie_seconds = $("#time_cookie_seconds").val();
        const trial = $("#trial").val();
        const time_trial_hours = $("#time_trial_hours").val();
        const time_trial_minutes = $("#time_trial_minutes").val();
        const time_trial_seconds = $("#time_trial_seconds").val();
        const is_active = $("#is_active").val();
        const id_ip_pool = $("#btn-simpan-hotspot-servers").attr("data-id-ip-pool");
        const id_profile = $("#btn-simpan-hotspot-servers").attr("data-id-profile");
        const id_user = $("#btn-simpan-hotspot-servers").attr("data-id-user");
        const id_dhcp_server = $("#btn-simpan-hotspot-servers").attr("data-id-dhcp-server");
        const id_firewall_nat = $("#btn-simpan-hotspot-servers").attr("data-id-firewall-nat");
        $.ajax({
            type:"post",
            url:url,
            data:{
                id_server:id_server,
                service_name:service_name,
                interface:interface,
                local_address:local_address,
                dns_name_server:dns_name_server,
                ip_pool_address:ip_pool_address,
                username:username,
                password:password,
                cookie:cookie,
                is_active:is_active,
                id_ip_pool:id_ip_pool,
                id_profile:id_profile,
                id_user:id_user,
                id_dhcp_server:id_dhcp_server,
                id_firewall_nat:id_firewall_nat,
                time_cookie_hours:time_cookie_hours,
                time_cookie_minutes:time_cookie_minutes,
                time_cookie_seconds:time_cookie_seconds,
                trial:trial,
                time_trial_hours:time_trial_hours,
                time_trial_minutes:time_trial_minutes,
                time_trial_seconds:time_trial_seconds,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                $("#btn-simpan-hotspot-servers").html("Menyimpan...");
                $("#btn-simpan-hotspot-servers").attr("disabled",true);
                show_loading("Menyiapkan...","Sedang membuatkan hotspot server baru, Mohon tunggu sebentar.");
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
                        list_hotspot_servers();
                        list_profile_hotspot();
                        list_ippool();
                    }else{
                        list_hotspot_servers();
                        list_profile_hotspot();
                        list_ippool();
                        $("#form_hotspot").modal("toggle");
                    }
                    $(".form-clear").val("");
                }
                $("#btn-simpan-hotspot-servers").html("Simpan");
                $("#btn-simpan-hotspot-servers").attr("disabled",false);
                console.log(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
                $("#btn-simpan-hotspot-servers").html("Simpan");
                $("#btn-simpan-hotspot-servers").attr("disabled",false);
            }
        });
    }
    
    $(document).on("click",".btn-edit-hotspot-servers",function() {
        const id_hotspot = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        const interface = $(this).attr("data-interface");
        const ip_dns = $(this).attr("data-ip-dns");
        const name_no_hotspot = $(this).attr("data-n");
        $.ajax({
            type:"post",
            url:"<?= base_url("setup_hotspot_edit") ?>",
            data:{
                id_server:$("#server").find(":selected").attr("data-id"),
                id_hotspot:id_hotspot,
                name:name,
                interface:interface,
                ip_dns:ip_dns,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                show_loading("Menyiapkan...","Mohon tunggu sebentar");
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                if(d.icon == "success"){
                    swal.close();
                    console.log(r);
                    $("#service_name").val(name_no_hotspot);
                    $("#interface").val(interface);
                    if(check_ip_address('no-element') == "no-element"){
                        $("#local-address").val(d.ip_address);
                    }
                    $("#element-ip-pool").html(d.element_ip_address);
                    $("#dns_name_server").val(d.dns_name);
                    $("#username").val(d.username);
                    $("#password").val(d.password);
                    $("#cookie").val(d.cookie);
                    if(d.cookie == "Yes"){
                        $("#waktu_cookie").show();
                        $("#time_cookie_hours").val(d.time_cookie_hours);
                        $("#time_cookie_minutes").val(d.time_cookie_minutes);
                        $("#time_cookie_seconds").val(d.time_cookie_seconds);
                    }else{
                        $("#waktu_cookie").hide();
                    }
                    $("#trial").val(d.trial);
                    if(d.trial == "Yes"){
                        $("#waktu_trial").show();
                        $("#time_trial_hours").val(d.time_trial_hours);
                        $("#time_trial_minutes").val(d.time_trial_minutes);
                        $("#time_trial_seconds").val(d.time_trial_seconds);
                    }else{
                        $("#waktu_trial").hide();
                    }
                    if($(this).attr("data-dis") == "true"){
                        $("#is_active").attr("checked",false)
                        $("#is_active").val("0");
                        $(".custom-control-label").html("Non Aktif");
                    }else{
                        $("#is_active").attr("checked",true)
                        $("#is_active").val("1");
                        $(".custom-control-label").html("Aktif");
                    }
                    $("#title-hotspot").html("Edit Hotspot Server");
                    $("#form_hotspot").modal("show");
                    $("#btn-simpan-hotspot-servers").attr("onclick","simpan_hotspot_server('"+id_hotspot+"')");
                    $("#btn-simpan-hotspot-servers").attr("data-id-ip-pool",d.id_ip_pool);
                    $("#btn-simpan-hotspot-servers").attr("data-id-profile",d.id_profile);
                    $("#btn-simpan-hotspot-servers").attr("data-id-user",d.id_user);
                    $("#btn-simpan-hotspot-servers").attr("data-id-dhcp-server",d.id_dhcp_server);
                    $("#btn-simpan-hotspot-servers").attr("data-id-firewall-nat",d.id_firewall_nat);
                }else{
                    swal.fire({
                        title:d.title,
                        html:d.pesan,
                        icon:d.icon,
                    });
                }
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        });
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
    //END HOTSPOT SERVER

    //PROFILE HOTSPOT
    function list_profile_hotspot() {
        id = $("#server").find(":selected").attr("data-id");
        $.ajax({
            type:"post",
            url:"<?= base_url("list_profile_hotspot/") ?>"+id,
            beforeSend:function(){
                $("#data-profile-hotspot").html('<tr><td colspan="6" align="center"><i>Sedang Memuat Hotspot Profile...</i></td></tr>');
            },
            success:function(r){
                $("#data-profile-hotspot").html(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }


    //IP POOL
    function list_ippool() {
        id = $("#server").find(":selected").attr("data-id");
        $.ajax({
            type:"post",
            url:"<?= base_url("ip_pool_hotspot/list/") ?>"+id,
            dataType:"JSON",
            beforeSend:function(){
                $("#data-ip-pool").html('<tr><td colspan="4" align="center"><i>Sedang Memuat IP Pool...</i></td></tr>');
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                $("#data-ip-pool").html(d.td_load);
                $("#local_address").html(d.option_load);
                $("#remote_address").html(d.option_load);
                $("#select-ip-pool").html(d.option_load);
                $("#address_pool_paket").html(d.option_load);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }

    // function form_add_ippool() {
    //     $(".form-clear").val("");
    //     $("#form_ippool").modal("show");
    //     $("#title-ip-pool").html("Tambah IP Pool");
    //     $("#btn-simpan-ip-pool").attr("onclick","simpan_ip_pool('add')");
    // }

    // function simpan_ip_pool(p) {
    //     if(p == "add"){
    //         url = '<?= base_url("simpan_ip_pool/add"); ?>';
    //     }else{
    //         url = '<?= base_url("simpan_ip_pool/"); ?>'+p;
    //     }
    //     var id_server = $("#server").find(":selected").attr("data-id");
    //     var name = $("#name_ip_pool").val();
    //     var addresses = $("#addresses").val();
    //     $.ajax({
    //         type:"post",
    //         url:url,
    //         data:{
    //             id_server:id_server,
    //             name:name,
    //             addresses:addresses,
    //         },
    //         dataType:"JSON",
    //         beforeSend:function(){
    //             $("#btn-simpan-ip-pool").html("Menyimpan...");
    //             $("#btn-simpan-ip-pool").attr("disabled",true);
    //         },
    //         success:function(r){
    //             d = JSON.parse(JSON.stringify(r));
    //             swal.fire({
    //                 title:d.title,
    //                 html:d.pesan,
    //                 icon:d.icon,
    //             });

    //             if(d.icon == "success"){
    //                 if(p == "add"){
    //                     list_ippool();
    //                 }else{
    //                     list_ippool();
    //                     $("#form_ippool").modal("toggle");
    //                 }
    //             }
    //             $(".form-clear").val("");
    //             $("#btn-simpan-ip-pool").html("Simpan");
    //             $("#btn-simpan-ip-pool").attr("disabled",false);
    //             console.log(r);
    //         },
    //         error:function(a,b,c){
    //             swal.fire("Error",a.responseText,"error");
    //             $("#btn-simpan-ip-pool").html("Simpan");
    //             $("#btn-simpan-ip-pool").attr("disabled",false);
    //         }
    //     })
    // }
    
    // $(document).on("click",".btn-edit-ip-pool",function() {
    //     console.log("click")
    //     $("#form_ippool").modal("show");
    //     $("#title-ippool").html("Edit IP Pool");
    //     $("#name_ip_pool").val($(this).attr("data-name"));
    //     $("#addresses").val($(this).attr("data-addresses"))
    //     $("#btn-simpan-ip-pool").attr("onclick","simpan_ip_pool('"+$(this).attr("data-id")+"')");
    // });

    // $(document).on("click",".btn-delete-ip-pool",function() {
    //     id = $(this).attr("data-id");
    //     Swal.fire({
    //         title: 'Yakin ingin menghapus data ini?',
    //         showCancelButton: true,
    //         confirmButtonText: 'Hapus',
    //         icon:"question",
    //     }).then((result) => {
    //         /* Read more about isConfirmed, isDenied below */
    //         if (result.isConfirmed) {
    //             $.ajax({
    //                 type:"post",
    //                 url:'<?= base_url("ip_pool/remove/") ?>'+id,
    //                 data:{
    //                     id:$("#server").find(":selected").attr("data-id"),
    //                 },
    //                 dataType:"JSON",
    //                 success:function(r){
    //                     d = JSON.parse(JSON.stringify(r));
    //                     swal.fire({
    //                         title:d.title,
    //                         html:d.pesan,
    //                         icon:d.icon,
    //                     });

    //                     if(d.icon == "success"){
    //                         list_ippool();
    //                     }
    //                     console.log(r);
    //                 },
    //                 error:function(a,b,c){
    //                     swal.fire("Error",a.responseText,"error");
    //                 }
    //             });
    //         }
    //     });
    // });
    //END IP POOL


    //HOTSPOT PROFILE USER
    function list_paket_hotspot() {
        id = $("#server").find(":selected").attr("data-id");
        $.ajax({
            type:"post",
            url:"<?= base_url("list_paket_hotspot/") ?>"+id,
            dataType:"JSON",
            beforeSend:function(){
                $("#data-paket-hotspot").html('<tr><td colspan="11" align="center"><i>Sedang Memuat Paket Hotspot...</i></td></tr>');
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                $("#data-paket-hotspot").html(d.td_load);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }

    function form_add_paket() {
        $(".form-clear").val("");
        $("#form_user_paket").modal("show");
        $("#session_time_hours").val("1");
        $("#title-paket-hotspot").html("Tambah Paket Hotspot");
        $("#btn-simpan-paket-hotspot").attr("onclick","simpan_paket_hotspot('add')");
    }

    function simpan_paket_hotspot(p) {
        if(p == "add"){
            url = '<?= base_url("save_paket_hotspot/add"); ?>';
        }else{
            url = '<?= base_url("save_paket_hotspot/"); ?>'+p;
        }
        var id_server = $("#server").find(":selected").attr("data-id");
        var name_paket = $("#name_paket").val();
        var address_pool_paket = $("#address_pool_paket").val();
        var session_time_days = $("#session_time_days").val();
        var session_time_hours = $("#session_time_hours").val();
        var session_time_minutes = $("#session_time_minutes").val();
        var download_limit = $("#download_limit").val();
        var upload_limit = $("#upload_limit").val();
        var shared_users = $("#shared_users").val();
        var harga = $("#harga").val();
        console.log(id_server);
        $.ajax({
            type:"post",
            url:url,
            data:{
                id_server:id_server,
                name_paket:name_paket,
                address_pool_paket:address_pool_paket,
                session_time_days:session_time_days,
                session_time_hours:session_time_hours,
                session_time_minutes:session_time_minutes,
                download_limit:download_limit,
                upload_limit:upload_limit,
                shared_users:shared_users,
                harga:harga,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                $("#btn-simpan-paket-hotspot").html("Menyimpan...");
                $("#btn-simpan-paket-hotspot").attr("disabled",true);
                show_loading("Processing...","Sedang Membuat Paket Anda.");
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
                        $("#form_user_paket").modal("toggle");
                    }
                    list_paket_hotspot();
                    $(".form-clear").val("");
                    $("#session_time_days").val("0");
                    $("#session_time_hours").val("1");
                    $("#session_time_minutes").val("0");
                }
                $("#btn-simpan-paket-hotspot").html("Simpan");
                $("#btn-simpan-paket-hotspot").attr("disabled",false);
                console.log(r);
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
                $("#btn-simpan-paket-hotspot").html("Simpan");
                $("#btn-simpan-paket-hotspot").attr("disabled",false);
            }
        })
    }

    $(document).on("click",".btn-edit-paket",function() {
        $("#form_user_paket").modal("show");
        $("#title-paket-hotspot").html("Edit IP Pool");
        $("#name_paket").val($(this).attr("data-name"));
        $("#address_pool_paket").val($(this).attr("data-address-pool"));
        $("#session_time_days").val($(this).attr("data-days"));
        $("#session_time_hours").val($(this).attr("data-hours"));
        $("#session_time_minutes").val($(this).attr("data-minutes"));
        $("#download_limit").val($(this).attr("data-download"));
        $("#upload_limit").val($(this).attr("data-upload"));
        $("#shared_users").val($(this).attr("data-shared"));
        $("#harga").val($(this).attr("data-harga"));
        $("#btn-simpan-paket-hotspot").attr("onclick","simpan_paket_hotspot('"+$(this).attr("data-i")+"')");
    });

    $(document).on("click",".btn-delete-paket",function() {
        id = $(this).attr("data-i");
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
                    url:'<?= base_url("remove_paket_hotspot/") ?>'+id,
                    data:{
                        id_server:$("#server").find(":selected").attr("data-id"),
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
                            list_paket_hotspot();
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
    //END HOTSPOT PROFILE USER

    //ALL IN ONE
    function change_server() {
        list_ippool();
        list_profile_hotspot();
        list_hotspot_servers();
        check_interface();
        list_paket_hotspot();
    }
    // change_server();

    //Cookie Status
    $("#waktu_cookie").hide();
    $("#cookie").change(function() {
        if($(this).val() == "Yes"){
            $("#waktu_cookie").show();
        }else{
            $("#waktu_cookie").hide();
        }
    });

    $("#waktu_trial").hide();
    $("#trial").change(function() {
        if($(this).val() == "Yes"){
            $("#waktu_trial").show();
        }else{
            $("#waktu_trial").hide();
        }
    });
</script>
