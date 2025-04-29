var FormNewReturDetail = function () {


    return {
        //main function to initiate the module
        init: function () {

            var form = $('#form_add_barang');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: true, // do not focus the last invalid input
                rules: {
                    barang_id: {
                        required: true,
                    },
                    warna_id: {
                        required: true
                    },
                    harga_jual: {
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

            $('.btn-add-qty').click(function () {
                if (form.valid())
                {
                    $('#portlet-config-qty').modal('toggle');
                    setTimeout(function(){
                        $('#qty-table').find('.input1').focus();
                    },700);
                }
            });

            $('#form_add_barang input').keypress(function (e) {
                if (e.which == 13) {
                    if (form.valid())
                    {
                        // $('#portlet-config-detail').modal('toggle');
                        $('#portlet-config-qty').modal('toggle');
                        setTimeout(function(){
                            $('#qty-table').find('.input1').focus();
                        },700);
                    }
                }
            });
        }

    };

}();