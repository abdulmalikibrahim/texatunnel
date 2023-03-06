<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
<script>
    $("#id_server, #paket").change(function() {
        const total_bayar = formatharga($("#paket").find(":selected").attr("data-h"), '');
        const waktu = $("#paket").find(":selected").attr("data-waktu");
        const download = $("#paket").find(":selected").attr("data-download");
        const upload = $("#paket").find(":selected").attr("data-upload");
        const shared = $("#paket").find(":selected").attr("data-shared");
        $("#total_bayar").html(total_bayar);
        $("#info-waktu").html(": "+waktu);
        $("#info-download").html(": "+download);
        $("#info-upload").html(": "+upload);
        $("#info-shared").html(": "+shared);
    });

	$("#id_user").change(function() {
		if($(this).val() == "tambah user"){
			$("#modal_input_user").modal("show");
		}
	});
	
	$("#btn-simpan-user").click(function() {
		name = $("#name").val();
		email = $("#email").val();
		phone_number = $("#phone_number").val();
		is_active = 1;
		$.ajax({
			type:"post",
			url:"<?= base_url("save_user/0") ?>"
		});
	});

    function get_paket() {
        id = $("#id_server").val();
        $.ajax({
            type: "post",
            url: "<?= base_url("list_paket_hotspot/") ?>" + id,
			timeout:0,
            dataType: "JSON",
            beforeSend: function() {
                $("#paket").html('<option>Sedang Memuat...</option>');
            },
            success: function(r) {
                d = JSON.parse(JSON.stringify(r));
                $("#paket").html("");
                $("#paket").prepend(d.option_load);
                $("#paket").prepend('<option value="" disabled selected>Silahkan Pilih Paket</option>');
            },
            error: function(a, b, c) {
                $("#paket").html('<option>Error Saat Memuat</option>');
            }
        })
    }
    get_paket();

    function hotspot_server() {
        id = $("#id_server").val();
        $.ajax({
            type:"post",
            url:"<?= base_url("list_hotspot_servers/") ?>"+id,
			timeout:0,
            dataType:"JSON",
            beforeSend:function(){
                $("#server_hotspot").html('<option>Sedang Memuat...</option>');
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                $("#server_hotspot").html("");
                $("#server_hotspot").prepend(d.option_load);
                $("#server_hotspot").prepend('<option value="" disabled selected>Silahkan Pilih Server Hotspot</option>');
            },
            error:function(a,b,c){
                $("#server_hotspot").html('<option>Error Saat Memuat</option>');
            }
        });
    }
    hotspot_server();

    function simpan_order() {
        $.ajax({
            type: "post",
            url: "<?= base_url("simpan_order_hotspot") ?>",
			timeout:0,
            data: {
                id_server: $("#id_server").val(),
                paket: $("#paket").val(),
                server_hotspot: $("#server_hotspot").val(),
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType: "JSON",
            beforeSend: function() {
                show_loading("Processing...", "Sedang order hotspot anda.");
            },
            success: function(r) {
                d = JSON.parse(JSON.stringify(r));
                console.log(r);
                if (d.icon == "success") {
                    swal.fire({
                        title: d.title,
                        html: d.pesan,
                        icon: d.icon,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            if(d.link_hotspot == ""){
                                link_hotspot = $("#id_server").find(":selected").attr("data-ip-address");
                            }else{
                                link_hotspot = d.link_hotspot;
                            }
                            window.open('http://'+link_hotspot,'_blank');
                        }
                    });
                    $("#server_hotspot").val("");
                    $("#paket").val("");
                    $("#total_bayar").htlm("");
                }else{
                    swal.fire({
                        title: d.title,
                        html: d.pesan,
                        icon: d.icon,
                    });
                }
            },
            error:function(a,b,c){
                swal.fire("Error",a.responseText,"error");
            }
        })
    }
</script>
