<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/fh-3.2.4/r-2.3.0/datatables.min.js"></script>
<script>
    $("#datatable").DataTable({
        responsive:true,
    });

	$(".btn-hapus").click(function() {
		Swal.fire({
			title: 'Yakin ingin menghapus data ini?',
			showCancelButton: true,
			confirmButtonText: 'Ya, Hapus',
			icon:"question",
		}).then((result) => {
			/* Read more about isConfirmed, isDenied below */
			if (result.isConfirmed) {
				window.location.href = '<?= base_url("user/delete/") ?>'+$(this).attr("data-id");
			}
		})
	})
</script>
