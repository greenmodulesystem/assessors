var application_id = null;
var departments_queue = {
    registration: [],
    verification: [],
    releasing: [],
};

function updatecapp(id) {
    application_id = id;

}

$(document).ready(function() {

    // console.log(current_user); 
    var now_serving_applicant;

    function reloadpage() {
        location.reload();
        // alert();
    }
    $('#user-status-queue').prop('checked', now_serving);

    function open_menu() {
        $('[data-queue-status="' + Number(now_serving) + '"]').show();
        $('[data-queue-status="' + Number(!now_serving) + '"]').hide();
        if (Boolean(now_serving)) {

            $('.queue-menu').show();
            // console.log(service_details);
            $('#bplo-task  option[value="' + service_details.Task + '"]').prop("selected", true);
            $('#assessors-window  option[value="' + service_details.Window + '"]').prop("selected", true);
            $('#bplo-task').attr('disabled', true);
            $('#assessors-window').attr('disabled', true);
            // $('#assessors-window  option[value="' + service_details.Window + '"]').prop("selected", true);

            // $("#bplo-task select").val(service_details.Task).change();
            // $("#assessors-window select").val(service_details.Window).change();

        } else {
		
            $('#now-serving').html('' + ' ');
            $('.queue-menu').hide();
            $('#bplo-task').attr('disabled', false);
            $('#assessors-window').attr('disabled', false);
            $('#business-name-q').text('');
        }
        // console.log(Boolean(now_serving));
        updateall();

    }

    function update_queue(data) {

        var insert_priority = 1;
        var insert_start = 2;
        var data_to_sort = [];
        data = data.message;
        now_serving_applicant = null;
        departments_queue = {
            current: [],
            waiting: [],
        };

        // ------ REGISTRATION

        departments_queue.current = data.filter(function(item) {
            return item.user == current_user;
        });

        departments_queue.waiting = data.filter(function(item) {

            return !Boolean(item.user);
        });
        console.log(departments_queue.waiting);
        $('#business-name-q').html('');
        if (departments_queue.current.length > 0 && departments_queue.current[0].user == current_user) {

            now_serving_applicant = departments_queue.current[0].number;
            console.log(departments_queue.current[0].application);
            // console.log(now_serving_applicant);
            $('#now-serving').html('' + (departments_queue.current[0].type == 1 ? 'P' : ' Q') + now_serving_applicant);
            $('#business-name-q').html(departments_queue.current[0].business_name);
            $('#business-name-q').attr('data-id', departments_queue.current[0].application);
        } else {
            now_serving_applicant = null;
            $('#now-serving').html('' + ' ');
            get_number();
            $('#business-name-q').attr('data-id', '');

        }



        // $(departments_queue.registration).each(function(key,value){
        //     console.log(value.number);
        // });
    }

    function updateall() {
        // // alert();
        // if(Boolean(now_serving)){

        socket.emit('qlicensing', {
            qlicensing: 0,
        });
        // }

    }

    function update_monitoring() {
        socket.emit('qmonitoringqueueing', {
            qmonitoringqueueing: 0,
        });
    }

    function get_number() {
        if (departments_queue.waiting.length > 0 && !Boolean(now_serving_applicant) && now_serving) {

            $(document).gmPostHandler({
                url: "queueing/service/queueing-service/assign",
                data: {
                    number: departments_queue.waiting[0].number,
                    task: $('#bplo-task option:selected').val(),
                },
                function_call: true,
                function: updateall,

            });

        }

        // socket.emit('qlicensing', { 
        //     qlicensing: 0,
        // });
    }

    function emptycapp() {
        updatecapp(null);
    }

    function get_queue() {

        if (Boolean(now_serving)) {
            // alert('online');
            $(document).gmPostHandler({
                url: "queueing/service/queueing-service/applicants",
                function_call: true,
                function: update_queue,
                parameter: true,
            });

        }

    }

    function update_status(data) {
        service_details = data.message;
        // console.log(service_details);
        now_serving = JSON.stringify(service_details) != 'null' ? true : false;

        // if (now_serving) {
        //     $('#user-status-queue').prop('checked', true);
        // }

        open_menu();
        updateall();
    }

    function get_status() {

        $(document).gmPostHandler({
            url: "queueing/service/queueing-service/service_status",
            data: {
                status: now_serving,
                window: $('#assessors-window option:selected').val(),
                task: $('#bplo-task option:selected').val(),
            },
            function_call: true,
            function: update_status,
            parameter: true,
            function_call_on_error: true,
            error_function: [{
                function: reloadpage,
            }, ],
        });

        now_serving_applicant = null;
        $('#now-serving').html('' + ' ');

    }
    $('.queue-menu').on('change', function() {
        get_status();
    });

    function default_status() {
        $(document).gmPostHandler({
            url: "queueing/service/queueing-service/get_service_status",
            function_call: true,
            function: update_status,
            parameter: true,
        });
    }

    function reset_status() {
        now_serving = false;

    }
    default_status();

    open_menu();


    // setInterval(function() {
    //     get_queue();
    // }, 1000);


    $('.queue-status').on('click', function() {
        now_serving = !Boolean($(this).data('queue-status'));
        if(now_serving){
            $(document).gmPostHandler({
                url: "queueing/service/queueing-service/service_status",
                data: {
                    status: now_serving,
                    task: $('#bplo-task option:selected').val(),
                    window: $('#assessors-window option:selected').val(),
                },
                function_call: true,
                function: update_status,
                parameter: true,
                alert_on_error: true,
                function_call_on_error: true,
                error_function: [{
                    function: reset_status
                }]
                
            });
        }
        //open_menu();
    });

    $(document).on('change', '#bplo-task', function() {
        $(document).gmPostHandler({
            url: "queueing/service/queueing-service/service_status",
            data: {
                status: null,
                task: $('#bplo-task option:selected').val(),
                window: $('#assessors-window option:selected').val(),
            },
            function_call: true,
            function: update_status,
            parameter: true,
            add_functions: [
                { function: emptycapp }
            ]
        });
    });

    function get_service_status() {
        $(document).gmPostHandler({
            url: "queueing/service/queueing-service/service-status",
            data: {
                status: $('#user-status-queue'),
            },
            function_call: true,
            function: get_queue,
        });
    }
    socket.on('qlicensing', function(data) {
        // addtolist(data.data);
        // alert();

        if (Boolean(now_serving)) {
            get_queue();
        }
    });
    $(document).on('click', '.btn-queue-menu', function() {
        // alert($(this).data('menu'));

        if ($(this).data('menu') == 'next') {
            $(document).gmPostHandler({
                url: "queueing/service/queueing-service/done",
                data: {
                    now_serving: departments_queue.current[0].application,
                    application: application_id,
                    number: now_serving_applicant,
                    task: service_details.Task.toLowerCase()
                },
                function_call: true,
                function: updateall,
                add_functions: [
                    { function: emptycapp },
                    { function: update_monitoring }
                ]
            });
            // get_queue();

        } else if ($(this).data('menu') == 'pass') {

            $(document).gmPostHandler({
                url: "queueing/service/queueing-service/pass",
                data: {
                    number: now_serving_applicant,
                    task: service_details.Task.toLowerCase()
                },
                function_call: true,
                function: updateall,
                add_functions: [
                    { function: emptycapp }
                ]

            });

        }
    });

    $(document).on('click', '#sign-out', function() {
        $(document).gmPostHandler({
            url: "queueing/service/queueing-service/service_status",
            data: {
                status: false,
                window: $('#assessors-window option:selected').val(),
            },
            function_call: true,
            function: update_status,
            parameter: true,
        });
    });

    var reload = () =>{
        location.reload();
    }
    var action ={
        offline(param){
            // console.log(param);
            $(document).gmPostHandler({
                url: "queueing/service/queueing-service/offline",
                data: {
                    access_key: param.user_details.security_key,
                    task: $('#bplo-task option:selected').val(),
                    window: $('#assessors-window option:selected').val(),
                },
                function_call: true,
                function: clear_form,
                message: display_message,
                add_functions: [{
                        function: reload,
                    },
                ]
            });
        }

    }
    
    $(document).on('click','#submit',function(){
        $(document).gmPostHandler({
            url: 'users/service/users-service/authenticate',
            selector: '.verification-form',
            field: "field",
            function_call: true,
            function: action.offline,
            parameter: true,
            message: display_message
        });
    })


});
// -----0