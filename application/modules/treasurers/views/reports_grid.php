<?php if($result != null) {
    foreach($result as $key => $profile){ 
		$Tradename = $profile->Tradename == '' ? $profile->Business_name : $profile->Tradename;?>
        <tr>
            <td><?=strtoupper($profile->Tax_payer) ?></td>
            <td><?=strtoupper($Tradename) ?></td>
            <td><?=date('F d, Y',strtotime($profile->Date_application)) ?></td>
            <td>
                <button class="btn btn-default btn-sm flat check_bill" data-id="<?=$profile->ID?>"
                    <?=$profile->Opened ? 'disabled' : '';?> style="width:10vw;"><?=$profile->Opened ? 
                    $profile->Opened_by : '<i class="fa fa-search"></i>&ensp;Check Bill';?>
                </button>
            </td>
        </tr>
<?php } 
}?>

<script language="javascript">
    $('.check_bill').on('click',function(){
        var id = $(this).data('id');
        window.location.href = '<?php echo base_url() ?>treasurers/applicant/'+id;
    });
    
    $(document).ready(function() {
        disable();
    });

    // $('.queue-status').on('click', function(){
    //     disable();
    // });
    $('.queue-status').on('click', function(){
        // alert($(this).data('queue-status'));
        if(!Boolean($(this).data('queue-status'))){
            disable();
        }
       
    });
    
    function disable() {
        $.each($('.check_bill'), function() {
            $(this).css('display', '');
        });

        if(now_serving){
            $.each($('.check_bill'), function() {
                $(this).css('display', 'none');
            });

            if($.trim(departments_queue.current[0].business_name) != ''){
                var serving = $('#business-name-q').data('id');
                $.each($('.check_bill'), function() {
                    if($(this).data('id') == serving){
                        $(this).css('display', '');
                    }
                });
            }
        }
    }
</script>
                    
