<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/fh-3.2.4/r-2.3.0/datatables.min.js"></script>
<!-- <script src="<?= base_url("assets/js/highcharts/highcharts.js?v=1") ?>"></script>
 -->
<script src="https://github.highcharts.com/v5.0.9/highcharts.js"></script>
<script>
    $("#datatable").DataTable({
        responsive: true,
    });
    function check_interface() {
        if ($("#server").find(":selected").val()) {
            $.ajax({
                type: 'get',
                url: '<?= base_url("data_interface_hotspot") ?>',
                data: {
                    id: $("#server").find(":selected").val(),
					<?= "'".$this->name_token."':'".$this->token."'"; ?>
                },
				timeout:0,
                beforeSend: function() {
                    $("#interface").html("<option>Sedang Memuat...</option>");
                },
                success: function(r) {
                    $("#interface").html('<option value="" disabled>- Pilih Interface -</option>' + r);
                    requestDatta();
                },
                error: function(a, b, c) {
                    $("#interface").html("<option>Error</option>");
                    $("#interface_print").html(a.responseText);
                }
            });
            $("#graph-chart").html('');
            $("#graph").show();
        }else{
            $("#graph").hide();
            $("#graph-chart").html('<center>Server Belum Dipilih</center>');
            $("#tabletx").html("-");
            $("#tablerx").html("-");
        }
    }
    check_interface();

    $("#server").change(function() {
        if($("#server").find(":selected").val() == "Tambah Server"){
            window.location.href = '<?= base_url('routeros/0'); ?>';
        }else{
            check_interface();
            console.log($("#server").find(":selected").val()+"Here");
        }
    });

    var chart;

    function requestDatta() {
        if($("#server").find(":selected").val()){
            $.ajax({
                type: "get",
                url: '<?= base_url("get_traffic") ?>',
                data: {
                    interface: $("#interface").find(":selected").val(),
                    id_server: $("#server").find(":selected").val(),
					<?= "'".$this->name_token."':'".$this->token."'"; ?>
                },
				timeout:0,
                datatype: "json",
                success: function(data) {
                    var midata = JSON.parse(data);
                    console.log(data);
                    console.log(midata);
                    if (midata.length > 0) {
                        var TX = parseInt(midata[0].data);
                        var RX = parseInt(midata[1].data);
                        var x = (new Date()).getTime();
                        shift = chart.series[0].data.length > 19;
                        chart.series[0].addPoint([x, TX], true, shift);
                        chart.series[1].addPoint([x, RX], true, shift);
                        if (midata[0].data == "Disconnect") {
                            document.getElementById("tabletx").innerHTML = "<span class='text-danger'>Disconnect</span>";
                        } else {
                            document.getElementById("tabletx").innerHTML = convert(TX);
                        }

                        if (midata[1].data == "Disconnect") {
                            document.getElementById("tablerx").innerHTML = "<span class='text-danger'>Disconnect</span>";
                        }else{
                            document.getElementById("tablerx").innerHTML = convert(RX);
                        }
                    } else {
                        document.getElementById("tabletx").innerHTML = "0";
                        document.getElementById("tablerx").innerHTML = "0";
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.error("Status: " + textStatus + " request: " + XMLHttpRequest);
                    console.error("Error: " + errorThrown);
                }
            });
            $("#graph-chart").html('');
            $("#graph").show();
        }else{
            $("#graph").hide();
            $("#graph-chart").html('<center>Server Belum Dipilih</center>');
            $("#tabletx").html("-");
            $("#tablerx").html("-");
        }
    }

    $(document).ready(function() {
        Highcharts.setOptions({
            global: {
                useUTC: false
            }
        });


        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'graph',
                animation: Highcharts.svg,
                type: 'spline',
                events: {
                    load: function() {
                        setInterval(function() {
                            requestDatta(document.getElementById("interface").value);
                        }, 5000);
                    }
                }
            },
            title: {
                text: ''
            },
            xAxis: {
                type: 'datetime',
                tickPixelInterval: 150,
                maxZoom: 20 * 1000
            },

            yAxis: {
                minPadding: 0.2,
                maxPadding: 0.2,
                title: {
                    text: 'Traffic'
                },
                labels: {
                    formatter: function() {
                        var bytes = this.value;
                        var sizes = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps'];
                        if (bytes == 0) return '0 bps';
                        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
                        return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i];
                    },
                },
            },
            series: [{
                name: 'TX',
                data: []
            }, {
                name: 'RX',
                data: []
            }],
            tooltip: {
                headerFormat: '<b>{series.name}</b><br/>',
                pointFormat: '{point.x:%Y-%m-%d %H:%M:%S}<br/>{point.y}'
            },


        });
    });

    function convert(bytes) {
        var sizes = ['bps', 'kbps', 'Mbps', 'Gbps', 'Tbps'];
        if (bytes == 0) return '0 bps';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return parseFloat((bytes / Math.pow(1024, i)).toFixed(2)) + ' ' + sizes[i];
    }
</script>
