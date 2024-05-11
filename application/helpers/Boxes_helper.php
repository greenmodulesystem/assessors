<?php function boxes(){?>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3 id="Billing_count">0</h3>
                    <p>Waiting for Billing</p>
                </div>
                <div class="icon">
                    <i class="fa fa-file-o"></i>
                </div>
                <a href="<?php echo base_url() ?>treasurers/reports/waiting_for_billing" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3 id="Approval_count">0</h3>
                    <p>Waiting for Approval</p>
                </div>
                <div class="icon">
                <i class="fa fa-file-text-o"></i>
                </div>
                <a href="<?php echo base_url() ?>treasurers/reports/waiting_for_approval" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-green">
                <div class="inner">
                    <h3 id="Approved_count">0</h3>
                    <p>Approved Billing</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-o-up"></i>
                </div>
                <a href="<?php echo base_url() ?>treasurers/reports/approved" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3 id="Cancelled_count">0</h3>
                    <p>Cancelled Billing</p>
                </div>
                <div class="icon">
                    <i class="fa fa-thumbs-o-down"></i>
                </div>
                <a href="<?php echo base_url() ?>treasurers/reports/cancelled" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
<?php } ?>