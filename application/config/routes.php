<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//DASHBOARD
$route['get_traffic'] = 'Dashboard/get_traffic';

//VPN MASTER
$route['list_vpn'] = 'Admin/list_vpn';
$route['list_vpn/form_vpn/(:any)'] = 'Admin/form_vpn/$1';
$route['save_vpn_master/(:num)'] = 'VPN/save_vpn_master/$1';
$route['check_username_vpn'] = 'VPN/check_username_vpn';

//PAYMENT MASTER
$route['list_payment'] = 'Admin/list_payment';
$route['list_payment/form_payment/(:num)'] = 'Admin/form_payment/$1';
$route['save_payment/(:num)'] = 'Payment/save_payment/$1';

//PAYMENT GATEWAY
$route['payment_gateway'] = 'Admin/payment_gateway';
$route['save_pg_md'] = 'Payment_Gateway/save/md';
$route['snap_md/(:any)'] = 'Payment_Gateway/snap/md/$1';
$route['notification_md'] = 'Payment_Gateway/notification/md';

$route['list_order_vpn'] = 'Admin/list_order_vpn';
$route['list_order_vpn/manage_vpn/(:any)'] = 'Admin/manage_vpn/$1';
$route['edit_vpn/(:any)'] = 'VPN/edit_vpn/$1';
$route['delvpn/(:any)'] = 'VPN/delete_vpn/$1';

$route['order_vpn'] = 'Admin/order_vpn';
$route['simpan_order_vpn'] = 'VPN/simpan_order_vpn';

$route['check_status_vpn'] = 'VPN/check_status_vpn';

//ACCOUNT
$route['account'] = 'Admin/account_setting';
$route['editing_account'] = 'Account/editing_account';

//SALDO
$route['saldo/isi_saldo'] = 'Admin/isi_saldo';
$route['saldo/pending'] = 'Admin/list_topup/pending';
$route['saldo/success'] = 'Admin/list_topup/success';
$route['saldo/cancel'] = 'Admin/list_topup/cancel';
$route['saldo/history_topup'] = 'Admin/history_topup';
$route['saldo/konfirmasi'] = 'Admin/konfirmasi_saldo';
$route['topup_saldo'] = 'Saldo/topup_saldo';
$route['cancel_topup/(:any)'] = 'Saldo/cancel_topup/$1';
$route['konfirmasi_saldo/(:any)/(:any)/(:any)'] = 'Saldo/konfirmasi_saldo/$1/$2/$3';
$route['get_update_tele'] = 'Saldo/get_update_tele';
$route['check_saldo'] = 'Saldo/check_saldo';

//USER
$route['user_list'] = 'Admin/list_user';
$route['user/add/(:any)'] = 'Admin/user_add/$1';
$route['user/edit/(:any)'] = 'Admin/user_edit/$1';
$route['user/delete/(:any)'] = 'User/delete/$1';
$route['save_user/(:any)'] = 'User/save_user/$1';

//ROUTEROS
$route['routeros/(:any)'] = 'Admin/routeros/$1';
$route['routeros_list'] = 'Admin/routeros/list';
$route['test_routeros/(:any)'] = 'Routeros/test/$1';
$route['save_routeros/(:any)'] = 'Routeros/save/$1';
$route['checking_routeros'] = 'Routeros/checking_routeros';

//REGISTRATION
$route['activation/(:any)'] = 'Auth/activation/$1';

//FORGET PASSWORD
$route['forget_password'] = 'Auth/forget_password';
$route['fpass'] = 'Auth/fpass';

//PPPoE
$route['setting_pppoe'] = 'Admin/setting_pppoe';
$route['order_pppoe'] = 'Admin/order_pppoe';
$route["list_order_pppoe"] = 'Admin/list_order_pppoe';
$route["data_interface"] = 'PPPoE/data_interface';
$route["simpan_pppoe_servers/(:any)"] = 'PPPoE/simpan_pppoe_servers/$1';
$route["delete_pppoe_servers/(:any)"] = 'PPPoE/delete_pppoe_servers/$1';
$route["list_pppoe_servers/(:any)"] = 'PPPoE/list_pppoe_servers/$1';
$route["ip_pool/list/(:any)"] = 'PPPoE/list_ip_pool/$1';
$route["simpan_ip_pool/(:any)"] = 'PPPoE/simpan_ip_pool/$1';
$route["ip_pool/remove/(:any)"] = 'PPPoE/remove_ip_pool/$1';
$route["profile_pppoe/list/(:any)"] = 'PPPoE/list_profile_pppoe/$1';
$route["simpan_profile_pppoe/(:any)"] = 'PPPoE/simpan_profile_pppoe/$1';
$route["profile_pppoe/remove/(:any)"] = 'PPPoE/remove_profile_pppoe/$1';
$route["get_paket_pppoe"] = 'PPPoE/get_paket';
$route["check_username_pppoe"] = 'PPPoE/check_username';
$route["simpan_order_pppoe"] = 'PPPoE/simpan_order';
$route["get_list_order_pppoe"] = 'PPPoE/list_order';
$route["simpan_edit_pppoe"] = 'PPPoE/simpan_edit';
$route["hapus_order_pppoe"] = 'PPPoE/hapus_order';
$route["update_status_pppoe"] = 'PPPoE/update_status';
$route["rubah_paket_pppoe"] = 'PPPoE/rubah_paket';
$route["check_status_pppoe"] = 'PPPoE/check_status';

//HOTSPOT
$route['setting_hotspot'] = 'Admin/setting_hotspot';
$route['order_hotspot'] = 'Admin/order_hotspot';
$route["user_order_hotspot"] = 'Admin/user_order_hotspot';
$route["voucher_hotspot"] = 'Admin/voucher_hotspot';
$route["setup_hotspot_edit"] = 'Hotspot/setup_edit';
$route["ip_address_hotspot"] = 'Hotspot/ip_address';
$route["data_interface_hotspot"] = 'Hotspot/data_interface';
$route["element_ip_pool_hotspot"] = 'Hotspot/element_ip_pool';
$route["simpan_hotspot_servers/(:any)"] = 'Hotspot/simpan_hotspot_servers/$1';
$route["delete_hotspot_servers/(:any)"] = 'Hotspot/delete_hotspot_servers/$1';
$route["list_hotspot_servers/(:any)"] = 'Hotspot/list_hotspot_servers/$1';
$route["ip_pool_hotspot/list/(:any)"] = 'Hotspot/list_ip_pool/$1';
$route["simpan_order_hotspot"] = 'Hotspot/simpan_order';
$route["get_list_order_hotspot"] = 'Hotspot/list_order';
$route["simpan_edit_hotspot"] = 'Hotspot/simpan_edit';
$route["hapus_order"] = 'Hotspot/hapus_order';
$route["update_status_hotspot"] = 'Hotspot/update_status';
$route["rubah_paket_hotspot"] = 'Hotspot/rubah_paket';
$route["check_status_hotspot"] = 'Hotspot/check_status';
$route["list_paket_hotspot/(:any)"] = 'Hotspot/paket_hotspot/list/$1';
$route["save_paket_hotspot/(:any)"] = 'Hotspot/paket_hotspot/save/$1';
$route["remove_paket_hotspot/(:any)"] = 'Hotspot/paket_hotspot/remove/$1';
$route["list_profile_hotspot/(:any)"] = 'Hotspot/profile_hotspot/list/$1';
$route["save_profile_hotspot/(:any)"] = 'Hotspot/profile_hotspot/save/$1';
$route["remove_profile_hotspot/(:any)"] = 'Hotspot/profile_hotspot/remove/$1';

//SARAN KRITIK
$route['saran_kritik'] = 'Admin/saran_kritik';
$route['submit_saran_kritik'] = 'Saran_Kritik/submit_saran_kritik';

$route["warobot"] = 'Api_WA/warobot';

//OLT
$route['olt/setting'] = 'Admin/setting_olt';
$route['olt/onu_type'] = 'Admin/onu_type_olt';
$route['olt/zone'] = 'Admin/zone_olt';
$route['olt/onu'] = 'Admin/onu_olt';
$route['get_list_olt'] = 'OLT/get_list_olt';
$route['add_onu_type'] = 'OLT/add_onu_type';
$route['get_onu_type_list'] = 'OLT/get_onu_type_list';
$route['add_zone'] = 'OLT/add_zone';
$route['get_zone_list'] = 'OLT/get_zone_list';
$route['get_onu_list'] = 'OLT/get_onu_list';
$route['add_onu'] = 'OLT/add_onu';
$route['trial_snmp'] = 'OLT/trial_snmp';
