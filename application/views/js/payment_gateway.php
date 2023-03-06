<script>
    function change_is_active() {
        if($("#is_active").prop("checked") == true){
            $("#is_active").val("Aktif");
            $(".custom-control-label").html("Aktif");
        }else{
            $("#is_active").val("Non Aktif");
            $(".custom-control-label").html("Non Aktif");
        }
    }
    change_is_active();

    function check_status() {
        status = $("#status").val();
        if(status == "Sandbox"){
            $(".md-production").hide();
            $(".md-sandbox").show();
            $(".text-sand").attr("required",true);
            $(".text-prod").attr("required",false);
        }else{
            $(".md-production").show();
            $(".md-sandbox").hide();
            $(".text-sand").attr("required",false);
            $(".text-prod").attr("required",true);
        }
    }
    check_status();
</script>