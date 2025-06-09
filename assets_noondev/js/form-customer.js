var FormAddCustomer = function () {
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
                    nama:{
                        required: true
                    },alamat:{
                        required: true
                    },nik: {
                        minlength: 19,
                        required: '#npwp-add:blank'
                    },npwp: {
                        minlength: 19,
                        required: '#nik-add:blank'
                    },blok:{
                        required: true
                    },no:{
                        required: true
                    },nik: {   
                        minlength: 19
                    },rt:{
                        required: true,
                        maxlength: 3,
                        minlength: 3
                    },rw:{
                        required: true,
                        maxlength: 3,
                        minlength: 3
                    },kelurahan:{
                        required: true
                    },kecamatan:{
                        required: true
                    },kota:{
                        required: true
                    },kode_pos:{
                        required: true,
                        maxlength: 5,
                        minlength: 5
                    },provinsi:{
                        required: true
                    }
                },
                messages: {
                    nik: "Npwp/NIK diisi salah satu, Digit NIK harus 16 angka",
                    npwp: "Npwp/NIK diisi salah satu, Digit NPWP harus 16 angka"
                },
                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element); // for other inputs, just perform default behavior
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
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

            $("#npwp-add").inputmask("9999 9999 9999 9999", {
                placeholder: " ",
                clearMaskOnLostFocus: true
            }); //default

            $("#nik-add").inputmask("9999 9999 9999 9999", {
                placeholder: " ",
                clearMaskOnLostFocus: true
            }); //default

            $('.btn-save').click(function(){
                if(form.valid()){
                    let data_st = {
                        nama:$('#new-nama').val(),
                        alamat:$('#alamat').val(),
                        no:$('#no').val(),
                        blok:$('#blok').val(),
                        rt:$('#rt').val(),
                        rw:$('#rw').val(),
                        kelurahan:$('#kelurahan').val(),
                        kecamatan:$('#kecamatan').val(),
                        kota:$('#kota').val(),
                        provinsi:$('#provinsi').val(),
                        kode_pos:$('#kode_pos').val()
                    };
                    let url = 'master/customer_cek';
                    ajax_data_sync(url,data_st).done(function(data_respond  ,textStatus, jqXHR ){
                        // alert(JSON.parse(data_respond).length);
                       console.log(data_respond  ,textStatus, jqXHR);
                       if(JSON.parse(data_respond).length.length > 0){
                        alert('Customer sudah terdaftar');
                       }else{
                        btn_disabled_load($('.btn-save'));
                        $('#form_add_data').submit();
                       }
                    });
                }
            });            
        }
    };
}();

var FormEditCustomer = function () {
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
                    nama:{
                        required: true
                    },alamat:{
                        required: true
                    },nik: {
                        minlength: 19,
                        required: '#npwp-edit:blank'
                    },npwp: {
                        minlength: 19,
                        required: '#nik-edit:blank'
                    },blok:{
                        required: true
                    },no:{
                        required: true
                    },rt:{
                        required: true,
                        maxlength: 3,
                        minlength: 3
                    },rw:{
                        required: true,
                        maxlength: 3,
                        minlength: 3
                    },kelurahan:{
                        required: true
                    },kecamatan:{
                        required: true
                    },kota:{
                        required: true
                    },kode_pos:{
                        required: true,
                        maxlength: 5,
                        minlength: 5
                    },provinsi:{
                        required: true
                    }
                },
                messages: {
                    nik: "Npwp/NIK diisi salah satu, Digit NIK harus 16 angka",
                    npwp: "Npwp/NIK diisi salah satu, Digit NPWP harus 16 angka"
                },
                errorPlacement: function (error, element) { // render error placement for each input type
                    error.insertAfter(element); // for other inputs, just perform default behavior
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
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

            $("#npwp-edit").inputmask("9999 9999 9999 9999", {
                placeholder: " ",
                clearMaskOnLostFocus: true
            }); //default

            $("#nik-edit").inputmask("9999 9999 9999 9999", {
                placeholder: " ",
                clearMaskOnLostFocus: true
            }); //default
            
            $('.btn-edit-save').click(function(){
                if($("#form_edit_data").valid()){
                    // alert('submit');
                    btn_disabled_load($('.btn-edit-save'));
                    $('#form_edit_data').submit();
                }
            });
        }
    };
}();

