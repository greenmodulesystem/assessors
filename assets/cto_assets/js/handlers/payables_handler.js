$(document).ready(function() {
    var date = new Date();
    var format = date.toDateString();
    $('#Date').html(format);
    $('#Show_paid').html("0").css({ 'font-weight': 'bold' });
    $('#Change_amount').html("0").css({ 'font-weight': 'bold' });

    $('.receipt').on('click', function() {
        var OR_num = $(this).data('field');
        window.location.href = baseUrl + "treasurers/view_receipt/" + OR_num;
    });

    $('#current').on('click', function() {
        window.location.href = baseUrl + "treasurers/applicant/" + ID;
    });

    $('#go_back').on('click', function() {
        window.location = document.referrer;
    });

    $('#Amount_paid').on('change keyup', function() {
        var amount_val = $(this).val();
        var amount = $('#Show_paid');
        var change = $('#Change_amount');
        var change_val = amount_val - Total_amount;
        if (amount_val == '') {
            amount.html("0").css({
                'font-weight': 'bold',
                'color': 'black'
            });
            change.html("0").css({
                'font-weight': 'bold',
                'color': 'black'
            });
        } else {
            if (amount_val < Total_amount) {
                amount.html(amount_val).css({
                    'font-weight': 'bold',
                    'color': 'red'
                });
                change.html(change_val).css({
                    'font-weight': 'bold',
                    'color': 'red'
                });
            } else {
                amount.html(amount_val).css({
                    'font-weight': 'bold',
                    'color': 'black'
                });
                change.html(change_val).css({
                    'font-weight': 'bold',
                    'color': 'black'
                });
            }
        }
    });

    $('#View_receipt').on('click', function() {
        $('.rcv-group').removeClass("danger");
        $('.pay-group').removeClass("danger");
        var Pay_for = [];
        var Quantity = [];
        var Amount_to_pay = [];
        var update_CE = false;
        var update_CH = false;

        // if (show_engineers === '1') {
        //     Object.keys(engineers).forEach(function(key) {
        //         Pay_for.push(engineers[key].Pay_type);
        //         Quantity.push(1);
        //         Amount_to_pay.push(engineers[key].Rate);
        //     });
        //     update_CE = true;
        // }

        if (show_healths === '1') {
            Pay_for.push("Sanitary Fee");
            Quantity.push(1);
            Amount_to_pay.push(healths[0].Sanitary_fee);
            Pay_for.push("Health Fee");
            Quantity.push(healths[0].Card_qty);
            $Health_fee = healths[0].Card_qty * healths[0].Fee;
            Amount_to_pay.push($Health_fee);
            update_CH = true;
        }

        if (show_addons === '1') {
            Object.keys(add_ons).forEach(function(key) {
                Pay_for.push(add_ons[key].Pay_for);
                Quantity.push(add_ons[key].Quantity);
                Amount_to_pay.push(amount[key]);
            });
        }

        if ($('#Amount_paid').val() < Total_amount) {
            $('.pay-group').addClass("danger");
        } else if ($.trim($('#Received_by').val()) === '') {
            $('.rcv-group').addClass("danger");
        } else {
            $.ajax({
                type: "POST",
                url: baseUrl + "treasurers/receive_payment",
                data: {
                    ID: $('#ID').val(),
                    Payee: Payee,
                    Total_amount: Total_amount,
                    Paid_amount: $('#Amount_paid').val(),
                    Change_amount: $('#Change_amount').html(),
                    Received_by: $('#Received_by').val(),
                    Pay_for: Pay_for,
                    Quantity: Quantity,
                    Amount_to_pay: Amount_to_pay,
                    update_CE: update_CE,
                    update_CH: update_CH,
                },
            }).done(function(result) {
                window.location.href = baseUrl + "treasurers/view_receipt/" + 1;
            });
        }
    });
});