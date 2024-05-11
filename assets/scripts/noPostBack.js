/*
 *  
 * 
 *  Author      :   Immanuel Dennis H. Lopez
 *	Email 		:	sunny.lopez@gmail.com
 *  Filename    :   noPostBack-1.20180316.js
 *  Version     :   1.20180316
 *  Description :   Eliminates post back action of page by passing it as JSON format in  
 *                  ajax.
 ***/

(function($) {

    $.fn.gmLoadPage = function(options) {
        var defaults = {
            url: null,
            load_on: null
        }
        $(document).ready(function() {
            $(options.load_on).load(options.url);
        });
    };

    $.fn.gmFilter = (function(options) {
        var defaults = {
            url: null,
            brgy: null,
            class: null,
            from: null,
            to: null,
            load_on: null,
        }

        this.click(function() {
            $.ajax({
                url: options.url,
                type: "POST",
                data: {
                    brgy: $(options.brgy).val(),
                    class: $(options.class).val(),
                    from: $(options.from).val(),
                    to: $(options.to).val()
                }
            }).always(function(e) {
                $(options.load_on).html(e);
            });
        });
    });

    $.fn.gmSavePost = function(options) {
        var defaults = {
            url: null,
            field: null,
            selector: null,
            load_after: null,
            load_on: null,
            redirect: null,
            return_id: false,
            clear: false,
            reload: false
        };

        var data = [];

        this.click(function() {
            $.each($(options.selector), function() {
                data.push('"' + $(this).data(options.field) + '":"' + $(this).val() + '"');
            });
            $.ajax({
                url: options.url,
                type: "POST",
                dataType: "JSON",
                data: $.parseJSON("{" + data + "}")
            }).always(function(e) {
                if (e.has_error == true) {
                    $("#alert").fadeIn(800);
                    $("#alert").html("<img id='alert-image' src='css/exclamation-icon.png' /><div id='alert-message'>" + e.error_message + "</div>");
                    return false;
                }
                if (options.load_on != null || options.load_after != null) {
                    $(options.load_on).load(options.load_after);
                }
                if (options.clear == true) {
                    $(options.selector).val('');
                }
                if (options.reload == true) {
                    window.location.reload();
                }
                if (options.redirect != null) {
                    if (options.return_id == true) {
                        window.location = options.redirect + e.ID;
                    } else {
                        window.location = options.redirect;
                    }
                }
            });
        });
    };

    $.fn.gmDelete = function(options) {
        var defaults = {
            url: null,
            id: null,
            confirm: null,
            load_after: null,
            load_on: null,
        };

        this.click(function() {
            if (options.confirm != null) {
                if (!confirm(options.confirm)) {
                    return false;
                }
            }

            $.ajax({
                url: options.url,
                type: "POST",
                data: {
                    ID: $(options.id).data('id')
                }
            }).always(function(e) {
                if (e.has_error == true) {
                    $("#alert").fadeIn(800);
                    $("#alert").html("<img id='alert-image' src='css/exclamation-icon.png' /><div id='alert-message'>" + e.error_message + "</div>");
                    return false;
                }
                if (options.load_on != null || options.load_after != null) {
                    $(options.load_on).load(options.load_after);
                }
            });
        });
    };

    $.fn.gmGetSingle = (function(options) {
        var defaults = {
            url: null,
            id: null,
            confirm: null,
            load_on: null
        };

        this.click(function() {
            if (options.confirm != null) {
                if (!confirm(options.confirm)) {
                    return false;
                }
            }

            $.ajax({
                url: options.url,
                type: "POST",
                data: {
                    ID: $(options.id).data('id')
                }
            }).always(function(e) {
                /*
                 * Loads the JSON data to 
                 * your html form.
                 */
                var value = $.parseJSON(e);
                $.each($(options.load_on), function() {
                    var field_name = $(this).data('field');
                    $("#" + field_name).val(value[field_name]);
                });
            });
        });
    });

    $.fn.gmSearch = (function(options) {
        var defaults = {
            url: null,
            search: null,
            load_on: null,
        }

        this.click(function() {
            $.ajax({
                url: options.url,
                type: "POST",
                data: {
                    search: $(options.search).val()
                }
            }).always(function(e) {
                $(options.load_on).html(e);
            });
        });
    });


    // -updated by mel
    $.fn.gmPostHandler = function(options) {

        var data = [];
        var defaults = {
            url: null,
            field: null,
            selector: null,
            data: null,
            function_call: false,
            function: null,
            parameter: null,
            message: null,
            load_after: null,
            load_on: null,
            add_functions: [{
                parameter: null,
                function: null
            }],
            alert_on_error: false,
            function_call_on_error: false,
            error_function: [{
                parameter: null,
                function: null
            }]
        };

        var gm_type = $(this).prop('tagName') //Example
        if (gm_type === 'BUTTON') {
            this.click(function() {

                $.each($(options.selector), function() {
                    data.push('"' + $(this).data(options.field) + '":"' + $(this).val() + '"');
                });

                $.each(options.data, function(key, value) {
                    data.push('"' + key + '":"' + value + '"');
                });

                $.ajax({
                    url: baseUrl + options.url,
                    type: "POST",
                    dataType: "JSON",
                    data: $.parseJSON("{" + data + "}"),
                    success: function(e) {
                        if (e != "") {
                            if (e.has_error) {
                                if (options.alert_on_error) {
                                    alert(e.error_message);
                                }

                                if (options.message != defaults.message && e.error_message != null) {
                                    options.message('<p style="color:red">' + e.error_message + '</p>');
                                }

                                if (options.function_call_on_error) {
                                    $.each(options.error_function, function(index, value) {
                                        value.function();
                                    });
                                }
                            } else {
                                // console.log(options);
                                if (options.load_on != null && options.load_after != null) {
                                    $(options.load_on).load(baseUrl + options.load_after);
                                }
                                if (options.message != defaults.message && e.message != null) {
                                    options.message('<p style="color:green">' + e.message + '</p>');
                                }
                                if (options.function_call) {

                                    if (options.parameter) {
                                        options.function(e);
                                    } else {
                                        options.function();
                                    }
                                    $.each(options.add_functions, function(index, value) {
                                        value.function();
                                    });
                                }


                            }

                        }

                    }
                });
            });
        } else {
            $.each($(options.selector), function() {
                data.push('"' + $(this).data(options.field) + '":"' + $(this).val() + '"');
            });

            $.each(options.data, function(key, value) {
                data.push('"' + key + '":"' + value + '"');
            });

            $.ajax({
                url: baseUrl + options.url,
                type: "POST",
                dataType: "JSON",
                data: $.parseJSON("{" + data + "}"),
                success: function(e) {
                    if (e != "") {
                        if (e.has_error) {

                            if (options.alert_on_error) {
                                alert(e.error_message);
                            }
                            if (options.message != defaults.message && e.error_message != null) {
                                options.message('<p style="color:red">' + e.error_message + '</p>');
                            }
                            if (options.function_call_on_error) {
                                $.each(options.error_function, function(index, value) {
                                    value.function();
                                });
                            }
                        } else {

                            if (options.load_on != null && options.load_after != null) {
                                $(options.load_on).load(baseUrl + options.load_after);
                            }
                            if (options.message != defaults.message && e.message != null) {
                                options.message('<p style="color:green">' + e.message + '</p>');
                            }
                            if (options.function_call) {
                                if (options.parameter) {
                                    options.function(e);
                                } else {
                                    options.function();
                                }

                                $.each(options.add_functions, function(index, value) {
                                    value.function();
                                });
                            }


                        }

                    }

                }
            });
        }
    }

})(jQuery);