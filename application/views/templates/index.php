<!DOCTYPE html>
<html lang="en"> 
    <?php $this->load->view("templates/header"); ?>
    <body>
        <div class="container-scroller">
            <!-- partial:partials/_navbar.html --> 
            <?php $this->load->view("templates/topbar"); ?>
            <!-- partial -->
            <div class="container-fluid page-body-wrapper">
				<input type="hidden" id="verifiedtoken" data-name="<?= $this->name_token; ?>" data-token="<?= $this->token; ?>">
                <?php $this->load->view("templates/sidebar"); ?>
                <div class="main-panel">
                    <div class="content-wrapper">
                        <?php $this->load->view("admin/".$page) ?>
                    </div>
                    <?php $this->load->view("templates/footer"); ?>
                </div>
                <!-- main-panel ends -->
            </div>
            <!-- page-body-wrapper ends -->
        </div>
        <?php $this->load->view("templates/footer_js"); ?>
    </body>
</html>
