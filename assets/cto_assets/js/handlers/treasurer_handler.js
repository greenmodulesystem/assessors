$(document).ready(function() {
    $('#Add_item').on('click', function() {
        if (missingValues()) {} else {
            $.ajax({
                type: "POST",
                url: baseUrl + "treasurers/add_item",
                data: {
                    Payee_ID: ID,
                    Pay_for: $('#Pay_for').val(),
                    Quantity: $('#Quantity').val(),
                },
            }).done(function(result) {
                $("#Pay_for option[value='" + $('#Pay_for').val() + "']").
                prop("disabled", "disabled");
                clearForm();
                loadGrid();
            });
        }
    });

    $('#applicant_history').on('click', function() {
        window.location.href = baseUrl + "treasurers/applicant_history/" + ID;
    });

    $('#individual_history').on('click', function() {
        window.location.href = baseUrl + "treasurers/individual_history/";
    });

});

function clearForm() {
    $('#Pay_for').val('');
    $('#Quantity').val('');
}

function missingValues() {
    var missing = 0;
    $.each($('.input-field'), function() {
        $(this).parent('div').removeClass('has-error');
        $("label[for=" + $(this).attr("id") + "]").attr('hidden', true);
        if ($(this).is(':enabled')) {
            if ($.trim($(this).val()) === '') {
                $(this).parent('div').addClass('has-error');
                $("label[for=" + $(this).attr("id") + "]").removeAttr('hidden');
                missing++;
            }
        }
    });

    if (missing != 0) {
        return true;
    }
}