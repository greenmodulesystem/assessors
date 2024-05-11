<?php
main_header();
sidebar('applicant');
$cat = "";

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
            <li>Tax Table</li>
            <li class="active">Edit Table</li>
        </ol>
    </section>
    <section class="content">
        <div class="container" style="width: 70%;">
            <!-- MANUFACTURERS -->
            <div class="row">
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 5px green;">
                    <h4>TAX TABLE FOR MANUFACTURERS/ASSEMBLERS/REPACKERS/PROCESSORS/BREWERS/DISTILLERS/RECTIFIERS/COMPOUNDERS OF LIQUORS,DISTILLED SPIRITS AND WINES</h4>
                </div>
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 1px green;">
                    <table class="table table-striped table-bordered manufacturers" style="max-height: 450px;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">-</th>
                                <th class="text-center">Range From</th>
                                <th class="text-center">Range To</th>
                                <th class="text-center">Tax</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($m_list)) {
                                foreach ($m_list as $val) {
                            ?>
                                    <tr>
                                        <td class="text-center"><input class="id" type="text" value="<?= @$val->ID ?>" hidden></td>
                                        <td contenteditable="" class="text-center gross_from"><?= @$val->Gross_from ?></td>
                                        <td contenteditable="" class="text-center gross_to"><?= @$val->Gross_to ?></td>
                                        <td contenteditable="" class="text-center tax"><?= @$val->Tax ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 text-right flex" style="padding: 10px; background-color: white;">
                    <button type="button" class="btn btn-success add">
                        Save
                    </button>
                    <!-- <button type="button" class="btn btn-warning alertbtn">
                        Add row
                    </button> -->
                </div>
            </div>

            <!-- DEALERS -->
            <div class="row" style="margin-top: 0.6rem;">
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 5px green;">
                    <h4>TAX TABLE FOR WHOLESALERS/DISTRIBUTORS/DEALERS/SUPPLIERS</h4>
                </div>
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 1px green;">
                    <table class="table table-striped table-bordered dealers" style="max-height: 450px;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">-</th>
                                <th class="text-center">Range From</th>
                                <th class="text-center">Range To</th>
                                <th class="text-center">Tax</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($d_list)) {
                                foreach ($d_list as $val) {
                            ?>
                                    <tr>
                                        <td class="text-center"><input class="id" type="text" value="<?= @$val->ID ?>" hidden></td>
                                        <td contenteditable="" class="text-center gross_from"><?= @$val->Gross_from ?></td>
                                        <td contenteditable="" class="text-center gross_to"><?= @$val->Gross_to ?></td>
                                        <td contenteditable="" class="text-center tax"><?= @$val->Tax ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 text-right flex" style="padding: 10px; background-color: white;">
                    <button type="button" class="btn btn-success add">
                        Save
                    </button>
                    <!-- <button type="button" class="btn btn-warning alertbtn">
                        Add row
                    </button> -->
                </div>
            </div>

            <!-- RETAILERS -->
            <div class="row" style="margin-top: 0.6rem;">
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 5px green;">
                    <h4>TAX RETAILERS</h4>
                    <p style="color: gray;"><i>Note: The second row tax will follow the formula :<span style="color: black;"> (gross_range x gross_tax of first row) + excess x gross_tax of second row </span> . Where excess is :<span style="color: black;"> Gross - gross_range of first row. </span> Please enter the tax values with no "%". <span style="color: black;"> Ex.: 2.2% = 2.2 </span> </i></p>
                </div>
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 1px green;">
                    <table class="table table-striped table-bordered dealers" style="max-height: 450px;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">Gross Range</th>
                                <th class="text-center">Tax</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <!-- <td class="text-center"><input class="id" type="text" value="" hidden></td> -->
                                <td class="text-center">Lesser than</td>
                                <td contenteditable="" class="text-center " id="gross_less"><?= @$retail->gross_less ?></td>
                                <td contenteditable="" class="text-center " id="tax-r"><?= @$retail->tax ?></td>
                            </tr>
                            <tr>
                                <!-- <td class="text-center"><input class="id" type="text" value="" hidden></td> -->
                                <td class="text-center">Greater than</td>
                                <td contenteditable="" class="text-center " id="gross_more"><?= @$retail->gross_more ?></td>
                                <td contenteditable="" class="text-center " id="tax-excess"><?= @$retail->tax_excess ?></td>
                            </tr>
                    </table>
                </div>
                <div class="col-12 text-right flex" style="padding: 10px; background-color: white;">
                    <button type="button" class="btn btn-success add-retail" data-id="<?= @$retail->ID ?>">
                        Save
                    </button>
                </div>
            </div>

            <!-- SERVICES -->
            <div class="row" style="margin-top: 0.6rem;">
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 5px green;">
                    <h4>TAX TABLE FOR SERVICES/CONTRACTORS/SERVICE ESTABLISHMENTS</h4>
                </div>
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 1px green;">
                    <table class="table table-striped table-bordered services" style="max-height: 450px;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">-</th>
                                <th class="text-center">Range From</th>
                                <th class="text-center">Range To</th>
                                <th class="text-center">Tax</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($s_list)) {
                                foreach ($s_list as $val) {
                            ?>
                                    <tr>
                                        <td class="text-center"><input class="id" type="text" value="<?= @$val->ID ?>" hidden></td>
                                        <td contenteditable="" class="text-center gross_from"><?= @$val->Gross_from ?></td>
                                        <td contenteditable="" class="text-center gross_to"><?= @$val->Gross_to ?></td>
                                        <td contenteditable="" class="text-center tax"><?= @$val->Tax ?></td>
                                    </tr>
                            <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-12 text-right flex" style="padding: 10px; background-color: white;">
                    <button type="button" class="btn btn-success add">
                        Save
                    </button>
                    <!-- <button type="button" class="btn btn-warning alertbtn">
                        Add row
                    </button> -->
                </div>
            </div>

            <!-- OTHER TAXES -->

            <div class="row" style="margin-top: 0.6rem;">
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 5px green;">
                    <h4>OTHER TAXES</h4>
                    <!-- <p style="color: gray;"><i>Note: The second row tax will follow the formula :<span style="color: black;"> (gross_range x gross_tax of first row) + excess x gross_tax of second row </span> . Where excess is :<span style="color: black;"> Gross - gross_range of first row. </span> Please enter the tax values with no "%". <span style="color: black;"> Ex.: 2.2% = 2.2 </span> </i></p> -->
                </div>
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 1px green;">
                    <table class="table table-striped table-bordered dealers" style="max-height: 450px;" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">CATEGORY</th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                                <th class="text-center"></th>
                                <th class="text-center">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (@$other as $val) { ?>
                                <tr data-id="<?= @$val->ID ?>">
                                    <td class="text-center" id="category"><?= @$val->Category ?></td>
                                    <td contenteditable="" class="text-center percent1" style="border-bottom: black solid 2px;"><?= @$val->percent1 ?></td>
                                    <td class="text-center"><?= @$val->Category == 'MANUFACTURER' || @$val->Category == 'DEALER' ||
                                                                @$val->Category == 'CONTRACTOR' ? 'of tax of the last row and' : 'of' ?></td>
                                    <td contenteditable="" class="text-center percent2" style="border-bottom: black solid 2px;"><?= @$val->percent2 ?></td>
                                    <td class="text-center "><?= @$val->Category == 'MANUFACTURER' || @$val->Category == 'DEALER' ||
                                                                    @$val->Category == 'CONTRACTOR' ? 'of excess' : 'of Gross' ?></td>
                                    <td class="text-center"><button type="button" class="btn btn-success save-other">SAVE</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <!-- <div class="col-12 text-right flex" style="padding: 10px; background-color: white;">
                    <button type="button" class="btn btn-success add-financial" data-id="<?= @$retail->ID ?>">
                        Save
                    </button>
                </div> -->
            </div>

            <!-- OTHER TAXES FIXED -->

            <div class="row" style="width: 75%; margin:auto; margin-top: 0.6rem; ">
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 5px green;">
                    <h4>OTHER FIXED TAXES</h4>
                </div>
                <div class="col-12" style="background-color: white; padding: 0.5rem; padding-left:20px; border-bottom: solid 1px green;">
                    <table class="table table-striped table-bordered dealers" style="max-height: 450px; " cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">Business Line</th>
                                <th class="text-center">Fee</th>
                                <th class="text-center">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fixed as $category => $items) { ?>
                                <tr style="font-weight: 600;">
                                    <td colspan="3"><?= $category ?></td>
                                </tr>
                                <?php foreach ($items as $item) { ?>
                                    <tr data-id=" <?= isset($item['ID']) ? $item['ID'] : '' ?>" >
                                        <td class="text-center description"><?= isset($item['Description']) ? $item['Description'] : '' ?></td>
                                        <td contenteditable="" class="text-center fee"><?= isset($item['Fee']) ? $item['Fee'] : '' ?></td>
                                        <td class="text-center"><button type="button" class="btn btn-success save-fixed">SAVE</td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-10 flex" style="padding: 15px; background-color: white;">
                    <select id="category-fixed" style="margin-right: 18px;">
                        <option value="" disabled selected>Select Category</option>
                        <?php
                            foreach($fixed as $cat=>$val){
                                ?>
                            <option value="<?=$cat?>"><?=$cat?></option>
                                <?php
                            }
                        ?>
                    </select>
                    <input id="bline" type="text" placeholder="Enter Business line" class="mr-2" style="margin-right: 18px; width:295px;">
                    <input id="fee-new" type="number" placeholder="Enter Fee" class="mr-2" style="margin-right: 18px;">
                </div>
                <div class="col-sm-2" style="padding: 10px; background-color: white;">
                    <button type="button" class="btn btn-info new-fix">
                        Add New Tax
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<?php main_footer(); ?>

<script language="javascript" src="<?php echo base_url() ?>assets/mgmt/tax.js"></script>
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