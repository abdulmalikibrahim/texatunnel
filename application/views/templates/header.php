<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords"
        content="Texa Tunnel, Texa ID, Texa Indonesia, Texa, Management PPPoE, Management Wifi, Management Hotspot, Hotspot, PPPoE, VPN Remote, Mikrotik">
    <meta name="description"
        content="Texa Tunnel is application management VPN, PPPoE & Hotspot">
    <meta name="robots" content="noindex,nofollow">
    <title><?= $nzm; ?></title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?= base_url("assets/skydash/vendors/feather/feather.css"); ?>">
    <link rel="stylesheet" href="<?= base_url("assets/skydash/vendors/ti-icons/css/themify-icons.css"); ?>">
    <link rel="stylesheet" href="<?= base_url("assets/skydash/vendors/css/vendor.bundle.base.css"); ?>">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" type="text/css" href="<?= base_url("assets/skydash/js/select.dataTables.min.css"); ?>">
    <link rel="shortcut icon" href="<?= base_url("assets/img/favicon.ico") ?>">
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="<?= base_url("assets/skydash/css/vertical-layout-light/style.css"); ?>">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/fh-3.2.4/r-2.3.0/datatables.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.5/sweetalert2.css"/>

    <?php
    if($this->p1 == "account"){
        echo '<link href="'.base_url('assets/css/custom.css').'" rel="stylesheet">';
    }
    // if($this->p2 == "isi_saldo"){
    //     $check_pg = $this->model->gd("payment_gateway","*","is_active = '1'","row");
    //     if(!empty($check_pg)){
    //         if($check_pg->id == "MD"){
    //             if($check_pg->status == "Sandbox"){
    //                 $url = 'https://app.sandbox.midtrans.com/snap/snap.js';
    //                 $client_key = $check_pg->client_key_sand;
    //             }else{
    //                 $url = 'https://app.midtrans.com/snap/snap.js';
    //                 $client_key = $check_pg->client_key_prod;
    //             }
    //             echo '
    //             <script type="text/javascript" src="'.$url.'" data-client-key="'.$client_key.'"></script>
    //             <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>';
    //         }
    //     }
    // }
    ?>
    <!-- <style>
        .swal2-success-circular-line-right, .swal2-success-circular-line-left, .swal2-success-fix{
            background-color:rgba(0,0,0,0) !important;
        }
    </style> -->
</head>
