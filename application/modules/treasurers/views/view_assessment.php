<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/template.css">
<?php
    $Bldg = ($profiles->Building_name != '') ? trim($profiles->Building_name).", " : '';
    $Strt = ($profiles->Street != '') ? trim($profiles->Street).", " : '';
    $Prk = ($profiles->Purok != '') ? trim($profiles->Purok).", " : '';
    $Address1 = $Bldg.$Strt.$Prk;
    $Address2 = trim($profiles->Barangay).", SAGAY City";
    $Proprietor = $profiles->Tax_payer;
    $Status = ($profiles->Status == 'RENEWAL') ? 'RENEW' : $profiles->Status;
    $Type = $Status." (".$profiles->Cycle_date.")";
    $total = 0; $tax = 0; $reg_fee = 0; $other = 0;
    $bal_total = 0; $dis_total = 0; $sur_total = 0; $int_total = 0;
    date_default_timezone_set('Asia/Manila');
    $user =  $_SESSION['User_details'];
	$Tradename = $profiles->Tradename == '' ? $profiles->Business_name : $profiles->Tradename;
    $Expiry = "";
    if($profiles->Status == 'NEW') {
        $Expiry = "December 31, ".$profiles->Cycle_date;
    } else {
        if((date('n') == 1 || date('n') == 4 || date('n') == 7 || date('n') == 10) && date('d') <= 20) {
            // $Expiry = date('F 20, ').$profiles->Cycle_date;
			$Expiry = date('F 20, Y'); // 01-10-2020
        } else {
            // $Expiry = date('F t, ').$profiles->Cycle_date;
            $Expiry = date('F t, Y'); // 01-10-2020
        }
    }
?>

<div class="doc" style="line-height:.6em; width:21.6cm; display:inline-block; left:in;">
    <img id="Ass_logo" style="width:85px; height:85px; position:absolute; left:19.5%; top:0.2cm"src="<?php echo base_url()?>assets/img/Logo_2.png">
    <div style="text-align: center;">
        <p>REPUBLIC OF THE PHILIPPINES</p>
        <p>CITY OF SAGAY</p>
        <div style="line-height:1.7em;">&emsp;</div>
        <p><b>OFFICE OF THE CITY TREASURER</b></p>
        <div style="line-height:0.8em;">&emsp;</div>
        <p style="font-size:16px;font-weight:bolder;">ASSESSMENT RECORD</p>
    </div>
    <div style="line-height:1em;">&emsp;</div>
    <div>
        <div style="float:left;">
            <!-- <p><span>BIN</span>: <b>B1402017060006</b></p> -->
            <p><span>Trade Name</span>: <b><?=strtoupper($Tradename)?></b></p>
            <p><span>Address</span>: <?=strtoupper($Address1)?></p>
            <p><span>&emsp;</span>&nbsp;&nbsp;<?=strtoupper($Address2)?></p>
        </div>
        <div style="float:right;text-align:right">
            <!-- <p class="right">Application No&nbsp;<span>: <b>B1402017060006-2R</b></span></p> -->
            <p class="right">Type&nbsp;<span>: <b><?=strtoupper($Type)?></b></span></p>
            <p class="right">Date&nbsp;<span>: <?=date('F d, Y', strtotime($profiles->Date_accepted))?></span></p>
        </div>
    </div>
    <div style="clear:both;">
        <div style="line-height:0.8em;">&emsp;</div>
        <p><span>Proprietor</span>: <b><?=strtoupper($Proprietor)?></b></p>
        <p><span>Address</span>: <?=strtoupper($Address1)?></p>
        <p><span>&emsp;</span>&nbsp;&nbsp;<?=strtoupper($Address2)?></p>
    </div>
    <div style="line-height:1em;">&emsp;</div>
    <!-- <table width="100%" border="1"> -->
    <table width="100%">
        <thead><tr height="20px" style="border-top:1px solid black;border-bottom:1px solid black;">
                <th width="51.7%" colspan="3" class="text-center" style="border-right:1px solid black;"><b>Assessment Information</b></th>
                <th width="48.3%" colspan="5 "class="text-center"><b>Billing Information</b></th>
        </tr></thead>
        <thead><tr height="18px" style="vertical-align:bottom;">
                <th width="30.2%" style="padding-left:8px;"><b>Tax/Fee</b></th>
                <th width="8%" class="text-center"><b>Status</b></th>
                <th width="12.8%" class="text-right" style="padding-right:8px;"><b>Amount</b></th>
                <th width="11%" class="text-right" style="border-left:1px solid black;"><b>Balance Due</b></th>
                <th width="8%" class="text-right"><b>Discount</b></th>
                <th width="9%" class="text-right"><b>Surcharge</b></th>
                <th width="7.5%" class="text-right"><b>Interest</b></th>
                <th width="13.5%" class="text-right" style="padding-right:12px;"><b>Total</b></th>
        </tr></thead>
        <tbody id="tbody">
        <?php foreach($fees as $key => $infos) { 
            if($infos != null) { ?>
                <tr height="20px" style="vertical-align:bottom;">
                    <td colspan="3"><b><?=$key?></b></td>
                    <td colspan="5" style="border-left:1px solid black;"></td>
                </tr>
                <tr height="8px">
                    <td colspan="3">&emsp;</td>
                    <td colspan="5" style="border-left:1px solid black;"></td>
                </tr>
                <?php foreach($infos as $key1 => $info) { ?>
                    <tr height="17px" style="vertical-align: top;line-height:1.5em;">
                        <td><?=strtoupper($key1)?></td>
                        <td class="text-center"><?=$info['Status']?></td>
                        <td class="text-right" style="padding-right:8px;">
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
                                <td class="text-right" style="border-left:1px solid black;">
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
                                <td class="text-right" style="padding-right:10px;">
                                    <?php $line_total = $Balance[$key1] + $Surcharge[$key1] + $Interest[$key1] - $Discount[$key1];
                                        echo number_format($line_total,2);
                                        $total += $line_total;
                                    ?>
                                </td>
                        <?php } else { ?>
                            <td class="text-right" style="border-left:1px solid black;">
                                <?php $info['Fee'] = $collection > 0 ? 0 : $info['Fee']; 
                                    echo number_format($info['Fee'],2);
                                    $bal_total += $info['Fee'];?>
                            </td>
                            <td class="text-right">0.00</td>
                            <td class="text-right">0.00</td>
                            <td class="text-right">0.00</td>
                            <td class="text-right" style="padding-right:12px;">
                                <?php echo number_format($info['Fee'],2);
                                    $total += $info['Fee'];
                                ?>
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
        if($profiles->Status == 'RENEWAL') {
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
            $reg_other = $collection > 0 ? 0 : $reg_fee + $other;
            $Q1 = $Q1 + $reg_other; 
        } else {
            $Q1 = $total;
        }
        ?>
        </tbody>
        <tfoot id="tfoot">
            <tr height="4px">
                <td colspan="3">&emsp;</td>
                <td colspan="5" style="border-left:1px solid black;"></td>
            </tr>
            <tr height="18px" style="border-top:1px solid black;vertical-align:bottom;">
                <td colspan="3" style="border-right:1px solid black;padding-left:8px;"><b>TOTALS</b></td>
                <td class="text-right"> <b><?=number_format($bal_total,2)?></b></td>
                <td class="text-right"><b><?=number_format($dis_total,2)?></b></td>
                <td class="text-right"><b><?=number_format($sur_total,2)?></b></td>
                <td class="text-right"><b><?=number_format($int_total,2)?></b></td>
                <td class="text-right" style="padding-right:12px;"><b><?=number_format($total,2)?></b></td>
            </tr>
            <tr height="20px">
                <td class="text-right" style="padding-right:50px;">Tax:</td>
                <td colspan="2" class="text-right" style="padding-right:80px;"><b><?=number_format($tax,2)?></b></td>
                <td colspan="5" style="border-left:1px solid black;"></td>
            </tr>
            <tr height="20px">
                <td class="text-right" style="padding-right:50px;">Reg Fees:</td>
                <td colspan="2" class="text-right" style="padding-right:80px;"><b><?=number_format($reg_fee,2)?></b></td>
                <td colspan="2" style="border-left:1px solid black;padding-left:50px">
                    Q1:&nbsp;<b><?=number_format($Q1,2)?></b>
                </td>
                <td colspan="2" style="padding-left:40px">Q3:&nbsp;<b><?=number_format($Q3,2)?></b></td>
                <td></td>
            </tr>
            <tr height="20px">
                <td class="text-right" style="padding-right:50px;">Other Charge:</td>
                <td colspan="2" class="text-right" style="padding-right:80px;"><b><?=number_format($other,2)?></b></td>
                <td colspan="2" style="border-left:1px solid black;padding-left:50px">
                    Q2:&nbsp;<b><?=number_format($Q2,2)?></b>
                </td>
                <td colspan="2" style="padding-left:40px">Q4:&nbsp;<b><?=number_format($Q4,2)?></b></td>
                <td></td>
            </tr>
            <tr height="10px">
                <td colspan="3">&emsp;</td>
                <td colspan="5" style="border-left:1px solid black;"></td>
            </tr>
            <tr height="25px" style="font-size:16px;font-weight:bolder;">
                <td colspan="3" style="border-right:1px solid black;">&emsp;</td>
                <td colspan="3" class="text-right" style="border-left:1px solid black;">
                    <b>TOTAL AMOUNT DUE :</b>
                </td>
                <td colspan="2" class="text-right" style="padding-right:12px;">
                    <b><?=number_format($total,2)?></b>
                </td>
            </tr>
            <tr height="20px">
                <td colspan="3" style="border-right:1px solid black;">&emsp;</td>
                <td colspan="3" class="text-right" style="font-size:15.5px;font-weight:bolder;"><b>BILL IS VALID UNTIL :</b></td>
                <td colspan="2" class="text-right" style="padding-right:12px;font-size:14px;">
                    <b><input style="width:155px;border-style:none;" class="sm permit-info text-right" value="<?=$Expiry?>"></b>
                </td>
            </tr>
            <tr height="10px" style="border-bottom:1px solid black;">
                <td colspan="3">&emsp;</td>
                <td colspan="5"></td>
            </tr>
        </tfoot>
    </table>
    <div>
        <div style="line-height:0.8em;">&emsp;</div>
        <p style="line-height:3em;padding-left:35px;">This is to certify that the above information supplied 
            by me is true and correct to the best of my knowledge and belief.
        </p>
        <div style="float:left;line-height:1em;padding-left:42px;">
            <p>Assessed By:</p>
            <p>&emsp;</p>
            <p style="width:335px;border-bottom:1px solid black" class="text-center">
                <?=strtoupper($assessment->Assessed_by)?></p>
        </div>
        <div style="float:right;line-height:1em;">
            <p><i><span style="width:6.3cm;">PAID UNDER OR NO.:</span></i></p>
            <p style="text-align:right;padding-right:25px;"><input style="width:90px;" class="sm permit-info text-center"></p>
            <p><i><span style="width:6.3cm;">DATED:</span></i></p>
            <p style="text-align:right;padding-right:25px;"><input style="width:160px;" class="sm permit-info text-center"></p>
            <p><i><span style="width:6.3cm;">AMOUNT:</span></i></p>
            <p style="text-align:right;padding-right:25px;"><input style="width:150px;" class="sm permit-info text-center"></p>
        </div>
    </div>
    <div style="clear:both;">&emsp;</div>
    <div style="float:right;line-height:1em;padding-right:25px;">
        <div style="line-height:4em;">&emsp;</div>
        <p>Reviewed By:</p>
        <p>&emsp;</p>
        <!-- <p><input style="width:305px;" class="sm permit-info text-center" value="<?=strtoupper($assessment->Action_by)?>"></p> -->
        <p class="text-center" style="width:305px;border-bottom:1px solid black">ARNOLD V. ARAQUIL</p>
        <p class="text-center" style="line-height:0em"><i>LTOO III</i></p>
    </div>
    <div style="clear:both;line-height:1em;">&emsp;</div>
    <div>
        <p style="line-height:1em;padding-left:8px;">
            <b><i>NOTICE: PLEASE PAY THE AMOUNT DUE AT THE CITY TREASURER'S OFFICE</i></b>
        </p>
        <p style="padding-left:8px;">
            <b><i>ON OR BEFORE</i></b>
            <input style="width:160px;" class="sm permit-info text-center">
            <b><i>PAYMENT AFTER DUE DATE WILL</i></b>
        </p>
        <p style="line-height:0em;padding-left:8px;">
            <b><i>HAVE A SURCHARGE OF 25% AND 2% INTEREST PER MONTH.</i></b>
        </p>
    </div>
    <div style="line-height:3em;">&emsp;</div>
    <div class="thisfoot">
        <p> 
            GENERATED BY : BUSINESS LICENSING INFORMATION SYSTEM&emsp;&emsp;&emsp;&emsp;&emsp;
            &emsp;&emsp;&emsp;&emsp;&emsp;VERSION : 2.0.0&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;
            &emsp;&emsp;&emsp;&emsp;&emsp;ASSESSED BY : <?=strtoupper($assessment->Assessed_by)?>
        </p>
        <p> PRINTED BY : <?=strtoupper($user->First_name." ".$user->Last_name)?>&emsp;
            PRINT DATE : <?=date('Y-m-d H:i:s')?></p>
    </div>
</div>