<?php 
main_header();
sidebar('applicant'); 

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
            <li><a href="<?php echo base_url() ?>treasurers/applicant/<?=$profiles->ID?>">Assessment</a></li>
            <li class="active">Assessment History</li>
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
                                <a href="<?php echo base_url() ?>treasurers/applicant/<?=$profiles->ID?>" class="btn btn-sm flat btn-default" >
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
                        <div class="box-title">
                            <h3 class="box-title"><i class="fa fa-history"></i> Assessment History</h3>
                        </div>
                    </div>
                    <div class="box-body">
                        <table class="table table-striped table-bordered text-center" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Date Assessed</th>
                                    <th>Assessed By</th>
                                    <th>Approved By</th>
                                    <th style="width:15%;">Option</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($history == null){ ?>
                                    <tr class="warning text-center">
                                        <td colspan="4"><b>NO RECORD</b></td>
                                    </tr>
                                <?php } else {
                                    foreach($history as $key => $profile){ ?>
                                        <tr>
                                            <td><?=date('F d, Y',strtotime($profile->Date_assessed)) ?></td>
                                            <td><?=$profile->Assessed_by?></td>
                                            <td><?=$profile->Action_by?></td>
                                            <td>
                                                <a href="<?php echo base_url() ?>treasurers/view_history/<?php echo $profile->ID?>" 
                                                    class="btn btn-default btn-sm flat"><i class="fa fa-search"></i>&ensp;Check Bill
                                                </a>
                                            </td>
                                        </tr>
                                <?php } 
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php main_footer(); ?>

<script>
    $(document).ready(function(){
        counter();
        socket.emit('assessor', {
            assessor: 0,
        });
        $('.queue-status').attr('disabled', true);
    });

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