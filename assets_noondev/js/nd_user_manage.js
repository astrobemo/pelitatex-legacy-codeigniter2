var FormAddUser = function () {


    return {
        //main function to initiate the module
        init: function () {

            var form = $('#form_add_data');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                rules: {
                    username: {
                        minlength: 3,
                        required: true,
                        checkUser:true
                    },
                    password: {
                        minlength: 6,
                        required: true
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element);
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
                    form.submit()
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                },

                ignore:[]

            });

            var response;
            $.validator.addMethod(
                "checkUser",  
                function(value, element) {
                    var data_st = {};
                    data_st['username'] = value;
                    $.ajax({
                        type: "POST",
                        url: baseurl+"master/check_user",
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
                "Username sudah dipakai"
            );


            $('.btn-save').click(function () {
                if (form.valid())
                {
                    form.submit();
                }
            });
        }

    };

}();


var FormEditUser = function () {


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
                focusInvalid: true, // do not focus the last invalid input
                rules: {
                    username: {
                        minlength: 3,
                        required: true,
                        checkUserEdit:true
                    },
                    password: {
                        minlength: 6//,
                        // required: true
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element);
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
                    form.submit()
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                },

                ignore:[]

            });

            var response;
            $.validator.addMethod(
                "checkUserEdit",  
                function(value, element) {
                    var data_st = {};
                    data_st['username'] = value;
                    data_st['user_id'] = $('#form_edit_data [name=user_id]').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"master/check_user_edit",
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
                "Username sudah dipakai"
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