var FormWizard = function () {


    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }

            var form = $('#submit_form');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    vendor_code: {
                        minlength: 3,
                        required: true,
                        checkCompanyCode: true
                    },
                    email: {
                        required: true,
                        email: true,
                        checkUsername: true
                    },
                    password: {
                        minlength: 5,
                        required: true
                    },
                    rpassword: {
                        minlength: 5,
                        required: true,
                        equalTo: "#submit_form_password"
                    },
                    //company
                    company_name: {
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
                    phone: {
                        required: true
                    },
                    type_of_entity: {
                        required: true
                    },
                    business_license_number: {
                        required: false
                    },
                    //contact person
                    first_name: {
                        required: true
                    },
                    middle_initial: {
                        required: false
                    },
                    last_name: {
                        required: true
                    },
                    contact_phone: {
                        required: true
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element); // for other inputs, just perform default behavior
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
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
                    label
                        .addClass('valid') // mark the current input as valid and display OK icon
                    .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                },

                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    form.submit();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                }

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

            var displayConfirm = function() {
                $('#tab5 .form-control-static', form).each(function(){
                    var input = $('[name="'+$(this).attr("data-display")+'"]', form);
                    if (input.is(":radio")) {
                        input = $('[name="'+$(this).attr("data-display")+'"]:checked', form);
                    }
                    if (input.is(":text") || input.is("textarea")) {
                        $(this).html(input.val());
                    } else if (input.is("select")) {
                        $(this).html(input.find('option:selected').text());
                    } else if (input.is(":radio") && input.is(":checked")) {
                        $(this).html(input.attr("data-title"));
                    }
                });
            }

            var handleTitle = function(tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                // set wizard title
                $('.step-title', $('#form_wizard_company')).text('Step ' + (index + 1) + ' of ' + total);
                // set done steps
                jQuery('li', $('#form_wizard_company')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }

                if (current == 1) {
                    $('#form_wizard_company').find('.button-previous').hide();
                } else {
                    $('#form_wizard_company').find('.button-previous').show();
                }

                if (current >= total) {
                    $('#form_wizard_company').find('.button-next').hide();
                    $('#form_wizard_company').find('.button-submit').show();
                    displayConfirm();
                } else {
                    $('#form_wizard_company').find('.button-next').show();
                    $('#form_wizard_company').find('.button-submit').hide();
                }
                Metronic.scrollTo($('.page-title'));
            }

            var response;
            $.validator.addMethod(
                "checkCompanyCode", 
                function(value, element) {
                    $.ajax({
                        type: "POST",
                        url: baseurl+"registration/check_company_code",
                        async: false,
                        data: "checkCode="+value,
                        success: function(msg)
                        {
                            //If username exists, set response to true
                            if(msg == 'true'){
                                response = true;
                            }else{
                                response = false;
                            }
                        }
                     });
                    return response;
                },
                "Vendor Code not found/already registered"
            );

            var response;
            $.validator.addMethod(
                "checkUsername", 
                function(value, element) {
                    $.ajax({
                        type: "POST",
                        url: baseurl+"registration/check_username",
                        async: false,
                        data: "checkName="+value,
                        success: function(msg)
                        {
                            //If username exists, set response to true
                            if(msg == 'true'){
                                response = true;
                            }else{
                                response = false;
                            }
                        }
                     });
                    return response;
                },
                "This email has already used, use another email address"
            );

            // default form wizard
            $('#form_wizard_company').bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                onTabClick: function (tab, navigation, index, clickedIndex) {
                    return false;
                    /*
                    success.hide();
                    error.hide();
                    if (form.valid() == false) {
                        return false;
                    }
                    handleTitle(tab, navigation, clickedIndex);
                    */
                },
                onNext: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    if (form.valid() == false) {
                        return false;
                    }

                    handleTitle(tab, navigation, index);
                },
                onPrevious: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    handleTitle(tab, navigation, index);
                },
                onTabShow: function (tab, navigation, index) {
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    var $percent = (current / total) * 100;
                    $('#form_wizard_company').find('.progress-bar').css({
                        width: $percent + '%'
                    });
                }
            });

            $('#form_wizard_company').find('.button-previous').hide();
            $('#submit_form .button-submit').click(function () {
                bootbox.confirm("Are you sure to submit this form?", function(result){
                    if (result)
                    {
                        $('#submit_form').attr('action','company_sign_up');
                        $('#submit_form').submit();
                    }
                });
            }).hide();
        }

    };

}();