$(document).ready(function() {
    function updatesocket() {
        socket.emit('qlicensing', {
            qlicensing: 0,
        });
    }
	//alert(baseUrl);
    $('#Save1').on('click', function() {
        $(this).attr('disabled', true);
        var Fee_name = [];
        var Fee_category = [];
        var Fee_stat = [];
        var Fee = [];
        // var Balance = [];
        // var Discount = [];
        // var Surcharge = [];
        // var Interest = [];

        Object.keys(details).forEach(function(key) {
            if (details[key] != null) {
                info = details[key];
                Object.keys(info).forEach(function(key1) {
                    Fee_name.push(key1);
                    Fee_category.push(key);
                    var Fee_ID = key1.replace(/[^a-z]/gi, '');
                    Fee_stat.push($.trim($('#stat' + Fee_ID).html()));
                    Fee.push($('#' + Fee_ID).val());
                    // Balance.push($('#bal' + Fee_ID).val());
                    // Discount.push($('#dis' + Fee_ID).val());
                    // Surcharge.push($('#sur' + Fee_ID).val());
                    // Interest.push($('#int' + Fee_ID).val());
                });
            }
        });

        // $.each(Fee_name, function(key) {
        //     console.log(Fee_name[key] + ' => ' + Fee[key] + "\n");
        // });

        $.ajax({
            type: "POST",
            url: baseUrl + "treasurers/submit",
            data: {
                ID: $('#Assessment_ID').val(),
                Application_ID: Application_ID,
                Expiry: $.trim($('#Expiry').text()),
                Fee_name: Fee_name,
                Fee_category: Fee_category,
                Fee_stat: Fee_stat,
                Fee: Fee,
                bill_info: bill_info,
                Status: Status
                    // Balance: Balance,
                    // Discount: Discount,
                    // Surcharge: Surcharge,
                    // Interest: Interest

            }
        }).done(function(result) {
            if ($('#Next').is(':visible') && ($.trim($('#now-serving').text()) != '')) {
                $('#Next').click();
            }
            loadGrid();
            $('#Edit').attr('disabled', true);
            if (Status == 'RENEWAL') {
                $('#Edit_gross').css("display", "inline");
            }
            socket.emit('assessor', {
                assessor: 0,
            });
        });
    });

    $('.edit_fees').on('click', function() {
        // $('#Edit_modal').modal({
        //     backdrop: false,
        //     keyboard: false
        // });

        var name = $(this).attr('name');
        var type = $(this).data('id');
        $('#Modal_name').text(name);
        $('#Modal_type').text(type);

        var class_name = name.replace(/[^a-z]/gi, '');

        $('.modal-input' + class_name).each(function(key) {
            var line = (bill_fees.find(fees => fees.Assessment_ID == Assessment_ID &&
                fees.Line_of_business == name && fees.Qtr == key + 1));
            var amount = (type == 'Surcharge') ? line.Surcharge : (type == 'Interest') ? line.Interest : line.Balance;
            $('#Qtr' + key).val(amount);
        });

        $('#Save_fees').on('click', function() {
            var Fees_amount = [];
            $('.modal-input' + class_name).each(function(key) {
                Fees_amount.push($('#Qtr' + key).val());
            });

            $.ajax({
                type: "POST",
                url: baseUrl + "treasurers/update_fees",
                data: {
                    Assessment_ID: Assessment_ID,
                    Line: name,
                    Type: type,
                    Fees_amount: Fees_amount
                }
            }).done(function(result) {
                loadGrid();
            });
        });
    });

    $('#Edit1').on('click', function() {
        $('#Save1').removeAttr('disabled');
        $('#Approve').attr('disabled', true);
        $('.fees_text').css("display", "none");
        $('.fees_input').css("display", "block");
        $('.edit_fees').css("cursor", "pointer");
        $('.edit_fees').attr("data-target", "#Edit_modal");
    });

    $('.authenticate').on('click', function() {
        $('#Submit').attr("data-id", $(this).attr('id'));
    });

    $('#Submit').on('click', function() {
        var type = $(this).data('id');
        $.ajax({
            type: "POST",
            url: baseUrl + "treasurers/authenticate",
            data: {
                Username: $('#Username').val(),
                Password: $('#Password').val()
            }
        }).always(function(e) {
            if (e != '') {
                var data = JSON.parse(e);
                if (data.has_error == true) {
                    $.each($('.user-input'), function() {
                        $(this).parent('div').addClass('has-error');
                    });
                    $('#Error').removeAttr('hidden');
                    $('#Error').html('</br>' + data.error_message);
                } else {
                    $.each($('.user-input'), function() {
                        $(this).parent('div').removeClass('has-error');
                        $(this).parent('div').addClass('has-success');
                    });
                    $('#Error').removeAttr('hidden');
                    $('#Error').html('</br>Access granted. Action successfully executed.');
                    var Action_by = data.user_details.First_name + " " +
                        data.user_details.Middle_name[0] + ". " +
                        data.user_details.Last_name;
                    var User_Position = data.user_details.Position;
                    if (type == 'Approve') {
                        $.ajax({
                            type: "POST",
                            url: baseUrl + "treasurers/approve",
                            data: {
                                ID: $('#Assessment_ID').val(),
                                App_ID: Application_ID,
                                Action_by: Action_by,
                                User_Position: User_Position
                            }
                        }).done(function(result) {
                            $(document).gmPostHandler({
                                url: "queueing/service/queueing-service/update_applicant_payment_status",
                                data: {
                                    status: 1,
                                    application: ID,
                                },
                                function_call: true,
                                function: updatesocket
                            });
                            socket.emit('assessor', {
                                assessor: 0,
                            });
                            socket.emit('qmonitoringbusiness', {
                                qmonitoringbusiness: 0,
                            });
                            setTimeout(function() { document.location.reload(true); }, 1000);
                        });
                    } else {
                        $.ajax({
                            type: "POST",
                            url: baseUrl + "treasurers/cancel",
                            data: {
                                ID: $('#Assessment_ID').val(),
                                Action_by: Action_by,
                                User_Position: User_Position
                            }
                        }).done(function(result) {
                            $(document).gmPostHandler({
                                url: "queueing/service/queueing-service/update_applicant_payment_status",
                                data: {
                                    status: 0,
                                    application: ID,
                                },
                                function_call: true,
                                function: updatesocket
                            });
                            socket.emit('assessor', {
                                assessor: 0,
                            });
                            setTimeout(function() { document.location.reload(true); }, 1000);
                        });
                    }
                }
            }
        });
    });

    $('.input-fees').on('keyup change', function() {
        if ($(this).hasClass('input-bt')) {
            var bt_total = 0;
            $.each($('.input-bt'), function() {
                bt_total += parseInt($(this).val());
            });
            $('#Bt_val').html(bt_total + '.00').css({
                'font-weight': 'bold'
            });
        } else if ($(this).hasClass('input-rf')) {
            var rf_total = 0;
            $.each($('.input-rf'), function() {
                rf_total += parseInt($(this).val());
            });
            $('#Rf_val').html(rf_total + '.00').css({
                'font-weight': 'bold'
            });
        } else if ($(this).hasClass('input-oc')) {
            var oc_total = 0;
            $.each($('.input-oc'), function() {
                oc_total += parseInt($(this).val());
            });
            $('#Oc_val').html(oc_total + '.00').css({
                'font-weight': 'bold'
            });
        }
    });
});