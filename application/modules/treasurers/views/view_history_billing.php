<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/template.css">
<?php
    $Bldg = ($profiles->Building_name != '') ? trim($profiles->Building_name).", " : '';
    $Strt = ($profiles->Street != '') ? trim($profiles->Street).", " : '';
    $Prk = ($profiles->Purok != '') ? trim($profiles->Purok).", " : '';
    $Address1 = $Bldg.$Strt.$Prk;
    $Address2 = trim($profiles->Barangay).", SAGAY City";
    $Proprietor = $profiles->Tax_payer;
    $Status = ($profiles->Status == 'RENEWAL') ? 'RENEW' : $profiles->Status;
    $total = 0;$bal_total = 0; $dis_total = 0; $sur_total = 0; $int_total = 0;
    $Q1 = 0;$Q2 = 0; $Q3 = 0; $Q4 = 0;
    date_default_timezone_set('Asia/Manila');
    $user =  $_SESSION['User_details'];
?>

<div class="doc" style="line-height:.6em; width:21.6cm; display:inline-block; left:in;">
    <img style="width:100px; height:100px; position:absolute; left:11%; top:0cm"src="<?php echo base_url()?>assets/img/Logo_2.png">
    <div style="text-align: center;">
        <p>REPUBLIC OF THE PHILIPPINES</p>
        <p>CITY OF SAGAY</p>
        <div style="line-height:2.2em;">&emsp;</div>
        <p style="font-size:16.5px;font-weight:bolder;"><b>OFFICE OF THE CITY TREASURER</b></p>
        <div style="line-height:1em;">&emsp;</div>
        <p style="font-size:18.5px;font-weight:bolder;">BUSINESS TAX BILLING STATEMENT</p>
    </div>
    <div style="line-height:1em;">&emsp;</div>
    <div>
        <div style="float:left;">
            <div style="line-height:0.8em;">&emsp;</div>
            <p><span>Proprietor</span>: <?=strtoupper($Proprietor)?></p>
            <p><span>Address</span>: <?=strtoupper($Address1)?></p>
            <p><span>&emsp;</span>&nbsp;&nbsp;<?=strtoupper($Address2)?></p>
            <div style="line-height:0.8em;">&emsp;</div>
            <p><span>Tradename</span>: <?=strtoupper($profiles->Business_name)?></p>
            <p><span>Bus.Address</span>: <?=strtoupper($Address1)?></p>
            <p><span>&emsp;</span>&nbsp;&nbsp;<?=strtoupper($Address2)?></p>
        </div>
        <div style="float:right;padding-right:38px;">
            <div style="line-height:0.8em;">&emsp;</div>
            <p><span style="width:2.6cm">Bill Date</span>: <?=date('F d, Y')?></p>
            <p>&emsp;</p>
            <div style="line-height:0.8em;">&emsp;</div>
            <!-- <p><span style="width:2.6cm">App No.</span>: <b>B1402017060006-2R</b></p> -->
            <p><span style="width:2.6cm">Application Type</span>: <b><?=strtoupper($Status)?></b></p>
        </div>
    </div>
    <div style="clear:both;">
    </div>
    <div style="line-height:1em;">&emsp;</div>
    <!-- <table width="100%" border="1"> -->
    <table width="100%">
        <thead><tr height="20px" style="border-top:1px solid black;border-bottom:1px solid black;">
                <th width="4" style="padding-left:8px;" class="text-center"><b>Qtr</b></th>
                <!-- <th width="40%" style="padding-left:20px;"><b>Line of Business</b></th> -->
                <th width="18%" class="text-left"><b>Line of Business</b></th>
                <th width="21%" class="text-left"><b>Account</b></th>
                <th width="10%" class="text-center"><b>Due Date</b></th>
                <th width="11%" class="text-right"><b>Amount</b></th>
                <th width="8%" class="text-right"><b>Discount</b></th>
                <th width="9%" class="text-right"><b>Surcharge</b></th>
                <th width="8%" class="text-right"><b>Interest</b></th>
                <th width="11%" class="text-right" style="padding-right:2px;"><b>Total</b></th>
        </tr></thead>
        <tbody>
            <tr style="line-height:0.4em;"><td colspan="8">&emsp;</td></tr>
            <?php foreach($bill_fees as $key => $bill) { 
                $line_total[$key] = $bill->Balance + $bill->Surcharge + $bill->Interest - $bill->Discount;
                if($bill->Balance != 0) {?>
                <tr style="vertical-align:top; line-height:1.4em;">
                    <td style="padding-left:8px;" class="text-center"><?=$bill->Qtr?></td>
                    <!-- <td style="padding-left:20px;"><?=strtoupper($bill->Line_of_business)?></td> -->
                    <td class="text-left">
                        <?=wordwrap(strtoupper($bill->Line_of_business),18,"<br>\n")?>
                    </td>
                    <td class="text-left">
                        <?php $account = '';
                        foreach($blines as $line) {
                            if($line->Business_line == $bill->Line_of_business) {
                                $category = ($line->Business_category == 'Dealer' || $line->Business_category == 'Wholesaler' 
                                || $line->Business_category == 'Producer' || $line->Business_category == 'Other') ? 'Wholesaler' :
                                $line->Business_category;
                                $account = 'BUSINESS TAX FOR '.strtoupper($category);
                            }
                        }
                        echo wordwrap($account,25,"<br>\n")?>
                    </td>
                    <td class="text-center"><?=date('m/d/Y', strtotime($bill->Due_date))?></td>
                    <td class="text-right"><?php $bal_total += $bill->Balance;
                        echo number_format($bill->Balance,2);?>
                    </td>
                    <td class="text-right"><?php $dis_total += $bill->Discount;
                        echo number_format($bill->Discount,2);?>
                    </td>
                    <td class="text-right"><?php $sur_total += $bill->Surcharge;
                        echo number_format($bill->Surcharge,2);?>
                    </td>
                    <td class="text-right"><?php $int_total += $bill->Interest;
                        echo number_format($bill->Interest,2);?>
                    </td>
                    <td class="text-right" style="padding-right:2px;"><?php $total += $line_total[$key];
                        echo number_format($line_total[$key],2);?>
                    </td>
                </tr>
            <?php }
                if($bill->Qtr == 1) { 
                    $Q1 += $line_total[$key];
                } else if($bill->Qtr == 2) {
                    $Q2 += $line_total[$key];
                } else if($bill->Qtr == 3) {
                    $Q3 += $line_total[$key];
                } else if($bill->Qtr == 4) {
                    $Q4 += $line_total[$key];
                }
            } ?>
            <tr><td colspan="8" style="line-height:0.2em;">&emsp;</td> </tr>
            <tr style="vertical-align:top; line-height:2.6em;font-size:11px;font-weight:bold">
                <td colspan="4" style="padding-left:324px;">TOTALS</td>
                <td class="text-right"><?=number_format($bal_total,2);?></td>
                <td class="text-right"><?=number_format($dis_total,2);?></td>
                <td class="text-right"><?=number_format($sur_total,2);?></td>
                <td class="text-right"><?=number_format($int_total,2);?></td>
                <td class="text-right" style="padding-right:2px;"><?=number_format($total,2);?></td>
            </tr>
        </tbody>
    </table>
    <table width="100%" class="content-block">
    <!-- <table width="100%" border="1"> -->
        <tbody>
            <tr>
                <td colspan="9">
                    <div style="line-height:3em;">&emsp;</div>
                    <div style="line-height:1em;display:flex;">
                        <div style="text-align:right;width:164px;">
                            <p><b>Payment Schedule</b></p>
                            <p><b>Amount</b></p>
                        </div>
                        <div style="text-align:center;width:156px;">
                            <p style="font-size:11.5px;"><b>First Quarter</b></p>
                            <p><?=$Q1 == 0 ? '-' : number_format($Q1,2);?></p>
                        </div>
                        <div style="text-align:center;width:150px;">
                            <p style="font-size:11.5px;"><b>Second Quarter</b></p>
                            <p><?=$Q2 == 0 ? '-' : number_format($Q2,2);?></p>
                        </div>
                        <div style="text-align:center;width:162px;">
                            <p style="font-size:11.5px;"><b>Third Quarter</b></p>
                            <p><?=$Q3 == 0 ? '-' : number_format($Q3,2);?></p>
                        </div>
                        <div style="text-align:center;width:156px;">
                            <p style="font-size:11.5px;"><b>Fourth Quarter</b></p>
                            <p><?=$Q4 == 0 ? '-' : number_format($Q4,2);?></p>
                        </div>
                    </div>
                </td>
            </tr>
            <tr><td colspan="9" style="line-height:0.8em;">&emsp;</td></tr>
            <tr height="28px" style="border-top:1px solid black;border-bottom:1px solid black;font-size:14px;">
                <td colspan="5" width="66%" style="padding-left:2px;">
                    <b>THIS BILL IS VALID UNTIL&ensp;
                    <?=$expiry == null ? '' : date('F d, Y',strtotime($expiry));?></b>
                </td>
                <td colspan="4" width="34%"><b>BILL AMOUNT :&emsp;&ensp;P
                    <span class="pull-right"><?=number_format($total,2);?></b></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>