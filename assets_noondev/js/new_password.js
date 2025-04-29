var FormPassword = function () {


    return {
        //main function to initiate the module
        init: function () {

            var form = $('#form_change_password');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                rules: {
                    password: {
                        minlength: 6,
                        required: true
                    },
                    rpassword: {
                        minlength: 6,
                        required: true,
                        equalTo: "#new_password"
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
            // $.validator.addMethod(
            //     "checkPassword",  
            //     function(value, element) {
            //         var data_st = {};
            //         data_st['password'] = value;
            //         $.ajax({
            //             type: "POST",
            //             url: baseurl+"admin/check_old_password",
            //             async: false,
            //             data: data_st,
            //             success: function(msg)
            //             {   
            //                 // alert(msg);
            //                 //If username exists, set response to true
            //                 if(msg == 'true'){
            //                     response = true;
            //                 }else{
            //                     response = false;
            //                 }
            //             }
            //          });
            //         return response;
            //     },
            //     "Password Incorrect"
            // );


            $('#form_change_password .btn-save').click(function () {
                if (form.valid())
                {
                    form.submit();
                }
            });
        }

    };

}();