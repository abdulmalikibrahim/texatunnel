<?php
if(empty($this->id_user)){
    redirect("auth/logout");
}
?>
<!-- partial:partials/_sidebar.html -->
<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <div style="font-size:9pt;" class="sidebar-heading pl-2 text-secondary mb-2">Dashboard</div>
        <li class="nav-item <?php if($this->p1 == "admin"){ echo "active"; } ?>">
            <a class="nav-link" href="<?= base_url("admin") ?>">
                <i class="icon-grid menu-icon"></i><span class="menu-title">Dashboard</span>
            </a>
        </li>
        <div style="font-size:9pt;" class="sidebar-heading pl-2 text-secondary mt-2 mb-2">Pembelian</div>
        <?php
        $jasa = $this->model->gd("user","vpn_remote,hotspot,pppoe,role_id","id = '".$this->id_user."'","row");
        if($jasa->vpn_remote == "YES"){
            ?>
            <li class="nav-item <?php if($this->p1 == "list_vpn" || $this->p1 == "list_order_vpn" || $this->p1 == "order_vpn"){ echo "active"; } ?>">
                <a class="nav-link" data-toggle="collapse" href="#vpn-remote" aria-expanded="false" aria-controls="vpn-remote">
                    <i class="fas fa-laptop-house menu-icon"></i>
                    <span class="menu-title">VPN REMOTE</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="vpn-remote">
                    <ul class="nav flex-column sub-menu">
                        <?php
                        if($this->role_id == "1"){
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="'.base_url("list_vpn").'">Setting VPN</a>
                            </li>';
                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("order_vpn") ?>">Order VPN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("list_order_vpn") ?>">Data Order</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php
        }
        if($jasa->pppoe == "YES"){
            ?>
            <li class="nav-item <?php if($this->p1 == "setting_pppoe" || $this->p1 == "list_order_pppoe" || $this->p1 == "order_pppoe"){ echo "active"; } ?>">
                <a class="nav-link" data-toggle="collapse" href="#pppoe-client" aria-expanded="false" aria-controls="pppoe-client">
                    <i class="fas fa-network-wired menu-icon"></i>
                    <span class="menu-title">PPPoE</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="pppoe-client">
                    <ul class="nav flex-column sub-menu">
                        <?php
                        if($this->role_id == "1"){
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="'.base_url("setting_pppoe").'">Setting</a>
                            </li>';
                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("order_pppoe") ?>">Order PPPoE</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("list_order_pppoe") ?>">Data Order</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php
        }
        if($jasa->hotspot == "YES"){
            ?>
            <li class="nav-item <?php if($this->p1 == "setting_hotspot" || $this->p1 == "user_order_hotspot" || $this->p1 == "order_hotspot" || $this->p1 == "user_active_hotspot" || $this->p1 == "voucher_hotspot"){ echo "active"; } ?>">
                <a class="nav-link" data-toggle="collapse" href="#hotspot-client" aria-expanded="false" aria-controls="hotspot-client">
                    <i class="fas fa-wifi menu-icon"></i>
                    <span class="menu-title">HOTSPOT</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="hotspot-client">
                    <ul class="nav flex-column sub-menu">
                        <?php
                        if($this->role_id == "1"){
                            echo '
                            <li class="nav-item">
                                <a class="nav-link" href="'.base_url("setting_hotspot").'">Setting</a>
                            </li>';
                        }
                        ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("order_hotspot") ?>">Order Hotspot</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("voucher_hotspot") ?>">Voucher</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("templates_hotspot") ?>">Templates Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("user_order_hotspot") ?>">User Hotspot</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php
        }
        ?>
        <div style="font-size:9pt;" class="sidebar-heading pl-2 text-secondary mt-2 mb-2">Informasi</div>
        <li class="nav-item <?php if($this->p1 == "account"){ echo "active"; } ?>">
            <a class="nav-link" href="<?= base_url("account") ?>">
                <i class="fas fa-user menu-icon"></i><span class="menu-title">My Profile</span>
            </a>
        </li>
        <li class="nav-item <?php if($this->p1 == "saldo"){ echo "active"; } ?>">
            <a class="nav-link" data-toggle="collapse" href="#info-saldo" aria-expanded="false" aria-controls="info-saldo">
                <i class="fas fa-dollar-sign menu-icon"></i>
                <span class="menu-title">Saldo</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="info-saldo">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url("saldo/isi_saldo") ?>">Info Saldo</a>
                    </li><?php
                    if($this->role_id == "1"){
                        echo '
                        <li class="nav-item">
                            <a class="nav-link" href="'.base_url("saldo/success").'">Data Top Up</a>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
        </li>
        <li class="nav-item <?php if($this->p1 == "user_list" || $this->p1 == "user"){ echo "active"; } ?>">
            <a class="nav-link" href="<?= base_url("user_list") ?>">
                <i class="fas fa-users menu-icon"></i><span class="menu-title">Data Client</span>
            </a>
        </li>
        <?php
        if($this->role_id == "1"){
            ?>
            <div style="font-size:9pt;" class="sidebar-heading pl-2 text-secondary mt-2 mb-2">Setting</div>
            <li class="nav-item <?php if($this->p1 == "routeros_list"){ echo "active"; } ?>">
                <a class="nav-link" href="<?= base_url("routeros_list") ?>">
                    <i class="fas fa-server menu-icon"></i><span class="menu-title">Server Mikrotik</span>
                </a>
            </li>
			<?php
			if($this->role_id == "1"){
				?>
				<li class="nav-item <?php if($this->p1 == "olt"){ echo "active"; } ?>">
					<a class="nav-link" data-toggle="collapse" href="#olt" aria-expanded="false" aria-controls="olt">
						<i class="fas fa-code-fork menu-icon"></i>
						<span class="menu-title">OLT</span>
						<i class="menu-arrow"></i>
					</a>
					<div class="collapse" id="olt">
						<ul class="nav flex-column sub-menu">
							<li class="nav-item">
								<a class="nav-link" href="<?= base_url("olt/setting") ?>">Setting</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?= base_url("olt/onu_type") ?>">ONU Type</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?= base_url("olt/zone") ?>">Zone</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?= base_url("olt/onu") ?>">Data ONU</a>
							</li>
						</ul>
					</div>
				</li>
				<?php
			}
			?>
            <li class="nav-item <?php if($this->p1 == "payment_gateway" || $this->p1 == "list_payment"){ echo "active"; } ?>">
                <a class="nav-link" data-toggle="collapse" href="#payment-setting" aria-expanded="false" aria-controls="payment-setting">
                    <i class="fas fa-money-check-dollar menu-icon"></i>
                    <span class="menu-title">Payment</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="payment-setting">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("payment_gateway") ?>">Payment Gateway</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url("list_payment") ?>">Payment Manual</a>
                        </li>
                    </ul>
                </div>
            </li>
            <?php
        }
        ?>
        <div style="font-size:9pt;" class="sidebar-heading pl-2 text-secondary mt-2 mb-2">Other</div>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url("saran_kritik") ?>">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Saran & Kritik</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url("documentation") ?>">
                <i class="icon-paper menu-icon"></i>
                <span class="menu-title">Dokumentasi</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="<?= base_url("auth/logout") ?>">
                <i class="ti-power-off menu-icon"></i><span class="menu-title">Logout</span>
            </a>
        </li>
    </ul>
</nav>
