<?php 
main_header();
sidebar('applicant');

$Bldg = ($profiles->Building_name != '') ? trim($profiles->Building_name).", " : '';
$Strt = ($profiles->Street != '') ? trim($profiles->Street).", " : '';
$Prk = ($profiles->Purok != '') ? trim($profiles->Purok).", " : '';
$Address1 = ucwords($Bldg).ucwords($Strt).ucwords($Prk);
$Address2 = ucwords(trim($profiles->Barangay)).", Cadiz City";
$Payor = ucwords($profiles->Tax_payer);
$Number = $profiles->Mob_num != '' ? $profiles->Mob_num : $profiles->Tel_num;
?>

<div class="content-wrapper">
    <section class="content-header">
        </br>
        <ol class="breadcrumb">
            <li><i class="fa fa-edit"></i> Assessors</li>
            <li>Businesses</li>
            <li><a href="<?php echo base_url() ?>treasurers/applicant/<?=$profiles->ID?>">Assessment</a></li>
            <li><a href="<?php echo base_url() ?>treasurers/applicant_history/<?=$profiles->ID?>">
                Assessment History</a>
            </li>
            <li class="active">View History</li>
        </ol>
    </section>
    <section class="content">
        <?php boxes()?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-default">
                    <div class="box-body box-profile">
                        <h3 class="profile-username text-center"><?=strtoupper($profiles->Business_name)?></h3>
                        <p class="text-muted text-center"><?="ASSESSMENT HISTORY (".$cycle_year.")"?></p>
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
                                <a href="<?php echo base_url() ?>treasurers/applicant_history/<?=$profiles->ID?>" class="btn btn-sm flat btn-default" >
                                    <i class="fa fa-caret-left"></i>&ensp;Back
                                </a>        
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
                        <h3 class="box-title">Assessment Details (<?=$cycle_year?>)</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Line of Business:</label></br>
                                <?php foreach($blines as $key => $bline){ ?>
                                    <p><?=$bline->Business_line?></p></br>
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
                                        <div>
                                            <p><?=number_format($bline->Capitalization,2)?>&emsp;&emsp;</p></br>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!-- <div class="col-md-3">
                                <label>&emsp;&emsp;Characteristic:</label>
                                <?php foreach($blines as $key => $bline) { ?>
                                    <p id="Char<?=$key?>">&emsp;&emsp;<?=$bline->Characteristics."(".$bline->Asset_size.")"?>
                                    </p></br>
                                <?php } ?>
                            </div> -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Size:</label>
                                    <?php foreach($blines as $key => $bline) { 
                                        $size = $bline->Characteristics."(".$bline->Asset_size.")";?>
                                        <input type="text" disabled class="form-control input-sm" value="<?=$size?>"></br>
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
                                    <?php } else { ?>
                                        <div class="gross_text">
                                            <p><?=number_format($bline->Essential ,2)?>&emsp;&emsp;</p></br>
                                        </div>
                                    <?php } 
                                    } ?>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group text-right">
                                    <label>Non-Essential:&emsp;&emsp;</label></br>
                                    <?php foreach($blines as $key => $bline){ 
                                        if($bline->Essential == null && $bline->NonEssential == null) { ?>
                                            <div>
                                                <p>(NEW)&emsp;&emsp;</p></br>
                                            </div>
                                    <?php } else if($bline->NonEssential == null) { ?>
                                        <div class="gross_text">
                                            <p><?=number_format(0,2)?>&emsp;&emsp;</p></br>
                                        </div>
                                    <?php } else { ?>
                                        <div class="gross_text">
                                            <p><?=number_format($bline->NonEssential ,2)?>&emsp;&emsp;</p></br>
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
                                    <input type="text" disabled class="form-control input-sm" value="<?=$details->Category?>">
                                    <h6><i>(Sanitary Fee)</i></h6>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Flammable Storage :</label></br>
                                    <div class="form-inline">
                                        <input id="Flame" type="checkbox" class="form-check-input" disabled
                                            <?=($details->Flammable != 0) ? 'checked' : ''?>>&emsp;
                                        <input id="Flammable" type="number" min="1" disabled
                                            class="form-control input-sm text-right"
                                            value="<?=$details->Flammable?>" style="width:70%">
                                    </div>
                                    <h6><i>(Permit Fee)</i></h6>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Payment Mode:</label>
                                    <input type="text" disabled class="form-control input-sm" value="<?=$profiles->Mode?>">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Designated Smoking Area:</label></br>
                                    <div class="form-inline">
                                        <input id="DSA" type="checkbox" class="form-check-input" disabled
                                            <?=($details->DSAFee != 0) ? 'checked' : ''?>>&emsp;
                                        <input id="DSAFee" type="number" min="1" disabled
                                            class="form-control input-sm text-right"
                                            value="<?=$details->DSAFee?>" style="width:70%">
                                    </div>
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
                        <h3 class="box-title">Assessment (<?=$cycle_year?>)</h3>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php main_footer(); ?>

<script language="javascript" src="<?php echo base_url()?>assets/scripts/noPostBack.js"></script>
<script>
    var ID = '<?php echo $ass_ID?>';

    $(document).ready(function(){
        counter();
        loadGrid();
        socket.emit('assessor', {
            assessor: 0,
        });
        $('.queue-status').attr('disabled', true);
    });

    var loadGrid = function(){
        $(document).gmLoadPage({
            url     :   baseUrl+"treasurers/view_history_payables/"+ID,
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

    socket.on('assessor', function(data){
        counter();
    });
</script>