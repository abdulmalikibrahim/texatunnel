<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/fh-3.2.4/r-2.3.0/datatables.min.js"></script>
<script>
    $("#datatable").DataTable({
        responsive:true,
    });
    function loading() {
        Swal.fire({
            title:"Check Koneksi...",
            html: "Mohon tunggu sistem sedang check koneksi server",
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
            },
            allowOutsideClick: false
        })
    }

    function test(id) {
        $.ajax({
            url:"<?=base_url("test_routeros/")?>"+id,
            beforeSend:function(){
                loading();
            },
			timeout:0,
            success:function(r){
                if(r == 200){
                    swal.fire("Sukses","Testing Koneksi Sukses","success");
                }else{
                    swal.fire("Error","Testing Koneksi Gagal, Mohon Periksa kembali IP Address, Port, Username, atau Password Server anda, dan mohon gunakan IP Public","error");
                }
                console.log(r);
            },
            error:function(a,b,c){
                swal.fire(c,"Testing Koneksi Gagal, Mohon Periksa kembali IP Address, Port, Username, atau Password Server anda, dan mohon gunakan IP Public",b);
            }
        })
    }
</script>
