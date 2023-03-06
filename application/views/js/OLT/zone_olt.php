<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/fh-3.2.4/r-2.3.0/datatables.min.js"></script>
<script>
	async function get_zone_list() {
		$.ajax({
			url:"<?=base_url("get_zone_list")?>",
			timeout:0,
			dataType:"JSON",
			beforeSend:function() {
				$("#tbody-zone").html('<tr><td colspan="3" class="text-center">Sedang Memuat...</td></tr>');
			},
			success:function(r) {
				console.log(r);
				d = JSON.parse(JSON.stringify(r));
				if(d.status === true){
					$("#tbody-zone").html(d.row);
					$("#table-zone").DataTable({
						responsive:true,
					});
				}else{
					swal.fire("Error",d.error,"error");
				}
			},
			error:function(a,b,c) {
				swal.fire("Error",a.responseText,"error");
			}
		});
	}
	$(document).ready(function() {
		get_zone_list();
	});
</script>
