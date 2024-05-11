<?php
main_header();
sidebar('ledger');

$payment_mode = sizeof($cycle_details);
echo 'test '.$payment_mode;
// $payment_mode = $payment_mode==1 ? ($payment_mode==2 ?  array('0'=>'First Half','2'=>'Second Half') : array('0'=>'Annual')) : array('0'=>'Q1','1'=>'Q2','2'=>'Q3','3'=>'Q4');
$payment_mode = ($payment_mode == 1) ? 
array('0' => 'Annual') : 
                (($payment_mode == 2) ? array('0' => 'First Half', '1' => 'Second Half') : array('0' => 'Q1', '1' => 'Q2', '2' => 'Q3', '3' => 'Q4') );
$total_bal = 0;
$total_paid = 0;
?>
<div class="content-wrapper">
    <section class="content-header">
        </br>
        <ol class="breadcrumb">
            <li><i class="fa fa-edit"></i> Assessors</li>
            <li>LEDGER</li>
            <li class="active">Assessment</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box" style="width: 79%; margin:0.5rem auto;">
                <div class="box-header">
                    <h3 class="box-title">Assessment Details</h3>
                </div>
                <div class="box-body">

                    <div class="box-body table-responsive no-padding" style="max-height: 500px;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td class="text-center"><b>ASSESSMENT DATE</b></td>
                                    <td class="text-center"><b>PAYMENT MODE</b></td>
                                    <td class="text-center"><b>DUE</b></td>
                                    <td class="text-center"><b>AMOUNT PAYABLE</b></td>
                                    <td class="text-center"><b>AMOUNT PAID</b></td>
                                    <td class="text-center"><b>OR NUMBER</b></td>
                                    <td class="text-center"><b>DATE PAID</b></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty(@$cycle_details)) {
                                    foreach (@$cycle_details as $k=>$val) {
                                ?>
                                <tr class="cycle-row-dets">
                                    <td class="text-center"><?= @$val->Assessment_Date==null?'-' : date("M j, Y h:ia", strtotime(@$val->Assessment_Date)) ?></td>
                                    <td class="text-center"><?= $payment_mode[$k] ?></td>
                                    <td class="text-center"><?= $val->Due==null? '-' : date("M j, Y", strtotime($val->Due))?></td>
                                    <td class="text-center"><?= number_format($val->Amount_Payable, 2) ?></td>
                                    <td class="text-center">
                                        <?= $val->Amount_Paid!=null ? number_format($val->Amount_Paid, 2) : '-' ?></td>
                                    <td class="text-center"><?= $val->OR_Number??'-' ?></td>
                                    <td class="text-center"><?= $val->Date_Paid==null?'-' : date("M j, Y h:ia", strtotime($val->Date_Paid))?></td>
                                </tr>
                                <?php
                                    $total_paid += $val->Amount_Paid;
                                    $total_bal += $val->Amount_Payable;
                                    }
                                } else {
                                    echo '<td class="text-center" colspan="3">No Cycle Details. Check if application is assessed and try again.</td>';
                                }
                                ?>
                                <tr>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"><strong>TOTAL :</strong></td>
                                    <td class="text-center"><strong><?= number_format($total_bal, 2) ?></strong></td>
                                    <td class="text-center"><strong><?= number_format($total_paid, 2) ?></strong></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php main_footer(); ?>
<script language="javascript" src="<?php echo base_url() ?>assets/ledger/ledger.js"></script>
<script language="javascript" src="<?php echo base_url() ?>assets/scripts/noPostBack.js"></script>