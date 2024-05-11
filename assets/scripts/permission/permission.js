function show_form(){
    // $('#user_modal').modal({ show: true, backdrop: 'static', keyboard: false});
    // $('#message').empty();
    $('.verification-form').each(function(){
        $(this).val('');
    });
}
function clear_form(){
    setTimeout(function(){
        $('.verification-form').each(function(){
            $(this).val('');
        });
        $('#message').empty();
        $('#user_modal').modal('hide');
    },1500);
}
function display_message(msg){
    $('#message').empty();
    $('#message').append(msg);
}

function display_info(msg,status=''){
    $('#warning-info').html('');
    $('#status-info').html('');
    // alert(msg);
    $('#warning-info').append(msg);
    $('#status-info').append(status.toUpperCase());
}


