-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2023 at 04:57 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `texa_tunnel`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_routeros`
--

CREATE TABLE `api_routeros` (
  `id` int(11) NOT NULL,
  `id_mitra` int(11) NOT NULL,
  `id_server` varchar(25) DEFAULT NULL,
  `nama_server` varchar(100) DEFAULT NULL,
  `ip_address` text,
  `port` int(11) DEFAULT NULL,
  `username` text,
  `password` text,
  `country` varchar(100) DEFAULT NULL,
  `is_active` enum('0','1','','') DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `api_routeros`
--

INSERT INTO `api_routeros` (`id`, `id_mitra`, `id_server`, `nama_server`, `ip_address`, `port`, `username`, `password`, `country`, `is_active`) VALUES
(1, 3, '230206113314', 'Mikrotik', '103.13.206.221', 0, '6841774d614b44486c657a592f776b436954377330513d3d', '59757a487339383946447054446e78373743415735773d3d', 'Indonesia', '1'),
(2, 3, '230228101725', 'Server 2', '103.13.206.221', 80, '65524e4369386e2f2f747538754a5a7a61325a4f70513d3d', '65524e4369386e2f2f747538754a5a7a61325a4f70513d3d', 'Indonesia', '1'),
(3, 3, '230303125153', 'Mikrotik', '192.168.1.180', 0, '6841774d614b44486c657a592f776b436954377330513d3d', '6841774d614b44486c657a592f776b436954377330513d3d', 'Indonesia', '1');

-- --------------------------------------------------------

--
-- Table structure for table `data_order`
--

CREATE TABLE `data_order` (
  `id` int(11) NOT NULL,
  `id_mitra` int(11) NOT NULL,
  `deleted_date` date DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nomor_order` int(11) DEFAULT NULL,
  `id_server` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` text,
  `port` int(11) DEFAULT NULL,
  `tanggal_order` date DEFAULT NULL,
  `berlangganan` int(11) DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `harga_beli` int(11) DEFAULT NULL,
  `status` enum('Aktif','Non Aktif','','') DEFAULT NULL,
  `status_debit` enum('0','1') DEFAULT '0',
  `remote_address` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `log_activity_user`
--

CREATE TABLE `log_activity_user` (
  `id` int(11) NOT NULL,
  `id_mitra` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `logs` text,
  `category` enum('Create','Update','Delete') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `log_activity_user`
--

INSERT INTO `log_activity_user` (`id`, `id_mitra`, `id_user`, `tanggal`, `logs`, `category`) VALUES
(1, 3, 3, NULL, 'Isi Saldo 0', 'Create'),
(2, 3, 3, '2022-12-21 23:06:40', 'Isi Saldo 100.000', 'Create'),
(3, 3, 3, '2022-12-21 23:06:40', 'Isi Saldo 100.000', 'Create'),
(4, 3, 3, '2022-12-21 23:06:40', 'Isi Saldo 100.000', 'Create'),
(5, 3, 3, '2022-12-21 23:45:58', 'Isi Saldo 10.000', 'Create'),
(6, 3, 3, '2022-12-21 23:47:48', 'Isi Saldo 20.000', 'Create'),
(7, 3, 3, '2022-12-21 16:03:56', 'Isi Saldo 50.000', 'Create'),
(8, 3, 3, '2022-12-21 16:30:08', 'Isi Saldo 100.000', 'Create'),
(9, 3, 3, '2022-12-21 16:30:08', 'Isi Saldo 100.000', 'Create'),
(10, 3, 3, '2022-12-21 16:02:55', 'Isi Saldo 50.000', 'Create'),
(11, 3, 3, '2022-12-21 16:02:55', 'Isi Saldo 50.000', 'Create'),
(12, 3, 3, '2022-12-21 16:02:55', 'Isi Saldo 50.000', 'Create'),
(13, 3, 3, '2022-12-22 00:15:12', 'Isi Saldo 25.000', 'Create'),
(14, 3, 3, '2022-12-22 00:20:49', 'Isi Saldo 30.000', 'Create'),
(15, 3, 3, '2022-12-22 00:20:49', 'Isi Saldo 30.000', 'Create'),
(16, 3, 3, '2022-12-22 00:20:49', 'Isi Saldo 30.000', 'Create'),
(17, 3, 3, '2022-12-22 00:20:49', 'Isi Saldo 30.000', 'Create'),
(18, 3, 3, '2022-12-22 00:20:49', 'Isi Saldo 30.000', 'Create'),
(19, 3, 3, '2022-12-22 00:37:52', 'Isi Saldo 50.000', 'Create'),
(20, 3, 3, '2022-12-22 00:37:52', 'Isi Saldo 50.000', 'Create'),
(21, 3, 3, '2022-12-22 00:37:52', 'Isi Saldo 50.000', 'Create'),
(22, 3, 3, '2022-12-22 00:13:20', 'Isi Saldo 25.000', 'Create'),
(23, 3, 3, '2022-12-22 00:42:43', 'Isi Saldo 10.000', 'Create'),
(24, 3, 3, '2022-12-22 00:42:43', 'Isi Saldo 10.000', 'Create'),
(25, 3, 3, '2022-12-22 00:42:43', 'Isi Saldo 10.000', 'Create'),
(26, 3, 3, '2022-12-22 00:42:43', 'Isi Saldo 10.000', 'Create'),
(27, 3, 3, '2022-12-22 11:19:42', 'Cancel Top Up Saldo 10.000', 'Create'),
(28, 3, 3, '2022-12-22 11:21:43', 'Cancel Top Up Saldo 10.000', 'Create'),
(29, 3, 3, '2022-12-22 11:26:43', 'Isi Saldo 10.000', 'Create'),
(30, 3, 3, '2022-12-22 11:32:14', 'Isi Saldo 10.000', 'Create'),
(31, 3, 3, '2022-12-22 11:33:59', 'Isi Saldo 10.000', 'Create'),
(32, 3, 3, '2022-12-22 11:34:39', 'Isi Saldo 12.000', 'Create'),
(33, 3, 3, '2022-12-22 11:34:57', 'Isi Saldo 11.000', 'Create'),
(34, 3, 3, '2022-12-22 11:35:38', 'Isi Saldo 20.000', 'Create'),
(35, 3, 3, '2022-12-22 11:55:47', 'Isi Saldo 10.000', 'Create'),
(36, 3, 3, '2022-12-26 10:32:25', 'Membuat VPN Remote Baru test1@texa.net', 'Create'),
(37, 3, 3, '2022-12-29 10:54:40', 'Order PPPoE test2 berlangganan selama 1', 'Create'),
(38, 3, 3, '2022-12-29 10:56:44', 'Edit PPPoE test1', 'Update'),
(39, 3, 3, '2022-12-29 10:58:46', 'Edit PPPoE malik1', 'Update'),
(40, 3, 3, '2022-12-29 10:59:22', 'Edit PPPoE malik11', 'Update'),
(41, 3, 3, '2022-12-29 11:00:28', 'Hapus PPPoE test1', 'Delete'),
(42, 3, 3, '2022-12-30 07:59:42', 'Perpanjangan PPPoE malik', 'Update'),
(43, 3, 3, '2022-12-30 08:02:22', 'Perpanjangan PPPoE malik11', 'Update'),
(44, 3, 3, '2023-01-05 16:07:12', 'Order Hotspot admin paket Paket-1Menit-Hotspot', 'Create'),
(45, 3, 3, '2023-01-05 16:10:58', 'Order Hotspot user1 paket Paket-1Menit-Hotspot', 'Create'),
(46, 3, 3, '2023-01-05 16:12:31', 'Order Hotspot admin paket Paket-1Menit-Hotspot', 'Create'),
(47, 3, 3, '2023-01-05 16:13:12', 'Order Hotspot user1 paket Paket-1Menit-Hotspot', 'Create'),
(48, 3, 3, '2023-01-05 16:18:45', 'Order Hotspot admin paket Paket-1Menit-Hotspot', 'Create'),
(49, 3, 3, '2023-01-05 16:19:04', 'Order Hotspot admin paket Paket-1Menit-Hotspot', 'Create'),
(50, 3, 3, '2023-01-06 16:09:27', 'Order Hotspot admin paket Paket-Pertalite-Hotspot', 'Create'),
(51, 3, 3, '2023-01-06 16:25:35', 'Order Hotspot admin paket Paket-Kecil-Hotspot', 'Create'),
(52, 3, 3, '2023-01-06 19:42:39', 'Order Hotspot admin paket Paket-Kecil-Hotspot', 'Create'),
(53, 3, 3, '2023-01-06 19:42:54', 'Disabled Hotspot admin', 'Update'),
(54, 3, 3, '2023-01-06 19:42:58', 'Disabled Hotspot admin', 'Update'),
(55, 3, 3, '2023-01-06 19:44:10', 'Enabled Hotspot admin', 'Update'),
(56, 3, 3, '2023-01-06 19:44:15', 'Disabled Hotspot admin', 'Update'),
(57, 3, 3, '2023-01-06 19:44:20', 'Disabled Hotspot admin', 'Update'),
(58, 3, 3, '2023-01-06 19:45:05', 'Enabled Hotspot admin', 'Update'),
(59, 3, 3, '2023-01-06 19:45:15', 'Disabled Hotspot admin', 'Update'),
(60, 3, 3, '2023-01-06 19:45:19', 'Enabled Hotspot admin', 'Update'),
(61, 3, 3, '2023-01-06 19:57:50', 'Edit Password Hotspot admin1  at Username : admin', 'Update'),
(62, 3, 3, '2023-01-06 19:58:20', 'Edit Password Hotspot admin  at Username : admin', 'Update'),
(63, 3, 3, '2023-01-06 20:01:31', 'Hapus Hotspot User admin', 'Delete'),
(64, 3, 3, '2023-01-06 22:04:44', 'Order PPPoE admin berlangganan selama 1', 'Create'),
(65, 3, 3, '2023-01-06 22:08:30', 'Order PPPoE malik berlangganan selama 1', 'Create'),
(66, 3, 3, '2023-01-06 22:10:39', 'Hapus PPPoE malik', 'Delete'),
(67, 3, 3, '2023-01-06 22:10:47', 'Disabled PPPoE admin', 'Update'),
(68, 3, 3, '2023-01-06 22:10:55', 'Enabled PPPoE admin', 'Update'),
(69, 3, 3, '2023-01-06 22:14:35', 'Order PPPoE malik berlangganan selama 1', 'Create'),
(70, 3, 3, '2023-01-06 22:14:56', 'Disabled PPPoE malik', 'Update'),
(71, 3, 3, '2023-01-06 22:15:01', 'Enabled PPPoE malik', 'Update'),
(72, 3, 3, '2023-01-06 22:15:13', 'Hapus PPPoE malik', 'Delete'),
(73, 3, 3, '2023-01-06 22:15:37', 'Order PPPoE malik berlangganan selama 1', 'Create'),
(74, 3, 3, '2023-01-06 22:16:11', 'Order Hotspot admin paket Paket-4Jam-Hotspot', 'Create'),
(75, 3, 3, '2023-01-06 22:17:05', 'Edit Password Hotspot admin1  at Username : admin', 'Update'),
(76, 3, 3, '2023-01-06 22:17:10', 'Edit Password Hotspot admin  at Username : admin', 'Update'),
(77, 3, 3, '2023-01-06 22:17:14', 'Disabled Hotspot admin', 'Update'),
(78, 3, 3, '2023-01-06 22:17:20', 'Enabled Hotspot admin', 'Update'),
(79, 3, 3, '2023-01-27 23:59:14', 'Membuat VPN Remote Baru admin@Texa.net', 'Create'),
(80, 3, 3, '2023-01-28 00:10:10', 'Order PPPoE malik berlangganan selama 1', 'Create');

-- --------------------------------------------------------

--
-- Table structure for table `payment_gateway`
--

CREATE TABLE `payment_gateway` (
  `id` int(11) NOT NULL,
  `id_mitra` int(11) NOT NULL,
  `gateway` varchar(100) DEFAULT NULL,
  `id_merchant_sand` varchar(100) DEFAULT NULL,
  `client_key_sand` text,
  `server_key_sand` text,
  `id_merchant_prod` varchar(100) DEFAULT NULL,
  `client_key_prod` text,
  `server_key_prod` text,
  `status` enum('Sandbox','Production') DEFAULT 'Sandbox',
  `is_active` int(11) DEFAULT '0',
  `biaya_penanganan` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_gateway`
--

INSERT INTO `payment_gateway` (`id`, `id_mitra`, `gateway`, `id_merchant_sand`, `client_key_sand`, `server_key_sand`, `id_merchant_prod`, `client_key_prod`, `server_key_prod`, `status`, `is_active`, `biaya_penanganan`) VALUES
(1, 3, 'Midtrans', 'M266235', 'SB-Mid-client-lv4BDnHH626y9UqV', 'SB-Mid-server-2k_AgSTEyI4E-y6iZKiV4yAf', NULL, NULL, NULL, 'Sandbox', 1, 0),
(2, 3, 'Midtrans', 'M266235', 'SB-Mid-client-lv4BDnHH626y9UqV', 'SB-Mid-server-2k_AgSTEyI4E-y6iZKiV4yAf', NULL, NULL, NULL, 'Sandbox', 0, 0),
(3, 3, 'Midtrans', 'M266235', 'SB-Mid-client-lv4BDnHH626y9UqV', 'SB-Mid-server-2k_AgSTEyI4E-y6iZKiV4yAf', NULL, NULL, NULL, 'Sandbox', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(11) NOT NULL,
  `id_mitra` int(11) NOT NULL,
  `payment` varchar(100) DEFAULT NULL,
  `nomor_payment` varchar(25) DEFAULT NULL,
  `an` text,
  `status` enum('Aktif','Non Aktif') DEFAULT 'Aktif'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`id`, `id_mitra`, `payment`, `nomor_payment`, `an`, `status`) VALUES
(1, 3, 'Shopee Pay', '089674618300', 'Tulussubakti27', 'Aktif'),
(2, 3, 'OVO', '089674618300', 'Mukroni', 'Aktif'),
(3, 3, 'DANA', '089674618300', 'Mukroni', 'Aktif'),
(4, 3, 'Link Aja', '089674618300', 'Mukroni', 'Aktif'),
(5, 3, 'Transfer BRI', '390301018370539', 'Mukroni', 'Aktif');

-- --------------------------------------------------------

--
-- Table structure for table `saran_kritik`
--

CREATE TABLE `saran_kritik` (
  `id` int(11) NOT NULL,
  `date_submit` datetime DEFAULT NULL,
  `id_pengirim` int(11) DEFAULT NULL,
  `dari` varchar(30) DEFAULT NULL,
  `saran_kritik` longtext,
  `status` enum('CLOSE','OPEN') DEFAULT 'OPEN'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `saran_kritik`
--

INSERT INTO `saran_kritik` (`id`, `date_submit`, `id_pengirim`, `dari`, `saran_kritik`, `status`) VALUES
(1, '2023-02-12 13:47:46', 3, 'tulus', 'asdasfasdafasd \' &quot; ; &quot; &amp;lt;&gt; ? * $#@$!#@*)%E_W*_$&amp;_W)(#&amp;_', 'OPEN'),
(2, '2023-02-12 13:53:48', 3, 'tulus', 'asdasfasd', 'OPEN'),
(3, '2023-02-12 13:54:34', 3, 'tulus', 'asdsd', 'OPEN'),
(4, '2023-02-12 14:04:24', 3, 'tulus', 'asdasdasfa', 'OPEN'),
(5, '2023-02-27 14:04:03', 3, 'tulus', 'asdasdasd', 'OPEN'),
(6, '2023-02-27 14:04:16', 3, 'tulus', 'asdasd', 'OPEN');

-- --------------------------------------------------------

--
-- Table structure for table `top_up`
--

CREATE TABLE `top_up` (
  `id` int(11) NOT NULL,
  `id_mitra` int(11) NOT NULL,
  `order_id` varchar(30) DEFAULT NULL,
  `tanggal` datetime DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nominal` int(11) DEFAULT NULL,
  `jenis_pembayaran` varchar(150) DEFAULT NULL,
  `nomor_tujuan` varchar(100) DEFAULT NULL,
  `an` varchar(100) DEFAULT NULL,
  `status` enum('Sukses','Cancel','Pending','Belum Bayar') DEFAULT 'Belum Bayar'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `top_up`
--

INSERT INTO `top_up` (`id`, `id_mitra`, `order_id`, `tanggal`, `id_user`, `nominal`, `jenis_pembayaran`, `nomor_tujuan`, `an`, `status`) VALUES
(1, 3, '719467467', '2022-12-21 16:29:34', 3, 50000, 'Bank Transfer BCA', '266235584591', 'MIDTRANS', 'Sukses'),
(2, 3, '1044866460', '2022-12-21 16:32:21', 3, 100000, 'Bank Transfer BCA', '266235942590', 'MIDTRANS', 'Sukses'),
(3, 3, '1750099903', '2022-12-21 16:33:57', 3, 100000, 'Bank Transfer BCA', '266235057294', 'MIDTRANS', 'Sukses'),
(4, 3, '2098044162', '2022-12-21 16:35:41', 3, 100000, 'Cstore indomaret', '98037708763253', 'MIDTRANS', 'Cancel'),
(5, 3, '63a32ef8d3197', '2022-12-21 23:06:17', 3, 100000, 'Echannel Mandiri', '485887751412', 'MIDTRANS', 'Sukses'),
(6, 3, '63a338084e441', '2022-12-21 23:44:56', 3, 10000, 'Bank Transfer BNI', '9886623518720752', 'MIDTRANS', 'Sukses'),
(7, 3, '63a338a9436d2', '2022-12-21 23:47:37', 3, 20000, 'Cstore ', NULL, 'MIDTRANS', 'Sukses'),
(8, 3, '63a33cb109915', '2022-12-22 00:20:32', 3, 30000, 'Bank Transfer PERMATA', '662004559354879', 'MIDTRANS', 'Sukses'),
(9, 3, '63a33e957d45c', '2022-12-22 00:12:54', 3, 25000, 'Bank Transfer PERMATA', '662005144810286', 'MIDTRANS', 'Sukses'),
(10, 3, '63a33f086ac24', '2022-12-22 00:37:46', 3, 50000, 'Bca Klikpay ', '', 'MIDTRANS', 'Sukses'),
(11, 3, '63a33f181c86f', '2022-12-22 00:15:04', 3, 25000, 'Bank Transfer ', NULL, 'MIDTRANS', 'Sukses'),
(12, 3, '63a3456f38fc5', '2022-12-22 00:42:07', 3, 10000, 'Akulaku ', '', 'MIDTRANS', 'Sukses'),
(13, 3, '63a3dade6db95', '2022-12-22 11:19:42', 3, 10000, 'MIDTRANS', NULL, 'MIDTRANS', 'Cancel'),
(14, 3, '63a3db571a2ea', '2022-12-22 11:21:43', 3, 10000, 'Credit Card MANDIRI', '52111111-1117', 'MIDTRANS', 'Cancel'),
(15, 3, '63a3dc4b62f5c', '2022-12-22 11:25:47', 3, 10000, 'Credit Card CIMB', '35282033-4357', 'MIDTRANS', 'Sukses'),
(16, 3, '63a3dd8cc4130', '2022-12-22 11:31:09', 3, 10000, 'Credit Card MANDIRI', '45565579-6624', 'MIDTRANS', 'Sukses'),
(17, 3, '63a3dde97fbbd', '2022-12-22 11:32:41', 3, 10000, 'Bca Klikpay ', '', 'MIDTRANS', 'Sukses'),
(18, 3, '63a3de59f37a0', '2022-12-22 11:34:34', 3, 12000, 'Bca Klikpay ', '', 'MIDTRANS', 'Sukses'),
(19, 3, '63a3de6c46a03', '2022-12-22 11:34:52', 3, 11000, 'Danamon Online ', '', 'MIDTRANS', 'Sukses'),
(20, 3, '63a3de8ea6cd4', '2022-12-22 11:35:27', 3, 20000, 'Cimb Clicks ', '', 'MIDTRANS', 'Sukses'),
(21, 3, '63a3dec1dc497', '2022-12-22 11:36:18', 3, 14000, 'Cstore ALFAMART', '2662175981926829', 'MIDTRANS', 'Pending'),
(22, 3, '63a3e343039dd', '2022-12-22 11:55:31', 3, 10000, 'Bca Klikpay ', '', 'MIDTRANS', 'Sukses'),
(23, 3, '63a402b14e5e0', '2022-12-22 14:09:37', 3, 10000, 'MIDTRANS', NULL, 'MIDTRANS', 'Belum Bayar');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `deleted_date` int(20) DEFAULT NULL,
  `id_mitra` int(11) NOT NULL,
  `nama_mitra` varchar(100) DEFAULT NULL,
  `alamat` text,
  `provinsi` text,
  `kabupaten` text,
  `kecamatan` text,
  `kelurahan` text,
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `image` varchar(128) NOT NULL,
  `password` varchar(256) NOT NULL,
  `role_id` int(11) NOT NULL,
  `is_active` int(1) NOT NULL,
  `date_created` int(11) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `saldo` int(11) DEFAULT NULL,
  `your_referal_code` varchar(15) DEFAULT NULL,
  `using_referal_code` varchar(15) DEFAULT NULL,
  `id_telegram` varchar(50) DEFAULT NULL,
  `user_remote` varchar(100) DEFAULT NULL,
  `server` varchar(100) DEFAULT NULL,
  `vpn_remote` enum('YES','NO') DEFAULT 'YES',
  `pppoe` enum('YES','NO') DEFAULT 'YES',
  `hotspot` enum('YES','NO') DEFAULT 'YES',
  `type_pembayaran` enum('PRABAYAR','PASCABAYAR','','') DEFAULT 'PASCABAYAR',
  `expired_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `deleted_date`, `id_mitra`, `nama_mitra`, `alamat`, `provinsi`, `kabupaten`, `kecamatan`, `kelurahan`, `name`, `email`, `phone_number`, `image`, `password`, `role_id`, `is_active`, `date_created`, `last_login`, `saldo`, `your_referal_code`, `using_referal_code`, `id_telegram`, `user_remote`, `server`, `vpn_remote`, `pppoe`, `hotspot`, `type_pembayaran`, `expired_date`) VALUES
(3, NULL, 0, 'TULUS TUNNEL', NULL, NULL, NULL, NULL, NULL, 'tulus', 'admin@gmail.com', '087708763253', 'default.jpg', '$2a$12$LkoHNWMwCAnDF.B2wmM6lOPGiQ2MwP0MoujH95HEKPbLG4YFO4r3K', 1, 1, 1666681911, '2023-03-06 20:54:30', 225000, '232121310', NULL, '789695166', 'texa.net', 'texa.my.id', 'YES', 'YES', 'YES', 'PASCABAYAR', '2023-03-30'),
(5, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, 'Abdul Malik Ibrahim', 'cuymalik915@gmail.com', '087708763253', 'default.jpg', '$2y$10$dKJJSVTWbpggBvIcOREExO26Se2ca4PrLOVHNGFh8gfngeO9Jjqt2', 2, 1, 1677666507, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'YES', 'YES', 'YES', 'PASCABAYAR', NULL),
(6, 1677667406, 3, NULL, NULL, NULL, NULL, NULL, NULL, 'Fajar', 'fajar.fajar@daihatsu.astra.co.id', '087708763253', 'default.jpg', '$2y$10$t2dwyg2dZ.4sUd5sf3faOOh6oczY3xSr5ilOtWMsFZnG3flqk7kUC', 2, 0, 1677666581, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'YES', 'YES', 'YES', 'PASCABAYAR', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(11) NOT NULL,
  `role` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role`) VALUES
(1, 'Administrator'),
(2, 'Member');

-- --------------------------------------------------------

--
-- Table structure for table `vpn_master`
--

CREATE TABLE `vpn_master` (
  `id` int(11) NOT NULL,
  `id_mitra` int(11) NOT NULL,
  `id_server` varchar(25) DEFAULT NULL,
  `nama` varchar(100) DEFAULT NULL,
  `ip_local` varchar(16) DEFAULT NULL,
  `ip_public` varchar(16) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `lokasi` text,
  `status` enum('Aktif','Non Aktif') DEFAULT 'Aktif'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `vpn_master`
--

INSERT INTO `vpn_master` (`id`, `id_mitra`, `id_server`, `nama`, `ip_local`, `ip_public`, `harga`, `lokasi`, `status`) VALUES
(1, 3, '230206113314', 'VPN Remote 01', '10.10.10', '103.13.201.221', 20000, 'Indonesia', 'Aktif');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_routeros`
--
ALTER TABLE `api_routeros`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_order`
--
ALTER TABLE `data_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `log_activity_user`
--
ALTER TABLE `log_activity_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_gateway`
--
ALTER TABLE `payment_gateway`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `saran_kritik`
--
ALTER TABLE `saran_kritik`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `top_up`
--
ALTER TABLE `top_up`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vpn_master`
--
ALTER TABLE `vpn_master`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_routeros`
--
ALTER TABLE `api_routeros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `data_order`
--
ALTER TABLE `data_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `log_activity_user`
--
ALTER TABLE `log_activity_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `payment_gateway`
--
ALTER TABLE `payment_gateway`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `saran_kritik`
--
ALTER TABLE `saran_kritik`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `top_up`
--
ALTER TABLE `top_up`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `vpn_master`
--
ALTER TABLE `vpn_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
