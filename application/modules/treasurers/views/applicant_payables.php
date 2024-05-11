<?php 
$tax = 0;
$reg_fee = 0;
$other = 0;
$bal_total = 0;
$dis_total = 0;
$sur_total = 0;
$int_total = 0;
$total = 0;

$saved = (empty($assessment->ID)) ? false : true;
$details = ($saved) ? $fees : $information;

if($details == null){ ?>
    <tr class="warning"><td colspan="8" style="border-left:2px solid gray;border-right:2px solid gray;">&emsp;</tr>
    <tr class="warning text-center">
        <td colspan="8" style="border-left:2px solid gray;border-right:2px solid gray;"><b>NO RECORD</b></td>
    </tr>
    <tr class="warning">
        <td colspan="8" style="border-left:2px solid gray;border-right:2px solid gray;border-bottom:2px solid gray;">&emsp;
    </tr>
<?php } else {
    $billing = ($bill_fees == null) ? $bill_info : $bill_fees;
    foreach($details as $key => $infos) { 
        if($infos != null) { ?>
            <tr class="success">
                <td colspan="3" style="border-left:2px solid gray;"><b><?=$key?></b></td>
                <td colspan="5" style="border-left:2px solid gray;border-right:2px solid gray;"></td>
            </tr>
            <?php foreach($infos as $key1 => $info) { ?>
                <tr>
                    <td style="border-left:2px solid gray;"><?=$key1?></td>
                    <td class="text-center" id="stat<?=preg_replace("/[^a-zA-Z]+/", "",$key1)?>">
                        <?php 
                        if($key == 'Business Tax') {
                            echo 'RENEW';
                        } else if ($key == 'Regulatory Fee' && substr($key1, -6) == 'Permit') {
                            if($profiles->Status == 'NEW') {
                                echo ($profiles->Line_status == null) ? 'NEW' : $profiles->Line_status;
                            } else {
                                foreach($blines as $bline){
                                    if($bline->Business_line == substr($key1, 0, -17)) {
                                        if($bline->Essential == null && $bline->NonEssential == null){
                                            echo 'NEW';
                                        } else {
                                            echo 'RENEW';
                                        }
                                    }
                                }
                            }
                        } ?>
                    </td>
                    <td class="text-right" style="border-right:2px solid gray;">
                        <div class="<?=($collection > 0 || $key == 'Business Tax' || $info == 0) ? '' : 'fees_text' ;?>">
                            <?=($info == 0) ? 'EXEMPTED' : number_format($info,2)?>
                        </div>
                        <div class="<?=($collection > 0 || $key == 'Business Tax' || $info == 0) ? '' : 'fees_input' ;?>"
                            style="display:none"><input id="<?=preg_replace("/[^a-zA-Z]+/", "",$key1)?>" 
                            type="number" min="1" value="<?=$info?>" step="0.01" class="input-fees 
                                form-control input-sm text-right <?=(($key == 'Business Tax') ? 'input-bt' :
                                ($key == 'Regulatory Fee') ) ? 'input-rf' : 'input-oc';?>">
                        </div>
                    </td>
                    <?php if($key == 'Business Tax') {
                        $Balance[$key1] = 0; $Discount[$key1] = 0; $Surcharge[$key1] = 0; $Interest[$key1] = 0;
                        foreach($billing as $bill) {
                            if($bill['Line_of_business'] == $key1) { 
                                $line_total = 0;
                                $Balance[$key1] += $bill['Balance'];
                                $Discount[$key1]  += $bill['Discount'];
                                $Surcharge[$key1]  += $bill['Surcharge'];
                                $Interest[$key1]  += $bill['Interest'];
                            }
                        }
                        ?>
                            <td class="text-right edit_fees" data-id="Balance" name="<?=$key1?>"
                                data-target="" data-toggle="modal" data-keyboard="false" 
                                data-backdrop="static">
                                <div>
                                    <?php echo number_format($Balance[$key1],2);
                                        $bal_total += $Balance[$key1];?>
                                </div>
                            </td>
                            <td class="text-right">
                                <div>
                                    <?php echo number_format($Discount[$key1],2);
                                        $dis_total += $Discount[$key1];?>
                                </div>
                            </td>
                            <td class="text-right edit_fees" data-id="Surcharge" name="<?=$key1?>"
                                data-target="" data-toggle="modal" data-keyboard="false" 
                                data-backdrop="static">
                                <!-- <button class="<?=($saved) ? 'fees_input' : '' ;?> btn btn-xs btn-success pull-left 
                                    edit_fees" style="display:none" data-id="Surcharge" name="<?=$key1?>">Edit
                                </button> -->
                                <?php echo number_format($Surcharge[$key1],2);
                                    $sur_total += $Surcharge[$key1];?>
                            </td>
                            <td class="text-right edit_fees" data-id="Interest" name="<?=$key1?>"
                                data-target="" data-toggle="modal" data-keyboard="false" 
                                data-backdrop="static">
                                <!-- <button class="<?=($saved) ? 'fees_input' : '' ;?> btn btn-xs btn-success pull-left 
                                    edit_fees" style="display:none" data-id="Interest" name="<?=$key1?>">Edit
                                </button> -->
                                <?php echo number_format($Interest[$key1],2);
                                    $int_total += $Interest[$key1];?>
                            </td>
                            <td class="text-right" style="border-right:2px solid gray;">
                                <?php $line_total = $Balance[$key1] + $Surcharge[$key1] + $Interest[$key1] - $Discount[$key1];
                                echo number_format($line_total,2);
                                $total += $line_total;?>

                                <div id="Edit_modal" class="modal fade">
                                    <div class="modal-dialog modal-sm">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal">
                                                <span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title text-left">Edit <span id="Modal_name"></span></h4>
                                            </div>
                                            <div class="modal-body text-center">
                                                <h4><b><span id="Modal_type"></span></b></h4>
                                                <form class="form-horizontal">
                                                    <?php for($x = 0; $x < 4; $x++) { ?>
                                                    <div class="form-group text-left">
                                                        <label class="col-sm-3">Q<?=$x+1?> :</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" value="0" id="Qtr<?=$x?>" 
                                                            class="modal-input<?=preg_replace("/[^a-zA-Z]+/", "",$key1)?>
                                                                form-control input-sm text-right">
                                                        </div>
                                                    </div>
                                                    <?php } ?>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-danger btn-sm flat pull-left" data-dismiss="modal">
                                                    <i class="fa fa-close"></i>&ensp;Close
                                                </button>
                                                <button class="btn btn-primary btn-sm flat" id="Save_fees"
                                                    data-dismiss="modal"><i class="fa fa-print"></i>&ensp;Save
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                    <?php } else { ?>
                        <td class="text-right">
                            <div>
                                <?php $info = $collection > 0 ? 0 : $info;
                                    echo number_format($info,2);
                                    $bal_total += $info;?>
                            </div>
                            <!-- <div class="<?=($saved) ? 'fees_input' : '' ;?>" style="display:none">
                                <input id="bal<?=preg_replace("/[^a-zA-Z]+/", "",$key1)?>" type="number" min="1" step="0.01" 
                                value="<?=$info?>" class="input-fees form-control input-sm text-right">
                            </div> -->
                        </td>
                        <td class="text-right">0.00</td>
                        <td class="text-right">0.00</td>
                        <td class="text-right">0.00</td>
                        <td class="text-right" style="border-right:2px solid gray;">
                            <?php echo number_format($info,2);
                            $total += $info;?>
                        </td>
                    <?php } ?>
                </tr>
    <?php   }
        }
    } 
    
    foreach($details as $key => $infos) {
        if($key == 'Business Tax') {
            if($infos != null) {
                foreach($infos as $key1 => $info) {
                    $tax += $info;
                }
            }
        } else if ($key == 'Regulatory Fee') {
            foreach($infos as $key1 => $info) {
                $reg_fee += $info;
            }
        } else if ($key == 'Other Charge') {
            foreach($infos as $key1 => $info) {
                $other += $info;
            }
        }
    }
    
    $Q1 = 0; $Q2 = 0; $Q3 = 0; $Q4 = 0;
	$expired = false;
    if($profiles->Status == 'RENEWAL') {
        foreach($billing as $bill) {
            $line_total = $bill['Balance'] + $bill['Surcharge'] + $bill['Interest'];
            if($bill['Qtr'] == 1) { 
                $Q1 += $line_total;
            } else if($bill['Qtr'] == 2) {
                $Q2 += $line_total;
            } else if($bill['Qtr'] == 3) {
                $Q3 += $line_total;
            } else if($bill['Qtr'] == 4) {
                $Q4 += $line_total;
            }
        }
        $reg_other = $collection > 0 ? 0 : $reg_fee + $other;
        $Q1 = $Q1 + $reg_other; 
		
		if($saved && date('Y-m-d', strtotime($assessment->Expiry)) < date('Y-m-d')) {
            foreach($billing as $b) {
                if(date('Y-m-d', strtotime($assessment->Expiry)) >= date('Y-m-d', strtotime($b['Due_date'])) 
                && $b['Balance'] != 0) {
                    $expired = true;
                    break;
                }
            }
        }
    } else {
        $Q1 = $total;
    }
    ?>
    <tr>
        <td colspan="3" style="border-top:2px solid gray;border-left:2px solid gray;"><b>TOTALS</b></td>
        <td class="text-right" style="border-top:2px solid gray;border-left:2px solid gray;">
            <b><?=number_format($bal_total,2)?></b>
        </td>
        <td class="text-right" style="border-top:2px solid gray;"><b><?=number_format($dis_total,2)?></b></td>
        <td class="text-right" style="border-top:2px solid gray;"><b><?=number_format($sur_total,2)?></b></td>
        <td class="text-right" style="border-top:2px solid gray;"><b><?=number_format($int_total,2)?></b></td>
        <td class="text-right" style="border-top:2px solid gray;border-right:2px solid gray;">
            <b><?=number_format($total,2)?></b>
        </td>
    </tr>
    <tr>
        <td colspan="2" class="text-right" style="border-left:2px solid gray;">Tax:</td>
        <td id="Bt_val" class="text-right" style="border-right:2px solid gray;"><b><?=number_format($tax,2)?></b></td>
        <td class="text-right">Q1:</td>
        <td><b><?=number_format($Q1,2)?></b></td>
        <td class="text-right">Q3:</td>
        <td><b><?=number_format($Q3,2)?></b></td>
        <td style="border-right:2px solid gray;"></td>
    </tr>
    <tr>
        <td colspan="2" class="text-right" style="border-left:2px solid gray;">Reg Fees:</td>
        <td id="Rf_val" class="text-right" style="border-right:2px solid gray;"><b><?=number_format($reg_fee,2)?></b></td>
        <td class="text-right">Q2:</td>
        <td><b><?=number_format($Q2,2)?></b></td>
        <td class="text-right">Q4:</td>
        <td><b><?=number_format($Q4,2)?></b></td>
        <td style="border-right:2px solid gray;"></td>
    </tr>
    <tr>
        <td colspan="2" class="text-right" style="border-left:2px solid gray;">Other Charge:</td>
        <td id="Oc_val" class="text-right" style="border-right:2px solid gray;"><b><?=number_format($other,2)?></b></td>
        <td></td>
        <td colspan="2" class="text-right"><b>TOTAL AMOUNT DUE :</b></td>
        <td colspan="2" class="text-right" style="border-right:2px solid gray;">
            <b><?=number_format($total,2)?></b>
        </td>
    </tr>
    <tr>
        <td colspan="3" style="border-left:2px solid gray;">&emsp;</td>
        <td style="border-left:2px solid gray;"></td>
        <td colspan="2" class="text-right"><b>BILL IS VALID UNTIL :</b></td>
        <td colspan="2" class="text-right" style="border-right:2px solid gray;" id="Expiry">
            <?php if($profiles->Status == 'NEW') { ?>
                <!-- <b>December 31, <?=$profiles->Cycle_date?></b> -->
                <b>December 31, <?=date('Y')?></b> <!-- 01-10-2020 -->
            <?php } else { 
                    if((date('n') == 1 || date('n') == 4 || date('n') == 7 || date('n') == 10) && date('d') <= 20) { ?>
                        <!-- <b><?=date('F 20, ').$profiles->Cycle_date?></b> -->
                        <b><?=date('F 20, Y')?></b>
                    <?php } else { ?>
                        <!-- <b><?=date('F t, ').$profiles->Cycle_date?></b> -->
                        <b><?=date('F t, Y')?></b> <!-- 01-10-2020 -->
                    <?php }
                } 
            ?>
        </td>
    </tr>
    <tr>
        <td colspan="8" style="border-top:2px solid gray;">
            <input id="Assessment_ID" type="hidden" value="<?=($saved && !$expired) ? 
                $assessment->ID : '';?>">
            <button id="Save1" class="btn btn-sm flat btn-primary" <?=($saved && !$expired) ? 'disabled' : ''?>>
                <i class="fa fa-plus-square"></i><span>&ensp;Save</span>
            </button>
            <button id="Edit1" class="btn btn-sm flat btn-success" <?=($saved && !$expired && 
                $assessment->Status == 'Approved') ? 'disabled' : ''?>>
                <i class="fa fa-edit"></i><span>&ensp;Edit</span>
            </button>
            <button id="Approve" class="btn btn-sm flat btn-warning authenticate" style="display:<?=($saved && 
                !$expired && ($assessment->Status == '' || $assessment->Status == 'Cancelled')) ? 'inline' : 'none'?>" 
                data-target="#Approve_modal" data-toggle="modal" data-keyboard="false" data-backdrop="static">
                <i class="fa fa-check"></i><span>&ensp;Approve</span>
            </button>
            <button id="Cancel" class="btn btn-sm flat btn-danger authenticate" style="display:<?=($saved && 
                !$expired && $assessment->Status == 'Approved') ? 'inline' : 'none'?>" data-target="#Approve_modal" 
                data-toggle="modal" data-keyboard="false" data-backdrop="static">
                <i class="fa fa-ban"></i><span>&ensp;Cancel Approval</sspan>
            </button>
            <div id="Approve_modal" class="modal fade">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">
                                <i class="fa fa-exclamation-circle"></i>&ensp;User Permission Required
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group has-feedback">
                                <input id="Username" class="user-input form-control input-sm"
                                    placeholder="Username" type="text" autofocus>
                                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                            </div>
                            <div class="form-group has-feedback">
                                <input id="Password" class="user-input form-control input-sm"
                                    placeholder="Password" type="password">
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                <label id="Error" class="text-center" hidden></label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary btn-sm flat" id="Submit" data-id="">
                                <i class="fa fa-arrow-right"></i>&ensp;Submit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pull-right">
                <button id="View2" class="btn btn-sm flat btn-info" style="display:<?=($saved && !$expired && 
                    $profiles->Status=="RENEWAL" && $assessment->Status=='Approved') ? '' : 'none'?>"
                    data-target="#Bill_modal" data-toggle="modal" data-keyboard="false" data-backdrop="static">
                    <i class="fa fa-print"></i><span>&ensp;Print Billing Statement</span>
                </button>
                <button id="View" class="btn btn-sm flat btn-info" style="display:<?=($saved && !$expired && 
                    $assessment->Status=='Approved') ? '' : 'none'?>" data-target="#Ass_modal" 
                    data-toggle="modal" data-keyboard="false" data-backdrop="static">
                    <i class="fa fa-print"></i><span>&ensp;Print Assessment</span>
                </button>
            </div>
            <div id="Ass_modal" class="modal fade">
                <div class="modal-dialog" style="width:850px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span></button>
                            <font size='4'><b>Assessment</b></font>
                        </div>
                        <div class="modal-body" id="assessment-body">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger btn-sm flat pull-left" data-dismiss="modal">
                                <i class="fa fa-close"></i>&ensp;Close
                            </button>
                            <button class="btn btn-primary btn-sm flat" id="Print_assessment"
                                data-dismiss="modal"><i class="fa fa-print"></i>&ensp;Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="Bill_modal" class="modal fade">
                <div class="modal-dialog" style="width:850px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span></button>
                            <font size='4'><b>Billing Statement</b></font>
                        </div>
                        <div class="modal-body" id="billing-body">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger btn-sm flat pull-left" data-dismiss="modal">
                                <i class="fa fa-close"></i>&ensp;Close
                            </button>
                            <button class="btn btn-primary btn-sm flat" id="Print_billing"
                                data-dismiss="modal"><i class="fa fa-print"></i>&ensp;Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </td>
    </tr>
<?php } ?>

<!-- <script language="javascript" src="<?php echo base_url()?>assets/cto_assets/js/handlers/payables_handler.js"></script> -->
<script language="javascript" src="<?php echo base_url()?>assets/cto_assets/js/handlers/assessment_handler.js"></script>
<script language="javascript" src="<?php echo base_url()?>assets/scripts/noPostBack.js"></script>
<!-- <script src="<?php echo base_url() ?>assets/theme/bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->
<script language="javascript">
    var Application_ID = '<?php echo $profiles->ID?>';
    var Assessment_ID = '<?php echo (empty($assessment->ID)) ? '' : $assessment->ID?>';
    var Status = '<?php echo $profiles->Status?>';
    var details = <?php echo json_encode($details)?>;
    var assessment = <?php echo json_encode($assessment)?>;
    var bill_info = <?php echo json_encode($bill_info)?>;
    var bill_fees = <?php echo json_encode($bill_fees)?>;
    
    $("#View").on("click", function () {
        $(document).gmLoadPage({
            url     :   baseUrl+"treasurers/view_assessment/"+Application_ID+"/"+Assessment_ID,
            load_on :   "#assessment-body"
        }); 
    }); 

    $("#View2").on("click", function () {
        $(document).gmLoadPage({
            url     :   baseUrl+"treasurers/view_billing/"+Application_ID+"/"+Assessment_ID,
            load_on :   "#billing-body"
        });
    }); 
    
    $("#Print_assessment").on("click", function () {
        // $("#foot_ass").css("display", "block");
        $("#assessment-body").printThis({
        });
    });

    $("#Print_billing").on("click", function () {
        // $("#foot_bill").css("display", "block");
        $("#billing-body").printThis({
        });
    });

    socket.on( 'assessor', function( data ) {
        counter();
    });

</script>