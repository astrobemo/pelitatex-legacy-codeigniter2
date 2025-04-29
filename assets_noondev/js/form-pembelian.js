var FormNewPembelian = function () {


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
                    supplier_id: {
                        required: true,
                    },
                    gudang_id: {
                        required: true
                    },
                    tanggal: {
                        required: true
                    },
                    no_faktur: {
                        required: true,
                        checkAvail: true
                    },
                    po_pembelian_batch_id:{
                        required: '#ockh:blank'
                    },
                    ockh_info:{
                        required: '#po_list:blank'
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    po_pembelian_batch_id: "PO / OCKH harus di isi salah satu",
                    ockh_info: "PO / OCKH harus di isi salah satu"

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
                "checkAvail",  
                function(value, element) {
                    var data_st = {};
                    data_st['no_faktur'] = value;
                    $.ajax({
                        type: "POST",
                        url: baseurl+"transaction/check_new_faktur_pembelian",
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
                "No faktur telah terdaftar"
            );


            $('.btn-save').click(function () {
                var ini = $(this);
                if (form.valid())
                {
                    btn_disabled_load(ini);
                    // alert('OK')
                    form.submit();
                }
            });

            $('#form_add_data input').keypress(function (e) {
                if (e.which == 13) {
                    if (form.valid())
                    {
                        // alert('OK')
                        $('.btn-save').prop('disabled',true);
                        form.submit();
                    }
                }
            });
        }

    };

}();

var FormEditPembelian = function () {

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
                    supplier_id: {
                        required: true,
                    },
                    gudang_id: {
                        required: true
                    },
                    tanggal: {
                        required: true
                    },
                    no_faktur: {
                        required: true,
                        checkAvailEdit: true
                    },
                    po_pembelian_batch_id:{
                        required: '#ockh_edit:blank'
                    },
                    ockh_info:{
                        required: '#po_list_edit:blank'
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    po_pembelian_batch_id: "PO / OCKH harus di isi salah satu",
                    ockh_info: "PO / OCKH harus di isi salah satu"
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
                "checkAvailEdit",  
                function(value, element) {
                    var data_st = {};
                    data_st['no_faktur'] = value;
                    data_st['pembelian_id'] = $('#form_edit_data [name=pembelian_id]').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"transaction/check_edit_faktur_pembelian",
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
                "No faktur telah terdaftar"
            );


            $('.btn-edit-save').click(function () {
                if (form.valid())
                {
                    // alert('OK')
                    $('.btn-edit-save').prop('disabled',true);
                    form.submit();
                }
            });

            $('#form_edit_data input').keypress(function (e) {
                if (e.which == 13) {
                    if (form.valid())
                    {
                        // alert('OK')
                        $('.btn-edit-save').prop('disabled',true);
                        form.submit();
                    }
                }
            });
        }

    };

}();

var FormNewPembelianDetail = function () {


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
                    harga_beli: {
                        required: true
                    },
                    qty: {
                        required: true,
                    },
                    jumlah_roll:{
                        required: true,  
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
                "checkAvail",  
                function(value, element) {
                    var data_st = {};
                    data_st['no_faktur'] = value;
                    $.ajax({
                        type: "POST",
                        url: baseurl+"transaction/check_new_faktur_pembelian",
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
                "No faktur telah terdaftar"
            );


            // $('.btn-save-brg').click(function () {
            //     if (form.valid())
            //     {
            //         // alert('OK')
            //         $('.btn-save-brg').prop('disabled',true);
            //         form.submit();
            //     }
            // });

            $('#form_add_barang input').keypress(function (e) {
                if (e.which == 13) {
                    if (form.valid())
                    {
                        // alert('OK')
                        $('.btn-save').prop('disabled',true);
                        form.submit();
                    }
                }
            });
        }

    };

}();