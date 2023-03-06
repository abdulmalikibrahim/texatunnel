    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/') ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/') ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/') ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/') ?>js/sb-admin-2.min.js"></script>
    <?php
    if(!empty($this->session->flashdata("swal"))){
        echo $this->session->flashdata("swal");
    }
    ?>
    </body>
</html>
<?php
if($this->p2 == "registration" || $this->p1 == "registration"){
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
    <script>
        get_province();
        function get_province() {
            $("#provinsi").html('<option value="" data-id="">Provinsi</option>');
            const options = {
                method: 'GET',
            };
            fetch(
                'http://www.emsifa.com/api-wilayah-indonesia/api/provinces.json',
                options
            )
            .then(response => response.json())
            .then(response => 
                response.forEach(element => {
                    name = kapitalisasiKata(element.name.toLowerCase());
                    id = element.id;
                    $("#provinsi").append('<option value="'+name+'" data-id="'+id+'">'+name+'</option>');
                })
            )
            .catch(err => console.error(err));
        }

        $("#kabupaten").hide();
        $("#provinsi").change(function() {
            var id = $(this).find(":selected").attr("data-id");
            $("#kabupaten").show();
            $("#kabupaten").html('<option value="">Kabupaten</option>');
            const options = {
                method: 'GET',
            };
            fetch(
                'http://www.emsifa.com/api-wilayah-indonesia/api/regencies/'+id+'.json',
                options
            )
            .then(response => response.json())
            .then(response => 
                response.forEach(element => {
                    console.log(element);
                    name = kapitalisasiKata(element.name.toLowerCase());
                    id = element.id;
                    $("#kabupaten").append('<option value="'+name+'" data-id="'+id+'">'+name+'</option>');
                })
            )
            .catch(err => console.error(err));
        });

        $("#kecamatan").hide();
        $("#kabupaten").change(function() {
            var id = $(this).find(":selected").attr("data-id");
            $("#kecamatan").show();
            $("#kecamatan").html('<option value="">Kecamatan</option>');
            const options = {
                method: 'GET',
            };
            fetch(
                'http://www.emsifa.com/api-wilayah-indonesia/api/districts/'+id+'.json',
                options
            )
            .then(response => response.json())
            .then(response => 
                response.forEach(element => {
                    console.log(element);
                    name = kapitalisasiKata(element.name.toLowerCase());
                    id = element.id;
                    $("#kecamatan").append('<option value="'+name+'" data-id="'+id+'">'+name+'</option>');
                })
            )
            .catch(err => console.error(err));
        });

        $("#kelurahan").hide();
        $("#kecamatan").change(function() {
            var id = $(this).find(":selected").attr("data-id");
            $("#kelurahan").show();
            $("#kelurahan").html('<option value="">Kelurahan</option>');
            const options = {
                method: 'GET',
            };
            fetch(
                'http://www.emsifa.com/api-wilayah-indonesia/api/villages/'+id+'.json',
                options
            )
            .then(response => response.json())
            .then(response => 
                response.forEach(element => {
                    console.log(element);
                    name = kapitalisasiKata(element.name.toLowerCase());
                    id = element.id;
                    $("#kelurahan").append('<option value="'+name+'" data-id="'+id+'">'+name+'</option>');
                })
            )
            .catch(err => console.error(err));
        });

        function kapitalisasiKata(str)
        {
            return str.replace(/\w\S*/g, function(kata){ 
            const kataBaru = kata.slice(0,1).toUpperCase() + kata.substr(1);
            return kataBaru
            });    
        }

        $("#form_registrasi").submit(function() {
            loading();
        });

        function loading() {
            Swal.fire({
                title:"Processing...",
                html: "<img src='https://media.tenor.com/zDynNSyQb1sAAAAC/loading-waiting.gif' width='30%'><br><br>Mohon tunggu akun anda sedang kami siapkan.",
                timerProgressBar: true,
                showConfirmButton:false,
                allowOutsideClick: false
            });
        }
    </script>
    <?php
}
?>