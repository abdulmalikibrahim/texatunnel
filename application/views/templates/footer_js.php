<!-- container-scroller -->
<!-- plugins:js -->
<script src="<?= base_url("assets/skydash/vendors/js/vendor.bundle.base.js"); ?>"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="<?= base_url("assets/skydash/vendors/chart.js/Chart.min.js"); ?>"></script>
<script src="<?= base_url("assets/skydash/vendors/datatables.net/jquery.dataTables.js"); ?>"></script>
<script src="<?= base_url("assets/skydash/vendors/datatables.net-bs4/dataTables.bootstrap4.js"); ?>"></script>
<script src="<?= base_url("assets/skydash/js/dataTables.select.min.js"); ?>"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="<?= base_url("assets/skydash/js/off-canvas.js"); ?>"></script>
<script src="<?= base_url("assets/skydash/js/hoverable-collapse.js"); ?>"></script>
<script src="<?= base_url("assets/skydash/js/template.js"); ?>"></script>
<script src="<?= base_url("assets/skydash/js/settings.js"); ?>"></script>
<script src="<?= base_url("assets/skydash/js/todolist.js"); ?>"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="<?= base_url("assets/skydash/js/dashboard.js"); ?>"></script>
<script src="<?= base_url("assets/skydash/js/Chart.roundedBarCharts.js"); ?>"></script>
<!-- End custom js for this page-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js"></script>
<script>

	function refreshtoken(name,token) {
		$("#verifiedtoken").attr("data-name",name);
		$("#verifiedtoken").attr("data-token",token);
	}
    $(".datepicker").datepicker({
        format: 'dd-mm-yyyy'
    });

    $(".harga").keyup(function() {
        $(this).val(formatharga($(this).val(),''))
    });
    
    function formatharga(angka, prefix){
        var number_string = angka.toString().replace(/[^,\d]/g, ''),
        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        harga     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            harga += separator + ribuan.join('.');
        }

        harga = split[1] != undefined ? harga + ',' + split[1] : harga;
        return prefix == undefined ? harga : (harga ? harga : '');
    }

    function show_loading(title,pesan) {
        Swal.fire({
            title:title,
            html: pesan,
            timerProgressBar: true,
            didOpen: () => {
                Swal.showLoading()
                const b = Swal.getHtmlContainer().querySelector('b')
            },
            allowOutsideClick: false
        })
    }

    function swal_alert(title,pesan,icon){
        swal.fire({
            title:title,
            html:pesan,
            icon:icon,
        });
    }

    function check_saldo(element) {
        $.ajax({
            url:"<?= base_url("check_saldo"); ?>",
            dataType:"JSON",
            beforeSend:function(){
                $("#"+element).html("Checking...");
            },
            success:function(r){
                d = JSON.parse(JSON.stringify(r));
                $("#"+element).html(d.saldo_val);
                $("#"+element).attr("data-saldo",d.saldo);
            },
            error:function(a,b,c){
                $("#"+element).html("Check Gagal");
            }
        })
    }

</script>
<?php
if(!empty($js_add)){
    $this->load->view("js/".$js_add);
}

if(!empty($this->session->flashdata("swal"))){
    echo $this->session->flashdata("swal");
}
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
