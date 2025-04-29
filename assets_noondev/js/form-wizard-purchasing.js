var FormWizardAddPurchasing = function () {


    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }

            var form = $('#form_add_data');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);


            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    date: {
                        required: true
                    },supplier_list_id: {
                        required: true
                    },invoice_number: {
                        required: false,
                        checkInvoice: true
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    
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
                    if (label.attr("for") == "vehicles[]") { // for checkboxes and radio buttons, no need to show OK icon
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
                    form.submit();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                }

            });

            $.extend($.inputmask.defaults, {
                'autounmask': true
            });

            var response;
            $.validator.addMethod(
                "checkInvoice", 
                function(value, element) {
                    data_sent = {}
                    data_sent['invoice_number'] = value;
                    data_sent['supplier_list_id'] = $('#form_add_data [name=supplier_list_id]').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"admin/check_purchasing_invoice",
                        async: false,
                        data: data_sent,
                        success: function(msg)
                        {
                            if(value == ''){
                                response = true;
                            }else{
                                if(msg == 'true'){
                                    response = true;
                                }else{
                                    response = false;
                                }    
                            }
                            
                        }
                     });
                    return response;
                },
                "This invoice number is duplicated, please check"
            );

            // default form wizard
            $('.btn-save').click(function () {
                if(form.valid()){
                    bootbox.confirm("Are you sure to submit this form?", function(result){
                        if (result)
                        {
                            form.submit();
                        }
                    });
                }
                
            });
        }

    };

}();

var FormWizardEditItem = function () {


    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }

            var form = $('#form_edit_data');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);


            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    date: {
                        required: true
                    },supplier_list_id: {
                        required: true
                    },invoice_number: {
                        required: true,
                        checkInvoiceUpdate: true
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    
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
                    if (label.attr("for") == "vehicles[]") { // for checkboxes and radio buttons, no need to show OK icon
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
                    form.submit();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                }

            });

            $.extend($.inputmask.defaults, {
                'autounmask': true
            });

            var response;
            $.validator.addMethod(
                "checkInvoiceUpdate", 
                function(value, element) {
                    data_sent = {}
                    data_sent['purchasing_list_id'] = $('#form_edit_data [name=purchasing_list_id]').val();
                    data_sent['invoice_number'] = value;
                    data_sent['supplier_list_id'] = $('#form_edit_data [name=supplier_list_id]').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"admin/check_purchasing_invoice_update",
                        async: false,
                        data: data_sent,
                        success: function(msg)
                        {
                            //If username exists, set response to true
                            // alert(msg);
                            if(msg == 'true'){
                                response = true;
                            }else{
                                response = false;
                            }
                        }
                     });
                    return response;
                },
                "This invoice number is duplicated, please check"
            );

            // default form wizard
            $('.btn-edit-save').click(function () {
                if(form.valid()){
                    bootbox.confirm("Are you sure to submit this form?", function(result){
                        if (result)
                        {
                            form.submit();
                        }
                    });
                }
                
            });
        }

    };

}();