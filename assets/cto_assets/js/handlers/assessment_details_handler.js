$(document).ready(function() {
    $('#Save,#Save_gross').on('click', function() {
        var Business_line_ID = [];
        var Capitalization = [];
        var Essential = [];
        var NonEssential = [];
        var Assessment_asset_ID = [];
        var mp_ID = [];
        var Exempted = [];

        Object.keys(blines).forEach(function(key) {
            Business_line_ID.push(blines[key].ID);
            Capitalization.push($('#cap' + key).val());
            var ess_val = ($('#ess' + key).val() == null || $('#ess' + key).val() == 0) ?
                null : $('#ess' + key).val();
            var non_val = ($('#non' + key).val() == null || $('#non' + key).val() == 0) ?
                null : $('#non' + key).val();
            Essential.push(ess_val);
            NonEssential.push(non_val);
            Assessment_asset_ID.push($('#size' + key).val());
            mp_ID.push($('#mp' + key).val()); //added 5824 alob
            var exempt = $('#xmp' + key).is(':checked') ? 1 : 0;
            Exempted.push(exempt);
        });
        if (missingValues()) {} else {
            $.ajax({
                type: "POST",
                url: baseUrl + "treasurers/save_details",
                data: {
                    ID: $('#ID').val(),
                    Application_ID: ID,
                    Employees: $('#Employees').val(),
                    Solid_waste_ID: $('#Solid_waste_ID').val(), //TEMP FUNCTION
                    // Category_ID: $('#Category_ID').val(),
                    Flammable: $('#Flammable').val(),
                    DSAFee: $('#DSAFee').val(),
                    Payment_mode_ID: $('#Payment_mode_ID').val(),
                    Delivery: $('#Delivery_Permit').val(),
                    Truck: $('#Trucking_Fee').val(),
                    Beach: $('#Beach_fee').val(),
                    Business_line_ID: Business_line_ID,
                    Capitalization: Capitalization,
                    Essential: Essential,
                    NonEssential: NonEssential,
                    Assessment_asset_ID: Assessment_asset_ID,
                    mp_ID: mp_ID, //added 5824 alob
                    Payment_mode: Payment_mode,
                    Exempted: Exempted
                },
            }).done(function(result) {
                // document.location.reload(true);
            });
        }
    });

    // $.each($('.gross-edit'), function() {
    //     console.log($(this).attr('id'));
    // });

    $("#applicant_history").on("click", function() {
        window.location.href = baseUrl + "treasurers/applicant_history/" + ID;
    });

    $('#Edit').on('click', function() {
        $('#Save').removeAttr('disabled');
        $('.cap_text').css("display", "none");
        $('.cap_input').css("display", "block");
        $('.gross_text').css("display", "none");
        $('.gross_input').css("display", "block");
        $('.input-field').removeAttr('disabled');
        $('#Flame').removeAttr('disabled');
        $('#DSA').removeAttr('disabled');
        $('#Delivery').removeAttr('disabled');
        $('#Trucking').removeAttr('disabled');
        $('#Beach').removeAttr('disabled');
        $('.exempt').removeAttr('disabled');
    });

    $('#Authenticate').on('click', function() {
        $.ajax({
            type: "POST",
            url: baseUrl + "treasurers/authenticate",
            data: {
                Username: $('#Username1').val(),
                Password: $('#Password1').val()
            }
        }).always(function(e) {
            if (e != '') {
                var data = JSON.parse(e);
                if (data.has_error == true) {
                    $.each($('.user-input'), function() {
                        $(this).parent('div').addClass('has-error');
                    });
                    $('#Error1').removeAttr('hidden');
                    $('#Error1').html('</br>' + data.error_message);
                } else {
                    $.each($('.user-input'), function() {
                        $(this).parent('div').removeClass('has-error');
                        $(this).parent('div').addClass('has-success');
                    });
                    $('#Error1').removeAttr('hidden');
                    $('#Error1').html('</br>Access granted.');

                    $('#Authenticate').css("display", "none");
                    $('#Close_auth').css("display", "inline");

                    // alert($('#Assessment_ID').val());

                    $.ajax({
                        type: "POST",
                        url: baseUrl + "treasurers/delete",
                        data: {
                            ID: Assessment_ID, // 02-23-2021
                        }
                    }).done(function(result) {
                        $('.gross_text').css("display", "none");
                        $('.gross_input').css("display", "block");
                        $('.gross-edit').removeAttr("disabled");
                        $('#Edit_gross').css("display", "none");
                        $('#Save_gross').css("display", "inline");
                        $('#Edit').removeAttr('disabled');
                        // setTimeout(function() { document.location.reload(true); }, 1500);
                    });
                }
            }
        });
    });

    $('#Edit_gross').on('click', function() {
        $('#Username1').val('');
        $('#Password1').val('');
        $.each($('.user-input'), function() {
            $(this).parent('div').removeClass('has-error');
            $(this).parent('div').removeClass('has-success');
        });
        $('#Error1').attr('hidden', true);
    });

    $('#Flame').on('change', function() {
        $('#Flammable').val($(this).is(':checked') ? '1500' : '0');
    });

    $('#DSA').on('change', function() {
        $('#DSAFee').val($(this).is(':checked') ? '80' : '0');
    });
    $('#Beach').on('change', function() {
        $('#Beach_fee').val($(this).is(':checked') ? '1500' : '0');
    });
    $('#Trucking').on('change', function() {
        // $('#Trucking_Fee').removeAttr('disabled');
        if($(this).is(':checked') ){
            $('#Trucking_Fee').removeAttr('disabled');
        } else {
            $('#Trucking_Fee').prop('disabled', true);
        }
    });
    $('#Delivery').on('change', function() {
        if($(this).is(':checked') ){
            $('#Delivery_Permit').removeAttr('disabled');
        } else {
            $('#Delivery_Permit').prop('disabled', true);
        }
    });

    // $('.cap-edit').on('keyup change', function() {
    //     var ID = $(this).data('id');
    //     var val = parseInt($(this).val());
    //     var asset = asset_size.find(ass => (val <= ass.Asset_to && val >= ass.Asset_from));
    //     if (asset != null) {
    //         var char = asset.Characteristics;
    //         var size = asset.Asset_size;
    //         $('#Char' + ID).html(char + "(" + size + ")").css({
    //             'font-weight': 'normal',
    //             'color': 'black'
    //         });
    //     } else {
    //         $('#Char' + ID).html("INVALID VALUE").css({
    //             'font-weight': 'bold',
    //             'color': 'red'
    //         });
    //     }
    // });

    $('#applicant_history').on('click', function() {
        window.location.href = baseUrl + "treasurers/applicant_history/" + ID;
    });
});

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