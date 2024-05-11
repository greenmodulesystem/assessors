<?php
main_header();
sidebar('ledger');
?>
<div class="content-wrapper">
    <section class="content-header">
        </br>
        <ol class="breadcrumb">
            <li><i class="fa fa-edit"></i> Assessors</li>
            <li>LEDGER</li>
            <li class="active">ALL CYCLE</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="box" style="width: 79%; margin:0.5rem auto;">
                <div class="box-header">
                    <h3 class="box-title">Cycle Information</h3>
                </div>
                <div class="box-body">

                    <div class="box-body table-responsive no-padding" style="max-height: 500px;">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <td class="text-center" width="34%">CYCLE</td>
                                    <td class="text-center" width="33%">TOTAL ASSESSED</td>
                                    <td class="text-center" width="33%">AMOUNT PAYABLE</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty(@$cycle_assessment)) {
                                    foreach (@$cycle_assessment as $val) {
                                ?>
                                        <tr class="cycle-row" data-cycleid="<?= @$val->Cycle_ID ?>">
                                            <td class="text-center"><?= $val->Cycle_date ?></td>
                                            <td class="text-center"><?= number_format($val->total_assessed, 2) ?></td>
                                            <td class="text-center"><?= number_format($val->amount_payable, 2) ?></td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<td class="text-center" colspan="3">No Assessment has been made yet....</td>';
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

<script language="javascript" src="<?php echo base_url() ?>assets/ledger/ledger.js"></script>
<script language="javascript" src="<?php echo base_url() ?>assets/scripts/noPostBack.js"></script>