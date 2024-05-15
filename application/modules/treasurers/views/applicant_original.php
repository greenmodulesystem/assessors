<?php 
main_header();
sidebar('applicant');

$saved = (empty($assessment->ID)) ? false : true;
$No_record = (empty($details->ID)) ? true : false;

$Bldg = ($profiles->Building_name != '') ? trim($profiles->Building_name).", " : '';
$Strt = ($profiles->Street != '') ? trim($profiles->Street).", " : '';
$Prk = ($profiles->Purok != '') ? trim($profiles->Purok).", " : '';
$Address1 = ucwords($Bldg).ucwords($Strt).ucwords($Prk);
$Address2 = ucwords(trim($profiles->Barangay)).", SAGAY City";
$Payor = ucwords($profiles->Tax_payer);
$Number = $profiles->Mob_num != '' ? $profiles->Mob_num : $profiles->Tel_num;
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
        <?php boxes()?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body box-profile">
                        <h3 class="profile-username text-center"><?=strtoupper($profiles->Business_name)?></h3>
                        <p class="text-muted text-center"><?=$profiles->Status." (".$profiles->Cycle_date.")"?></p>
                        <div class="list-group list-group-unbordered text-center">
                            <div class="list-group-item col-md-3 col-sm-12 col-xs-12">
                                <strong><i class="fa fa-user margin-r-5"></i>Proprietor</strong>
                                <p class="text-muted"><?=$Payor?></p>
                            </div>
                            <div class="list-group-item col-md-6 col-sm-12 col-xs-12">
                                <strong><i class="fa fa-map-marker margin-r-5"></i>Address</strong>
                                <p class="text-muted"><?=$Address1.$Address2?></p>
                            </div>
                            <div class="list-group-item col-md-3 col-sm-12 col-xs-12">
                                <strong><i class="fa fa-phone margin-r-5"></i>Contact No.</strong>
                                <p class="text-muted">&thinsp;<?=$Number?></p>
                            </div>
                            <div class="pull-left"></br>
                                <a href="<?php echo base_url() ?>treasurers/applicant_search" 
                                    class="btn btn-sm flat btn-default" >
                                    <i class="fa fa-caret-left"></i>&ensp;Back
                                </a>        
                            </div>
                            <div class="pull-right"></br>
                                <button id="applicant_history" class="btn btn-sm flat btn-default">
                                    View Assessment History&ensp;<i class="fa fa-caret-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php if($profiles->Rate == null) {?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <table class="table table-condensed text-center" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td class="warning"> No bill from the City Engineer's Office yet. Please process your City Engineer's bill for your current application first.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <!-- <?php } else if($profiles->Cenro == null) {?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <table class="table table-condensed text-center" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td class="warning"> No bill from the Cty Environment & Natural Resources Office yet. Please process your CENRO's bill for your current application first.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> -->
    <?php } else if($profiles->Ready == null) {?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body">
                        <table class="table table-condensed text-center" cellspacing="0" width="100%">
                            <tbody>
                                <tr>
                                    <td class="warning"> Not yet verified by BPLO. To be assessed in <?=$profiles->Cycle_date?>.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">PART I. Assessment Details (<?=$profiles->Cycle_date?>)</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Line of Business:</label></br>
                                <?php foreach($blines as $key => $bline){ ?>
                                    <p><?php if($bline->Assessment_asset_ID == null) { ?>
                                            <input id="xmp<?=$key?>" type="checkbox" class="form-check-input exempt">
                                        <?php } else { ?>
                                            <input id="xmp<?=$key?>" type="checkbox" class="form-check-input exempt" disabled
                                            <?=($bline->Exempted) ? 'checked' : ''?>>
                                        <?php } ?>
                                        <?=$bline->Business_line?>
                                    </p></br>
                                <?php } ?>
                            </div>
                            <div class="col-md-1">
                                <label>Units:</label></br>
                                <?php foreach($blines as $key => $bline){ ?>
                                    <p><?=$bline->NoOfUnits?></p></br>
                                <?php } ?>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group text-right">
                                    <label>Capitalization:&emsp;&emsp;</label></br>
                                    <?php foreach($blines as $key => $bline){ ?>
                                        <div class="<?=($bline->Essential == null && $bline->NonEssential == null) ? 'cap_text' : '';?>">
                                            <p><?=number_format($bline->Capitalization,2)?>&emsp;&emsp;</p></br>
                                        </div>
                                        <div class="<?=($bline->Essential == null && $bline->NonEssential == null) ? 'cap_input' : '';?>"
                                            style="display:none">
                                            <input class="input-field cap-edit form-control input-sm 
                                                text-right" type="number" min="1" disabled step="0.01"
                                                value="<?=$bline->Capitalization?>" id="cap<?=$key?>" data-id="<?=$key?>"></br>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <label>Characteristic:</label>
                                <?php foreach($blines as $key => $bline) { ?>
                                    <p id="Char<?=$key?>"><?=$bline->Characteristics."(".$bline->Asset_size.")"?>
                                    </p></br>
                                <?php } ?>
                            </div> -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Size:</label>
                                    <?php foreach($blines as $key => $bline) { ?>
                                        <?php if($bline->Assessment_asset_ID == null) { ?>
                                            <select id="size<?=$key?>" class="input-field form-control input-sm">
                                                <option disabled selected value="">Select asset size</option>
                                        <?php } else { ?>
                                            <select id="size<?=$key?>" class="input-field form-control input-sm" disabled>
                                                <option selected hidden value="<?=$bline->Assessment_asset_ID?>">
                                                    <?=$bline->Characteristics."(".$bline->Asset_size.")"?>
                                                </option>
                                                <option disabled value="">Select business type</option>
                                        <?php } ?> 
                                            <?php  foreach($asset_size as $key => $asset){ ?>
                                                <option value="<?=$asset->ID?>">
                                                    <?=$asset->Characteristics."(".$asset->Asset_size.")"?>
                                                </option>
                                            <?php } ?>
                                        </select></br>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group text-right">
                                    <label>Essential:&emsp;&emsp;</label></br>
                                    <?php foreach($blines as $key => $bline){ 
                                        if($bline->Essential == null && $bline->NonEssential == null) { ?>
                                            <div>
                                                <p>(NEW)&emsp;&emsp;</p></br>
                                            </div>
                                    <?php } else if($bline->Essential == null) { ?>
                                        <div class="gross_text">
                                            <p><?=number_format(0,2)?>&emsp;&emsp;</p></br>
                                        </div>
                                        <div class="gross_input" style="display:none">
                                            <input class="input-field gross-edit form-control input-sm text-right" 
                                                type="number" min="1" disabled value="0" step="0.01"
                                                id="ess<?=$key?>"></br>
                                        </div>
                                    <?php } else { ?>
                                        <div class="gross_text">
                                            <p><?=number_format($bline->Essential ,2)?>&emsp;&emsp;</p></br>
                                        </div>
                                        <div class="gross_input" style="display:none">
                                            <input class="input-field gross-edit form-control input-sm text-right" 
                                                type="number" min="1" disabled value="<?=$bline->Essential ?>" step="0.01"
                                                id="ess<?=$key?>"></br>
                                        </div>
                                    <?php } 
                                    } ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group text-right">
                                    <label>Non-Essential:&emsp;</label></br>
                                    <?php foreach($blines as $key => $bline){ 
                                        if($bline->Essential == null && $bline->NonEssential == null) { ?>
                                            <div>
                                                <p>(NEW)&emsp;</p></br>
                                            </div>
                                    <?php } else if($bline->NonEssential == null) { ?>
                                        <div class="gross_text">
                                            <p><?=number_format(0,2)?>&emsp;</p></br>
                                        </div>
                                        <div class="gross_input" style="display:none">
                                            <input class="input-field gross-edit form-control input-sm text-right" 
                                                type="number" min="1" disabled value="0" step="0.01"
                                                id="non<?=$key?>"></br>
                                        </div>
                                    <?php } else { ?>
                                        <div class="gross_text">
                                            <p><?=number_format($bline->NonEssential ,2)?>&emsp;</p></br>
                                        </div>
                                        <div class="gross_input" style="display:none">
                                            <input class="input-field gross-edit form-control input-sm text-right" 
                                                type="number" min="1" disabled value="<?=$bline->NonEssential ?>" step="0.01"
                                                id="non<?=$key?>"></br>
                                        </div>
                                    <?php } 
                                    } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>No. of Employees :</label>
                                    <label for="Employees" hidden>
                                        <i class="fa fa-times-circle-o"></i> Required
                                    </label>
                                    <input id="Employees" type="number" min="1" disabled
                                        class="input-field form-control input-sm text-right"
                                        value="<?=$profiles->Total_number_employees?>" style="width:70%">
                                    <h6><i>(Health Fee)</i></h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Business Size:</label>
                                    <?php $bus_type = '';
                                        foreach($solid_fees as $solid) {
                                            if($solid->ID == $profiles->Cenro) {
                                                $bus_type = $solid->Size.' (Solid Waste Fee: P'.$solid->Waste_fee.')';
                                            }
                                        }
                                    ?>
                                    <input type="text" disabled class="form-control input-sm" value="<?=$bus_type?>">
                                    <h6><i>(Solid Waste Fee)</i></h6>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Business Type:</label>
                                    <label for="Category_ID" hidden>
                                        <i class="fa fa-times-circle-o"></i> Required
                                    </label>
                                    <?php if($No_record) { ?>
                                        <select id="Category_ID" class="input-field form-control input-sm">
                                            <option disabled selected value="">Select business type</option>
                                    <?php } else { ?>
                                        <select id="Category_ID" class="input-field form-control input-sm" disabled>
                                            <option selected hidden value="<?=$details->Category_ID?>">
                                                <?=$details->Category?>
                                            </option>
                                            <option disabled value="">Select business type</option>
                                    <?php } ?> 
                                        <?php  foreach($sanitary_fees as $key => $sanitary){ ?>
                                            <option value="<?=$sanitary->ID?>"><?=$sanitary->Category?></option>
                                        <?php } ?>
                                    </select>
                                    <h6><i>(Sanitary Fee)</i></h6>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Flammable Storage :</label></br>
                                    <div class="form-inline">
                                    <?php if($No_record) { ?>
                                        <input id="Flame" type="checkbox" class="form-check-input">&emsp;
                                        <input id="Flammable" type="number" min="1" disabled
                                            class="form-control input-sm text-right"
                                            value="" style="width:70%">
                                    <?php } else { ?>
                                        <input id="Flame" type="checkbox" class="form-check-input" disabled
                                            <?=($details->Flammable != 0) ? 'checked' : ''?>>&emsp;
                                        <input id="Flammable" type="number" min="1" disabled
                                            class="form-control input-sm text-right"
                                            value="<?=$details->Flammable?>" style="width:70%">
                                    <?php } ?> 
                                    </div>
                                    <h6><i>(Permit Fee)</i></h6>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Payment Mode:</label>
                                    <select id="Payment_mode_ID" class="input-field form-control input-sm" disabled style="width:90%">
                                        <option selected hidden value="<?=$profiles->Payment_mode_ID?>">
                                            <?=$profiles->Mode?>
                                        </option>
                                        <option disabled value="">Select payment mode</option>
                                    <?php  foreach($payment_mode as $key => $mode){ ?>
                                        <option value="<?=$mode->ID?>"><?=$mode->Mode?></option>
                                    <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button id="Save" class="btn btn-sm flat btn-primary" <?=($No_record) ?
                                        '' : 'disabled';?>>
                                        <i class="fa fa-clipboard"></i><span>&ensp;Assess</span>
                                    </button>
                                    <input id="ID" hidden value="<?=($No_record) ? '' : $details->ID;?>">
                                    <button id="Edit" class="btn btn-sm flat btn-success" <?=($saved) ? 
                                        'disabled' : '';?>>
                                        <i class="fa fa-edit"></i><span>&ensp;Edit</span>
                                    </button>&emsp;
                                    <b>Note: <font color='red'>To exempt a Business Line, please check the box beside it.</font></b>
                                    <div class="pull-right">
                                        <button id="Edit_gross" class="btn btn-sm flat btn-danger" 
                                            style="display:<?=($profiles->Status == 'RENEWAL' && $saved) 
                                            ? 'inline' : 'none';?>" <?=$collection == 0 ? '' : 'disabled'?> 
                                            data-target="#Authenticate_modal" data-toggle="modal" 
                                            data-keyboard="false" data-backdrop="static">
                                            <i class="fa fa-edit"></i><span>&ensp;Edit Gross</span>
                                        </button>
                                        <button id="Save_gross" class="btn btn-sm flat btn-primary" style="display:none">
                                            <i class="fa fa-save"></i><span>&ensp;Save Gross</span>
                                        </button> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="Authenticate_modal" class="modal fade">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">
                                        <i class="fa fa-exclamation-circle"></i>&ensp;User Permission Required
                                    </h4>
                                </div>
                                <div class="modal-body text-center">
                                    <div class="form-group has-feedback">
                                        <input id="Username1" class="user-input form-control input-sm"
                                            placeholder="Username" type="text" autofocus>
                                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <input id="Password1" class="user-input form-control input-sm"
                                            placeholder="Password" type="password">
                                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                        <label id="Error1" hidden></label>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-primary btn-sm flat" id="Authenticate">
                                        <i class="fa fa-arrow-right"></i>&ensp;Submit
                                    </button>
                                    <button class="btn btn-danger btn-sm flat" id="Close_auth"
                                        style="display:none" data-dismiss="modal">
                                        <i class="fa fa-times"></i>&ensp;Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">PART II. Assessment (<?=$profiles->Cycle_date?>)</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table id="bills" class="table table-striped table-bordered
                                    table-condensed" 
                                    cellspacing="0" width="100%">
                                    <thead><tr>
                                        <th colspan="3" class="text-center" style="
                                            border:2px solid gray;">ASSESSMENT INFORMATION
                                        </th>
                                        <th colspan="5" class="text-center" style="
                                            border:2px solid gray;">BILLING INFORMATION
                                        </th>
                                    </tr></thead>
                                    <thead style="font:bold 10px arial black, sans-serif;"><tr>
                                        <th class="text-center" style="width:31%;
                                            border-left:2px solid gray;
                                            border-top:2px solid gray;">TAX/FEES
                                        </th>
                                        <th class="text-center" style="width:9%;
                                            border-top:2px solid gray;">STATUS</th>
                                        <th class="text-center" style="width:11%;
                                            border-right:2px solid gray;
                                            border-top:2px solid gray;">AMOUNT
                                        </th>
                                        <th class="text-center" style="width:11%;
                                            border-top:2px solid gray;">BALANCE DUE</th>
                                        <th class="text-center" style="width:9%;
                                            border-top:2px solid gray;">DISCOUNT</th>
                                        <th class="text-center" style="width:9%;
                                            border-top:2px solid gray;">SURCHARGE</th>
                                        <th class="text-center" style="width:9%;
                                            border-top:2px solid gray;">INTEREST</th>
                                        <th class="text-center" style="width:11%;
                                            border-top:2px solid gray;
                                            border-right:2px solid gray;">TOTAL</th>
                                    </tr></thead>
                                    <tbody id="bills_body">
                                    </tbody>
                                </table>
                                <h5> * Please finalize all fees before clicking <font color="#367fa9">
                                    <b>SAVE</b></font>. Editing of <b>PART I <i>(Assessment Details)</i>
                                    </b> and <b>Assessment Information</b> will be disabled after saving.
                                </h5>
                                <h5> * Only authorize personnel can <font color="orange"><b>APPROVE</b>
                                    </font> assessments. Editing of <b>PART II <i>(Assessment)</i></b> 
                                    will be disabled after approving.
                                </h5>
                                <h5> * Only authorize personnel can <font color="red"><b>EDIT GROSS</b>.
                                    </font> Editing of <b>PART I <i>(Assessment Details)</i></b> and <b>
                                    Assessment Information</b> can also be done if necessary.
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    </section>
</div>

<?php main_footer(); ?>

<script language="javascript" src="<?php echo base_url()?>assets/cto_assets/js/handlers/assessment_details_handler.js"></script>
<script language="javascript" src="<?php echo base_url()?>assets/scripts/noPostBack.js"></script>
<script language="javascript">

    var baseUrl = '<?php echo base_url()?>';
    var ID = '<?php echo $profiles->ID?>';
    var Payment_mode = '<?php echo $profiles->Payment_mode_ID?>';
    var No_record = '<?php echo $No_record?>';
    var blines = <?php echo json_encode($blines)?>;
    var asset_size = <?php echo json_encode($asset_size)?>;

    $(document).ready(function(){
        loadGrid();
        counter();
        socket.emit('assessor', {
            assessor: 0,
        });
        updatecapp(ID);
        $('.queue-status').attr('disabled', true);
    });

    var loadGrid = function(){
        $(document).gmLoadPage({
            url     :   baseUrl+"treasurers/applicant_payables/"+ID,
            load_on :   "#bills_body"
        });
    }

    var counter = function(){
        $.ajax({
            type: "GET",
            url: baseUrl + "treasurers/counter",
        }).done(function(data) {
            data = JSON.parse(data);
            $('#Billing_count').text(data['Billing']);
            $('#Approval_count').text(data['Approval']);
            $('#Approved_count').text(data['Approved']);
            $('#Cancelled_count').text(data['Cancelled']);
        });
    }
</script>