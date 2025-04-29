var FormKIR = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#kir_registration_new');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    date_submitted: {
                        required: true,
                        //checkDI: true
                    },date_expired: {
                        required: true
                    },kir_no: {
                        required: true
                    },cost: {
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

            $('.button-save').click(function () {
                if (form.valid())
                {
                    bootbox.confirm("Are you sure to submit this form?", function(result)
                    {
                        if (result)
                        {  
                            var data = [];
                            data[0] = $('#kir_registration_new [name=date_submitted]').val();
                            data[1] = $('#kir_registration_new [name=date_expired]').val();
                            data[2] = $('#kir_registration_new [name=kir_no]').val();
                            data[3] = $('#kir_registration_new [name=pic]').val();
                            data[4] = $('#kir_registration_new [name=cost]').val();
                            data[4] = change_number_format(data[4]);
                            data[5] = "<a href='#kir_edit' data-toggle='modal' class='btn-xs btn green'><i class='fa fa-edit'></i> Edit</a>";
                            $('#vehicle_table').dataTable().fnAddData(data);
                            $('#vehicle_table').dataTable().fnDraw();

                            $('#kir_new').modal('toggle');
                        }
                    });
                }
            });

        }

    };

}();