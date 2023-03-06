<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/fh-3.2.4/r-2.3.0/datatables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.min.js"></script>
<script>
    function get_all() {
        $.ajax({
            type: "post",
            url: "<?= base_url("get_list_order_hotspot") ?>",
			timeout:1,
            data: {
                id_server: $("#id_server").val(),
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType: "JSON",
            beforeSend: function() {
                $("#data-body").html("<tr><td colspan='12' class='text-center'><i>Sedang Memuat...</i></td></tr>");
            },
            success: function(r) {
                d = JSON.parse(JSON.stringify(r));
                if (d.status == "success") {
                    $("#data-body").html(d.data);
                    $("#datatable").DataTable({
                        responsive: true,
                    });
                } else {
                    swal.fire({
                        title: d.title,
                        html: d.pesan,
                        icon: d.icon,
                    });
                }
            },
            error: function(a, b, c) {
                swal.fire("Error", a.responseText, "error");
            }
        })
    }
    get_all();

    $(document).on('click', '.btn-edit', function() {
        $("#form_user").modal("show");
        $("#action").attr("data-i", $(this).attr("data-i"));
        $("#name").val($(this).attr("data-username"));
        $("#password").val($(this).attr("data-password"));
    });

    $(document).on('click', '.btn-delete', function() {
        const id_server = $("#id_server").val();
        const id_hotspot = $(this).attr("data-i");
        const username = $(this).attr("data-username");
        Swal.fire({
            title: 'Yakin hapus hotspot ' + username + '?',
            html: 'Data akan di hapus secara permanen',
            showCancelButton: true,
            cancelButtonText: "Tidak",
            confirmButtonColor: "red",
            confirmButtonText: 'Ya, Hapus',
            icon: "question",
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url("hapus_order") ?>",
                    data: {
                        id_server: id_server,
                        id_hotspot: id_hotspot,
                        username: username,
						<?= "'".$this->name_token."':'".$this->token."'"; ?>
                    },
					timeout:0,
                    dataType: "JSON",
                    beforeSend: function() {
                        show_loading("Loading...", "Sedang proses penghapusan");
                    },
                    success: function(r) {
                        d = JSON.parse(JSON.stringify(r));
                        swal_alert(d.title, d.pesan, d.icon);
                        if (d.icon == "success") {
                            $("#row-" + id_hotspot).remove();
                        }
                    },
                    error: function(a, b, c) {
                        swal_alert("Error", a.responseText, "error");
                    }
                })
            }
        });
    });

    $(document).on('click', '.btn-dis', function() {
        const id_server = $("#id_server").val();
        const id_hotspot = $(this).attr("data-i");
        const status = $(this).attr("data-dis");
        $.ajax({
            type: "POST",
            url: "<?= base_url("update_status_hotspot") ?>",
            data: {
                id_server: id_server,
                id_hotspot: id_hotspot,
                status: status,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType: "JSON",
            beforeSend: function() {
                show_loading("Loading...", "Mohon tunggu sebentar");
            },
            success: function(r) {
                d = JSON.parse(JSON.stringify(r));
                swal_alert(d.title, d.pesan, d.icon);
                if (d.icon == "success") {
                    $("#status-" + id_hotspot).html(d.icon_disable);
                    if (status == "0") {
                        $("#btn-dis-" + id_hotspot).attr("data-dis", "1");
                        $("#btn-dis-" + id_hotspot).html("Enable");
                    } else {
                        $("#btn-dis-" + id_hotspot).attr("data-dis", "0");
                        $("#btn-dis-" + id_hotspot).html("Disable");
                    }
                }
            },
            error: function(a, b, c) {
                swal_alert("Error", a.responseText, "error");
            }
        });
    });

    function simpan_edit() {
        const id_hotspot = $("#action").attr("data-i");
        const id_server = $("#id_server").val();
        const username = $("#name").val();
        const password = $("#password").val();
        $.ajax({
            type: "post",
            url: "<?= base_url("simpan_edit_hotspot") ?>",
            data: {
                id_hotspot: id_hotspot,
                id_server: id_server,
                username:username,
                password: password,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType: "JSON",
            beforeSend: function() {
                btn_loading();
            },
            success: function(r) {
                d = JSON.parse(JSON.stringify(r));
                swal.fire({
                    title: d.title,
                    html: d.pesan,
                    icon: d.icon,
                });

                if (d.icon == "success") {
                    $("#form_user").modal("toggle");
                    $("#password-" + id_hotspot).html(password);
                    $("#btn-edit-" + id_hotspot).attr("data-password", password);
                }
                btn_finish();
            },
            error: function(a, b, c) {
                swal.fire("Error", a.responseText, "error");
                btn_finish();
            }
        })
    }

    $("#action").click(function() {
        simpan_edit();
    })

    function btn_loading() {
        $("#action").html("Menyimpan...");
        $("#action").attr("disabled", true);
    }

    function btn_finish() {
        $("#action").html("Simpan");
        $("#action").attr("disabled", false);
    }

    var loading = '<i class="fas fa-spinner fa-pulse text-info m-2"></i>';
    var checked = '<i class="fas fa-check-circle text-success m-2"></i>';
    var failed = '<i class="fas fa-times-circle text-danger m-2"></i>';

    function check_username() {
        const id_pppoe = $("#action").attr("data-i");
        const username = $("#name").val();
        const id_server = $("#id_server").val();
        $.ajax({
            type: 'post',
            url: '<?= base_url("check_username_pppoe") ?>',
            data: {
                id_pppoe: id_pppoe,
                id_server: id_server,
                username: username,
				<?= "'".$this->name_token."':'".$this->token."'"; ?>
            },
			timeout:0,
            dataType: "JSON",
            beforeSend: function() {
                $("#check-username").html(loading);
            },
            success: function(r) {
                d = JSON.parse(JSON.stringify(r));
                if (d.status == "ok") {
                    $("#check-username").html(checked);
                } else {
                    if (d.icon) {
                        if (username) {
                            swal.fire({
                                title: d.title,
                                html: d.pesan,
                                icon: d.icon
                            });
                        }
                        $("#check-username").html("");
                    } else {
                        $("#check-username").html(failed);
                    }
                }
                console.log(r);
            },
            error: function(a, b, c) {
                swal.fire("Error", a.responseText, "error");
            }
        })
    }
</script>
