var FormDriver = function () {

    return {
        //main function to initiate the module
        init: function () {

            var form = $('#form_driver');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                rules: {
                    //account
                    vendor_code: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        minlength: 5,
                        required: false
                    },
                    rpassword: {
                        minlength: 5,
                        required: false,
                        equalTo: "#submit_form_password"
                    },
                    //profile
                    first_name: {
                        required: true
                    },
                    middle_initial: {
                        required: false
                    },
                    last_name: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    state: {
                        required: true
                    },
                    zipcode: {
                        required: true
                    },
                    name_of_business: {
                        required: false
                    },
                    type_of_entity: {
                        required: true
                    },
                    home_business_phone: {
                        required: false
                    },
                    mobile_phone: {
                        required: true
                    },
                    social_security_number: {
                        require_from_group: [1, ".required_number_group"]
                    },
                    rsocial_security_number: {
                        equalTo: "#ssn"
                    },
                    tax_payer_id_number: {
                        require_from_group: [1, ".required_number_group"]
                    },
                    rtax_payer_id_number: {
                        equalTo: "#tin"
                    },
                    business_license_number: {
                        required: false
                    },
                    date_of_birth: {
                        required: true
                    },
                    drivers_license_number: {
                        required: true
                    },
                    drivers_license_state: {
                        required: true
                    },
                    bank_name: {
                        required: false
                    },
                    account_type: {
                        required: false
                    },
                    routing_number: {
                        minlength: 9,
                        maxlength: 9,
                        required: false
                    },
                    confirm_routing_number: {
                        minlength: 9,
                        maxlength: 9,
                        required: false,
                        equalTo: "#routing_number"
                    },
                    account_number: {
                        minlength: 3,
                        maxlength: 17,
                        required: false
                    },
                    confirm_account_number: {
                        minlength: 3,
                        maxlength: 17,
                        required: false,
                        equalTo: "#account_number"
                    },
                    'vehicles': {
                        required: true,
                        minlength: 1
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    'vehicles': {
                        required: "Please select at least one option",
                        minlength: jQuery.validator.format("Please select at least one option")
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.attr("name") == "vehicles") { // for uniform checkboxes, insert the after the given container
                        error.insertAfter("#form_vehicles_error");
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                    console.log(validator.errorList[0].attr("name"));
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    if (label.attr("for") == "vehicles") { // for checkboxes and radio buttons, no need to show OK icon
                        label
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                        label.remove(); // remove error label here
                    } else { // display success icon for other inputs
                        label
                            .addClass('valid') // mark the current input as valid and display OK icon
                        .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    }
                },

                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                },

                ignore:[]

            });

            $.extend($.inputmask.defaults, {
                'autounmask': true
            });

            $(".mask_phone").inputmask("mask", {
                "mask": "(999) 999-9999"
            }); //specifying fn & options
            $(".mask_tin").inputmask({
                "mask": "99-9999999",
                placeholder: "" // remove underscores from the input mask
            }); //specifying options only
            $(".mask_ssn").inputmask("999-99-9999", {
                placeholder: " ",
                clearMaskOnLostFocus: true
            }); //default

            $('#vehicles_1').change(function ()
            {
                if ($("#vehicles_1").prop("checked"))
                {
                    $("#div_vehicles_1").show("fade");
                }
                else
                {
                    $("#div_vehicles_1").hide("fade");
                }
            });

            $('#vehicles_2').change(function ()
            {
                if ($("#vehicles_2").prop("checked"))
                {
                    $("#div_vehicles_2").show("fade");
                }
                else
                {
                    $("#div_vehicles_2").hide("fade");
                }
            });

            $('#vehicles_3').change(function ()
            {
                if ($("#vehicles_3").prop("checked"))
                {
                    $("#div_vehicles_3").show("fade");
                }
                else
                {
                    $("#div_vehicles_3").hide("fade");
                }
            });

            $('#vehicles_4').change(function ()
            {
                if ($("#vehicles_4").prop("checked"))
                {
                    $("#div_vehicles_4").show("fade");
                }
                else
                {
                    $("#div_vehicles_4").hide("fade");
                }
            });

            $('#vehicles_5').change(function ()
            {
                if ($("#vehicles_5").prop("checked"))
                {
                    $("#div_vehicles_5").show("fade");
                }
                else
                {
                    $("#div_vehicles_5").hide("fade");
                }
            });

            $('#vehicles_6').change(function ()
            {
                if ($("#vehicles_6").prop("checked"))
                {
                    $("#div_vehicles_6").show("fade");
                }
                else
                {
                    $("#div_vehicles_6").hide("fade");
                }
            });

            $('#vehicles_7').change(function ()
            {
                if ($("#vehicles_7").prop("checked"))
                {
                    $("#div_vehicles_7").show("fade");
                }
                else
                {
                    $("#div_vehicles_7").hide("fade");
                }
            });

            
        }

    };

}();