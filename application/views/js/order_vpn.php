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

    $("#VPNRemote").submit(function() {
        Swal.fire({
            title:"Loading...",
            html: "Sedang membuat Remote VPN anda",
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
            },
            allowOutsideClick: false
        })
    });
	
	$('.input-group-prepend').hide();
	$("#username").keyup(function() {
		if($("#id_server").val()){
			$.ajax({
				type:"post",
				url:"<?= base_url('check_username_vpn') ?>",
				data:{
					id_server:$("#id_server").val(),
					username:$(this).val(),
				},
				dataType:"JSON",
				success:function(r) {
					d = JSON.parse(JSON.stringify(r));
					if(d.status == "success"){
						$('.input-group-prepend').show();
						$("#status-check").html('<i class="fas fa-check-circle text-success"></i>');
					}else if(d.status == "error"){
						$('.input-group-prepend').show();
						$("#status-check").html('<i class="fas fa-check-circle text-success"></i>');
					}else{
						swal.fire("Warning",d.pesan,"warning");
						$("#username").val("");
					}
				},
				error:function(a,b,c) {
					swal.fire("Error",a.responseText,"error");
				}
			});
			if(!$(this).val()){
				$('.input-group-prepend').hide();
			}
		}else{
			swal.fire("Warning","Mohon pilih server terlebih dahulu","warning");
			$("#username").val("");
		}
	})
</script>
