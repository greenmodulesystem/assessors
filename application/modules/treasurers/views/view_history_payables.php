<?php 
$tax = 0;
$reg_fee = 0;
$other = 0;
$bal_total = 0;
$dis_total = 0;
$sur_total = 0;
$int_total = 0;
$total = 0;

foreach($fees as $key => $infos) { 
    if($infos != null) { ?>
        <tr class="success">
            <td colspan="3" style="border-left:2px solid gray;"><b><?=$key?></b></td>
            <td colspan="5" style="border-left:2px solid gray;border-right:2px solid gray;"></td>
        </tr>
        <?php foreach($infos as $key1 => $info) { ?>
            <tr>
                <td style="border-left:2px solid gray;"><?=$key1?></td>
                <td class="text-center"><?=$info['Status']?></td>
                <td class="text-right" style="border-right:2px solid gray;">
                    <?=($info['Fee'] == 0) ? 'EXEMPTED' : number_format($info['Fee'],2)?>
                </td>
                <?php if($key == 'Business Tax') {
                    $Balance[$key1] = 0; $Discount[$key1] = 0; $Surcharge[$key1] = 0; $Interest[$key1] = 0;
                    foreach($bill_fees as $bill) {
                        if($bill['Line_of_business'] == $key1) { 
                            $line_total = 0;
                            $Balance[$key1] += $bill['Balance'];
                            $Discount[$key1]  += $bill['Discount'];
                            $Surcharge[$key1]  += $bill['Surcharge'];
                            $Interest[$key1]  += $bill['Interest'];
                        }
                    }
                    ?>
                        <td class="text-right">
                            <?php echo number_format($Balance[$key1],2);
                                $bal_total += $Balance[$key1];?>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($Discount[$key1],2);
                                $dis_total += $Discount[$key1];?>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($Surcharge[$key1],2);
                                $sur_total += $Surcharge[$key1];?>
                        </td>
                        <td class="text-right">
                            <?php echo number_format($Interest[$key1],2);
                                $int_total += $Interest[$key1];?>
                        </td>
                        <td class="text-right" style="border-right:2px solid gray;">
                            <?php $line_total = $Balance[$key1] + $Surcharge[$key1] + $Interest[$key1] - $Discount[$key1];
                            echo number_format($line_total,2);
                            $total += $line_total;?>
                        </td>
                <?php } else { ?>
                    <td class="text-right">
                        <div>
                            <?php echo number_format($info['Fee'],2);
                                $bal_total += $info['Fee'];?>
                        </div>
                    </td>
                    <td class="text-right">0.00</td>
                    <td class="text-right">0.00</td>
                    <td class="text-right">0.00</td>
                    <td class="text-right" style="border-right:2px solid gray;">
                        <?php echo number_format($info['Fee'],2);
                        $total += $info['Fee'];?>
                    </td>
                <?php } ?>
            </tr>
<?php   }
    }
} 

foreach($fees as $key => $infos) {
    if($key == 'Business Tax') {
        if($infos != null) {
            foreach($infos as $key1 => $info) {
                $tax += $info['Fee'];
            }
        }
    } else if ($key == 'Regulatory Fee') {
        foreach($infos as $key1 => $info) {
            $reg_fee += $info['Fee'];
        }
    } else if ($key == 'Other Charge') {
        foreach($infos as $key1 => $info) {
            $other += $info['Fee'];
        }
    }
}

$Q1 = 0; $Q2 = 0; $Q3 = 0; $Q4 = 0; 
if($bill_fees != null) {
    foreach($bill_fees as $bill) {
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
    $reg_other = $reg_fee + $other;
    $Q1 = $Q1 + $reg_other; 
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
    <td class="text-right" style="border-right:2px solid gray;"><b><?=number_format($tax,2)?></b></td>
    <td class="text-right">Q1:</td>
    <td><b><?=number_format($Q1,2)?></b></td>
    <td class="text-right">Q3:</td>
    <td><b><?=number_format($Q3,2)?></b></td>
    <td style="border-right:2px solid gray;"></td>
</tr>
<tr>
    <td colspan="2" class="text-right" style="border-left:2px solid gray;">Reg Fees:</td>
    <td class="text-right" style="border-right:2px solid gray;"><b><?=number_format($reg_fee,2)?></b></td>
    <td class="text-right">Q2:</td>
    <td><b><?=number_format($Q2,2)?></b></td>
    <td class="text-right">Q4:</td>
    <td><b><?=number_format($Q4,2)?></b></td>
    <td style="border-right:2px solid gray;"></td>
</tr>
<tr>
    <td colspan="2" class="text-right" style="border-left:2px solid gray;">Other Charge:</td>
    <td class="text-right" style="border-right:2px solid gray;"><b><?=number_format($other,2)?></b></td>
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
    <td colspan="2" class="text-right" style="border-right:2px solid gray;">
        <b><?=date('F d, Y', strtotime($assessment->Expiry))?></b>
    </td>
</tr>
<tr>
    <td colspan="8" style="border-top:2px solid gray;">
        <div class="pull-right">
            <button id="View2" class="btn btn-sm flat btn-info" style="display:<?=($bill_fees != null) ? '' : 'none'?>"
                data-target="#Bill_modal" data-toggle="modal" data-keyboard="false" 
                data-backdrop="static">
                <i class="fa fa-print"></i><span>&ensp;Print Billing Statement</span>
            </button>
            <button id="View" class="btn btn-sm flat btn-info" data-target="#Ass_modal" 
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

<script language="javascript" src="<?php echo base_url()?>assets/scripts/noPostBack.js"></script>
<script language="javascript"> 
    var Application_ID = '<?php echo $profiles->ID?>';
    var Assessment_ID = '<?php echo $assessment->ID?>';

    $("#View").on("click", function () {
        $(document).gmLoadPage({
            url     :   baseUrl+"treasurers/view_history_assessment/"+Application_ID+"/"+Assessment_ID,
            load_on :   "#assessment-body"
        });
    });
    
    $("#View2").on("click", function () {
        $(document).gmLoadPage({
            url     :   baseUrl+"treasurers/view_history_billing/"+Application_ID+"/"+Assessment_ID,
            load_on :   "#billing-body"
        });
    });
    
    $("#Print_assessment").on("click", function () {
        $("#assessment-body").printThis();
    });

    $("#Print_billing").on("click", function () {
        $("#billing-body").printThis();
    });
</script>