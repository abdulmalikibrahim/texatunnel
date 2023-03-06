<?php
defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jakarta');

class Dashboard extends MY_Controller
{
    public function get_traffic()
    {
        $id_server = d_nzm($this->input->get("id_server"));
        $interface = $this->input->get("interface");
        $data_router = [
            "id" => $id_server,
            "proses" => "/interface/monitor-traffic",
            "data" => array(
                "interface" => $interface,
                "once" => "",
            )
        ];

        $getinterfacetraffic = $this->router_process("router",$data_router);
        if(!empty($getinterfacetraffic)){
            $rows = array();
            $rows2 = array();

            $ftx = $getinterfacetraffic[0]['tx-bits-per-second'];
            $frx = $getinterfacetraffic[0]['rx-bits-per-second'];

            $rows['name'] = 'Tx';
            $rows['data'][] = $ftx;
            $rows2['name'] = 'Rx';
            $rows2['data'][] = $frx;
        }else{
            $rows['name'] = 'Tx';
            $rows['data'][] = "Disconnect";
            $rows2['name'] = 'Rx';
            $rows2['data'][] = "Disconnect";
        }

        $result = array();
        
        array_push($result, $rows);
        array_push($result, $rows2);
        // echo json_encode($interface.$id_server);
        echo json_encode($result);
        die();
    }
}
