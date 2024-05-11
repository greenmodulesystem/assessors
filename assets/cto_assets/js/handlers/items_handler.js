$(document).ready(function() {
    var date = new Date();
    var format = date.toDateString();
    $('#Date').html(format);
    $('#Show_paid').html("0").css({ 'font-weight': 'bold' });
    $('#Change_amount').html("0").css({ 'font-weight': 'bold' });

    $('.remove').on('click', function() {
        var Item_ID = $(this).data('id');
        $.ajax({
            type: "POST",
            url: baseUrl + "treasurers/remove_item",
            data: {
                Item_ID: Item_ID,
            }
        }).done(function(result) {
            $("#Pay_for option[value='" + $('#Pay_for').val() + "']").prop("disabled", "enabled");
            document.location.reload(true);
        });
    });

    $('#receipt').on('click', function() {
        var OR_num = $(this).data('field');
        window.location.href = baseUrl + "treasurers/view_receipt/" + OR_num;
    });

    $('#To_payments').on('click', function() {
        window.location.href = baseUrl + "treasurers/individual/";
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
        $('.cus-group').removeClass("danger");
        var Pay_for = [];
        var Quantity = [];
        var Amount_to_pay = [];

        if (show_items === '1') {
            Object.keys(items).forEach(function(key) {
                Pay_for.push(items[key].Pay_for);
                Quantity.push(items[key].Quantity);
                Amount_to_pay.push(amount[key]);
            });
        }

        if ($('#Amount_paid').val() < Total_amount) {
            $('.pay-group').addClass("danger");
        } else if ($.trim($('#Payee').val()) === '') {
            $('.cus-group').addClass("danger");
        } else if ($.trim($('#Received_by').val()) === '') {
            $('.rcv-group').addClass("danger");
        } else {
            $.ajax({
                type: "POST",
                url: baseUrl + "treasurers/receive_payment",
                data: {
                    ID: ID,
                    Payee: $('#Payee').val(),
                    Total_amount: Total_amount,
                    Paid_amount: $('#Amount_paid').val(),
                    Change_amount: $('#Change_amount').html(),
                    Received_by: $('#Received_by').val(),
                    Pay_for: Pay_for,
                    Quantity: Quantity,
                    Amount_to_pay: Amount_to_pay,
                },
            }).done(function(result) {
                window.location.href = baseUrl + "treasurers/view_receipt/" + 1;
            });
        }
    });
});