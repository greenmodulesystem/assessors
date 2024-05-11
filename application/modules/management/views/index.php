<?php
main_header();
sidebar('applicant');

$fee_arr = [];
if (!empty($fees_list)) {
    foreach (@$fees_list as $f) {
        $fee_arr[] = $f->BusLine_ID;
    }
}

?>

<div class="content-wrapper">
    <section class="content-header">
        </br>
        <ol class="breadcrumb">
            <li><i class="fa fa-edit"></i> Assessors</li>
            <li>Businesses</li>
            <li class="active">Assessment</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box" style="width: 90%; margin: auto;">
                <div class="box-header">
                    <h3 class="box-title">Add Fees</h3>
                </div>
                <div class="box-body">
                    <div class="box-body no-padding">
                        <div class="row">
                            <div class="col-md-2">
                                Select Business Line :
                            </div>
                            <div class="col-md-5">
                                <select class="input-field pull-center input-md form-control select2" id="busID">
                                    <option value="" disabled selected>Select</option>
                                    <?php

                                    if (!empty(@$bus_line)) {
                                        foreach (@$bus_line as $b) {
                                            $busLineID = (string) $b->ID;
                                            if (!in_array($busLineID, $fee_arr)) {
                                    ?>
                                                <option value="<?= @$b->ID ?>"> <?= $b->Description ?> </option>
                                    <?php
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                Rate :
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" id="rate">
                            </div>
                            <div class="col-md-2 text-center">
                                <button type="button" class="btn btn-success" id="addfee" style="width:12rem;"> ADD </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="box" style="width: 90%; margin: auto; margin-top:10px;">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-briefcase"></i>Mayor's Fees Permit</h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 350px;  padding-top: 5px; margin-bottom: 5px">
                            <input type="text" name="table_search" id="search" class="form-control pull-right input-sm" placeholder="Search Mayors Permit Fee">
                            <div class="input-group-btn">
                                <button type="button" style="width: 50px;" id="btnSearch" class="btn btn-success input-sm"><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="box-body">

                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td width="25%"></td>
                                    <td width="45%">Business line</td>
                                    <td width="25%">Mayor's Permit Fee</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($fees_list)) {
                                    foreach ($fees_list as $value) {
                                ?>
                                        <tr>
                                            <td> - </td>
                                            <td> <?= @$value->Description ?> </td>
                                            <td> <?= @$value->Rate ?> </td>
                                        </tr>
                                <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<?php main_footer(); ?>

<script language="javascript" src="<?php echo base_url() ?>assets/mgmt/mgmt.js"></script>
<script language="javascript" src="<?php echo base_url() ?>assets/scripts/noPostBack.js"></script>
<script language="javascript">
    var baseUrl = '<?php echo base_url() ?>';
    // $(function() {
    //     $('.select2').select2()
    // })
    $(document).ready(function() {

        // // loadGrid();
        // // counter();
        // socket.emit('assessor', {
        //     assessor: 0,
        // });
        // updatecapp(ID);
        // $('.queue-status').attr('disabled', true);
    });
</script>