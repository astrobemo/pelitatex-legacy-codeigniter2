var FormOrderBox = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_order');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    delivery_instruction: {
                        required: true,
                        //checkDI: true
                    },date_order: {
                        required: true
                    },car_code: {
                        required: true
                    },vehicle_id: {
                        required: true
                    },driver: {
                        required: true
                    },driver_assistant: {
                        required: true
                    },route_id: {
                        required: true
                    },billing: {
                        required: true
                    },driver_fee: {
                        required: true
                    },solar_expense: {
                        required: true
                    },driver_assistant_fee: {
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

            var response;
            $.validator.addMethod(
                "checkUsername", 
                function(value, element) {
                    var data_st = {};
                    data_st['checkName'] = value;
                    data_st['code'] = $('#vendor_code_hid').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"registration/check_username_code",
                        async: false,
                        data: data_st,
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

            $('select[name=route_id]').change(function(){
                if($(this).val()!=""){
                    $('input[name=billing]').val('623000');
                    $('input[name=driver_fee]').val('65000');
                    $('input[name=solar_expense]').val('125000');
                    $('input[name=driver_assistant_fee]').val('55000');
                }else{
                    $('input[name=billing]').val('');
                    $('input[name=driver_fee]').val('');
                    $('input[name=solar_expense]').val('');
                    $('input[name=driver_assistant_fee]').val('');
                }   
            });

            $('#form_order .button-save').click(function () {
                if (form.valid())
                {
                    bootbox.confirm("Are you sure to submit this form?", function(result)
                    {
                        if (result)
                        {  
                            //$('#form_order').submit();
                            window.location.replace(baseurl + "admin/delivery_order_list");
                        }
                    });
                }
            });

        }

    };

}();

var FormBillingNew = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_bill');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    billing_id: {
                        required: true
                        //checkDI: true
                    },date_start: {
                        required: true
                    },date_end: {
                        required: true,
                        greaterThan: true
                    },customer: {
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

            var response;
            $.validator.addMethod(
                "greaterThan", 
                function(value, element) {
                    var tgl = new Date(Date.parse($('[name=date_end]').val()) - Date.parse($('[name=date_start]').val()));
                    if(tgl > 0){
                        response = true;
                    }else{
                        response = false;
                    }
                    return response;
                },
                "Date end must not before date start"
            );
            

            $('#form_bill .button-save').click(function () {
                if (form.valid())
                {
                    bootbox.confirm("Are you sure to create new bill ?", function(result)
                    {
                        if (result)
                        {  
                            //$('#form_order').submit();
                            window.location.replace(baseurl + "admin/billing_active_details");
                        }
                    });
                }
            });

        }

    };

}();


var FormOrderBox = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_order');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    delivery_instruction: {
                        required: true,
                        //checkDI: true
                    },date_order: {
                        required: true
                    },car_code: {
                        required: true
                    },vehicle_id: {
                        required: true
                    },driver: {
                        required: true
                    },driver_assistant: {
                        required: true
                    },route_id: {
                        required: true
                    },billing: {
                        required: true
                    },driver_fee: {
                        required: true
                    },solar_expense: {
                        required: true
                    },driver_assistant_fee: {
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

            var response;
            $.validator.addMethod(
                "checkUsername", 
                function(value, element) {
                    var data_st = {};
                    data_st['checkName'] = value;
                    data_st['code'] = $('#vendor_code_hid').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"registration/check_username_code",
                        async: false,
                        data: data_st,
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

            $('select[name=route_id]').change(function(){
                if($(this).val()!=""){
                    $('input[name=billing]').val('623000');
                    $('input[name=driver_fee]').val('65000');
                    $('input[name=solar_expense]').val('125000');
                    $('input[name=driver_assistant_fee]').val('55000');
                }else{
                    $('input[name=billing]').val('');
                    $('input[name=driver_fee]').val('');
                    $('input[name=solar_expense]').val('');
                    $('input[name=driver_assistant_fee]').val('');
                }   
            });

            $('#form_order .button-save').click(function () {
                if (form.valid())
                {
                    bootbox.confirm("Are you sure to submit this form?", function(result)
                    {
                        if (result)
                        {  
                            //$('#form_order').submit();
                            window.location.replace(baseurl + "admin/delivery_order_list");
                        }
                    });
                }
            });

        }

    };

}();

var FormPayrollDriverNew = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_payroll');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    date_start: {
                        required: true
                    },date_end: {
                        required: true,
                        greaterThan: true
                    },date_solar_start: {
                        required: true
                    },date_solar_end: {
                        required: true,
                        greaterThanSolar: true
                    },customer: {
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

            var response;
            $.validator.addMethod(
                "greaterThan", 
                function(value, element) {
                    var tgl = new Date(Date.parse($('[name=date_end]').val()) - Date.parse($('[name=date_start]').val()));
                    
                    if(tgl > 0){
                        response = true;
                    }else{
                        response = false;
                    }
                    return response;
                },
                "Date end must not before date start"
            );

            var response;
            $.validator.addMethod(
                "greaterThanSolar", 
                function(value, element) {
                    var tgl = new Date(Date.parse($('[name=date_solar_end]').val()) - Date.parse($('[name=date_solar_start]').val()));
                    
                    if(tgl > 0){
                        response = true;
                    }else{
                        response = false;
                    }
                    return response;
                },
                "Date end must not before date start"
            );
            

            $('#form_payroll .btn-save').click(function () {
                if (form.valid())
                {
                    bootbox.confirm("Are you sure to create new payroll ?", function(result)
                    {
                        if (result)
                        {  
                            //$('#form_order').submit();
                            window.location.replace(baseurl + "admin/payroll_driver_active_detail");
                        }
                    });
                }
            });

        }

    };

}();
