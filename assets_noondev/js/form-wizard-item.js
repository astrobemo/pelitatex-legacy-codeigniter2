var FormWizardAddItem = function () {


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
                    name: {
                        required: true,
                        checkName: true
                    },unit: {
                        required: true
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
                "checkName", 
                function(value, element) {
                    data_sent = {}
                    data_sent['name'] = value;
                    data_sent['unit'] = $('#form_add_data [name=unit]').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"admin/check_item_name",
                        async: false,
                        data: data_sent,
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
                "This item is duplicated, please check"
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
                    name: {
                        required: true,
                        checkUpdateName: true
                    },unit: {
                        required: true
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
                "checkUpdateName", 
                function(value, element) {
                    data_sent = {}
                    data_sent['name'] = value;
                    data_sent['unit'] = $('#form_edit_data [name=unit]').val();
                    data_sent['item_list_id'] = $('#form_edit_data [name=item_list_id]').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"admin/check_item_update_name",
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
                "This item is duplicated, please check"
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