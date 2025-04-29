var FormWizardKodeAkun = function () {


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
                    //account
                    kode: {
                        checkKode: true,
                        required: true,
                        number: true,
                        minlength:3,
                    },
                    name: {
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

            var response;
            $.validator.addMethod(
                "checkKode",
                function(value, element) {
                    var data_st = {};
                    data_st['kode'] = value;
                    $.ajax({
                        type: "POST",
                        url: baseurl+"admin_deposit/check_kodeakun",
                        async: false,
                        data: data_st,
                        success: function(msg)
                        {   
                            if(msg == 'true'){
                                response = true;
                            }else{
                                response = false;
                            }
                        }
                     });
                    return response;
                },
                "This Code is already used"
            );


            $('.btn-save').click(function () {
                if(form.valid()){
                    form.submit();
                }
            });
        }

    };

}();

var FormWizardEditKodeAkun = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_edit_data');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    kode: {
                        checkKode: true,
                        required: true,
                        number: true,
                        minlength:3,
                    },
                    name: {
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

            var response;
            $.validator.addMethod(
                "checkKode", 
                function(value, element) {
                    var data_st = {};
                    data_st['kode'] = value;
                    data_st['kode_akun_id'] = $('#form_edit_data [name=kode_akun_id]').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"admin_deposit/check_kodeakun_update",
                        async: false,
                        data: data_st,
                        success: function(msg)
                        {   
                            // alert(msg);
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
                "This Code is already used"
            );

            $('.btn-edit-save').click(function () {
                if (form.valid())
                {
                    form.submit();
                    
                }
            });

        }

    };

}();