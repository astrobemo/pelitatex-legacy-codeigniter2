var FormNewData = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_sparepart');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    name: {
                        required: true
                    },
                    unit: {
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
                    data_st['company_code_id'] = $('#company_code_id').val();
                    data_st['company_user_id'] = $('#company_user_id').val();
                    $.ajax({
                        type: "POST",
                        url: baseurl+"registration/check_username_company",
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
                            // alert(msg);
                        }
                     });
                    return response;
                },
                "This email has already used, use another email address"
            );
   

            $('#form_sparepart .button-save').click(function() {
                if (form.valid())
                {
                    bootbox.confirm("Are you sure to submit this form?", function(result)
                    {
                        if (result)
                        {
                            $('#form_sparepart').attr('action','sparepart_insert');
                            $('#form_sparepart').submit();
                        }
                    });
                }
            });


        }

    };

}();

var FormEditData = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_sparepart');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    name: {
                        required: true
                    },
                    unit: {
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
   

            $('#form_sparepart .button-save').click(function() {
                if (form.valid())
                {
                    bootbox.confirm("Are you sure to submit this form?", function(result)
                    {
                        if (result)
                        {
                            $('#form_sparepart').attr('action','sparepart_update');
                            $('#form_sparepart').submit();
                        }
                    });
                }
            });


        }

    };

}();

var FormNewStock = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_sparepart');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    date: {
                        required: true
                    },
                    supplier: {
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

   
            $('#form_sparepart .button-save').click(function() {
                if (form.valid())
                {
                    bootbox.confirm("Are you sure to submit this form?", function(result)
                    {
                        if (result)
                        {
                            window.location.replace(baseurl+'admin/sparepart_add_stock');
                            // $('#form_company').attr('action','company_update');
                            // $('#form_company').submit();
                        }
                    });
                }
            });

            $('#sparepart_list').on('click','.button-add', function(){
                point_to_this = $(this);
                var tr = point_to_this.closest('tr');
                var td = tr.find("td");
                var kolom =[];
                var index =0;   
                
                td.each(function(k,v){
                    kolom[kolom.length] = $(this).text();
                    index=index+1;
                });
                
                var add_data = [];
                for(var i=0;i<=index;i++){
                    if(i == 0){
                        var sparepart_id = kolom[i];
                    }else if(i == 1){
                        add_data[0] = "<input type='text' hidden='hidden' name='sparepart_id' value='"+sparepart_id+"'>"+kolom[i];
                        add_data[1] = "<input type='text' class='form-control input-sm' name='price' style='width:70px' value='0'>";
                    }else if(i == 3){
                        add_data[i] = kolom[i];
                    }else if(i == 2){
                        add_data[i] = "<input type='text' class='form-control input-sm' name='qty' style='width:70px' value='1'>";
                    }else if(i == 4){
                        add_data[i] = "<input readonly type='text' class='form-control input-sm' name='total' style='width:150px' value='750.000'>";
                    }else if(i == 5){
                        add_data[i] = "<a href='sparepart_add_stock_detail' class='btn-xs btn blue'><i class='fa fa-edit'></i> Edit</a> <a href='sparepart_add_stock_detail' class='btn-xs btn red'><i class='fa fa-times'></i> Remove</a>";
                    }
  
                }

                $('#sparepart_add_list').dataTable().fnAddData(add_data);
                $('#sparepart_add_list').dataTable().fnDraw();

            });

            $('#sparepart_add_list').on('focusout','[name=price],[name=qty]', function(){
                $(this).closest('tr').find('[name=price]').val(change_number_format($(this).closest('tr').find('[name=price]').val()));
                var price = reset_number_format($(this).closest('tr').find('[name=price]').val());
                var qty = reset_number_format($(this).closest('tr').find('[name=qty]').val());
                var total_before = reset_number_format($(this).closest('tr').find('[name=total]').val());
                var total = price * qty;
                $(this).closest('tr').find('[name=total]').val(change_number_format(total));
                var total_amount = 0;
                $('input[name=total]').each(function(){
                    var sub_total = reset_number_format($(this).val());
                    total_amount = total_amount + parseInt(sub_total);
                });
                $('[name=amount]').val(change_number_format(total_amount));

            });

        }

    };

}();

var FormMaintenance = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_sparepart');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    date: {
                        required: true
                    },
                    supplier: {
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

               
            $('#form_sparepart .button-save').click(function() {
                if (form.valid())
                {
                    bootbox.confirm("Are you sure to submit this form?", function(result)
                    {
                        if (result)
                        {
                            window.location.replace(baseurl+'admin/sparepart_add_stock');
                            // $('#form_company').attr('action','company_update');
                            // $('#form_company').submit();
                        }
                    });
                }
            });

            $('#sparepart_list').on('click','.button-add', function(){
                
                point_to_this = $(this);
                var tr = point_to_this.closest('tr');
                var td = tr.find("td");
                var kolom =[];
                var index =0;   
                 
                td.each(function(k,v){
                    kolom[kolom.length] = $(this).text();
                    index=index+1;
                });
                
                var add_data = [];
                for(var i=0;i<=index;i++){
                    if(i == 0){
                        var sparepart_id = kolom[i];
                    }else if(i == 1){
                        add_data[0] = "<input type='text' hidden='hidden' name='sparepart_id' value='"+sparepart_id+"'>"+kolom[i];
                        add_data[1] = "<input type='text' class='form-control input-sm' name='qty' style='width:70px' value='1'>";
                    }else if(i == 3){
                        add_data[2] = kolom[i];
                    }else if(i == 2){
                        add_data[3] = "<a href='sparepart_add_stock_detail' class='btn-xs btn blue'><i class='fa fa-search'></i> Detail</a>";
                    }
  
                }

                $('#sparepart_add_list').dataTable().fnAddData(add_data);
                $('#sparepart_add_list').dataTable().fnDraw();

            });

            $('.button-save').click(function(){
                bootbox.confirm('Are you sure to save this form', function(result){
                    window.location.replace(baseurl+'admin/sparepart_used');
                });
            });

        }

    };

}();

var FormPO = function () {

    return {
        //main function to initiate the module
        init: function () {
            
            var form = $('#form_sparepart');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            // form.validate({
            //     doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
            //     errorElement: 'span', //default input error message container
            //     errorClass: 'help-block help-block-error', // default input error message class
            //     focusInvalid: false, // do not focus the last invalid input
            //     rules: {
            //         //account
            //         date: {
            //             required: true
            //         },
            //         supplier: {
            //             required: true
            //         }
            //     },

            //     errorPlacement: function (error, element) { // render error placement for each input type
            //         error.insertAfter(element); // for other inputs, just perform default behavior
            //     },

            //     invalidHandler: function (event, validator) { //display error alert on form submit   
            //         success.hide();
            //         error.show();
            //         Metronic.scrollTo(error, -200);
            //     },

            //     highlight: function (element) { // hightlight error inputs
            //         $(element)
            //             .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
            //     },

            //     unhighlight: function (element) { // revert the change done by hightlight
            //         $(element)
            //             .closest('.form-group').removeClass('has-error'); // set error class to the control group
            //     },

            //     success: function (label) {
            //         label
            //             .addClass('valid') // mark the current input as valid and display OK icon
            //         .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
            //     },

            //     submitHandler: function (form) {
            //         success.show();
            //         error.hide();
            //         form.submit();
            //         //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
            //     },

            //     ignore:[]

            // });

            // $.extend($.inputmask.defaults, {
            //     'autounmask': true
            // });

            // $(".mask_phone").inputmask("mask", {
            //     "mask": "(999) 999-9999"
            // }); //specifying fn & options
            // $(".mask_tin").inputmask({
            //     "mask": "99-9999999",
            //     placeholder: "" // remove underscores from the input mask
            // }); //specifying options only
            // $(".mask_ssn").inputmask("999-99-9999", {
            //     placeholder: " ",
            //     clearMaskOnLostFocus: true
            // }); //default

   
            // $('#form_sparepart .button-save').click(function() {
            //     if (form.valid())
            //     {
            //         bootbox.confirm("Are you sure to submit this form?", function(result)
            //         {
            //             if (result)
            //             {
            //                 //window.location.replace(baseurl+'admin/sparepart_add_stock');
            //                 $('#form_sparepart').attr('action','sparepart_po_update');
            //                 $('#form_sparepart').submit();
            //             }
            //         });
            //     }
            // });

            $('#form_sparepart').on('focusout','input[name=supplier]',function(){
                var data_sent = {};
                var url = 'admin/sparepart_po_update';
                data_sent['id'] = $('#form_sparepart input[name=id]').val();
                data_sent['supplier'] = $(this).val();
                var result = sent_data(data_sent, url);
                alert(result);
            });

            $('#sparepart_list').on('click','.button-add', function(){
                point_to_this = $(this);
                var tr = point_to_this.closest('tr');
                var td = tr.find("td");
                var kolom =[];
                var index =0;   
                
                td.each(function(k,v){
                    kolom[kolom.length] = $(this).text();
                    index=index+1;
                });
                
                var add_data = [];
                var data_sent = {};
                var response = '';
                for(var i=0;i<=index;i++){
                    if(i == 0){
                        data_sent['sparepart_id'] = kolom[i].trim();
                        data_sent['sparepart_po_id'] = $('input[name=id]').val();
                        // alert(data_sent['sparepart_id']);
                        var url = 'admin/sparepart_po_item_insert';
                        response = sent_data(data_sent,url);
                        response = parseInt(response);
                        // var driver_user_id = point_to_this.closest('tr').find('input[name=driver_user_id]').val();
                        // add_data[i] = '<input hidden="hidden" name="driver_user_id" value='+driver_user_id+'>'+kolom[i];
                    }else if(i == 1){
                        add_data[0] = kolom[i]+"<input hidden='hidden' name='sparepart_po_list_item_id' value='"+response+"''>";
                    }else if(i == 2){
                        add_data[1]  = '<input name="qty_change" class="qty_number" value="1" style="width:50px;border:1px solid #e5e5e5; padding-left:5px">';
                    }else if(i == 3){
                        add_data[2]  = kolom[i];//
                    }else if(i == 4){
                        add_data[3] = '<button class="btn-xs btn red button_remove" tabindex="-1"><i class="fa fa-times"></i> remove</button>';
                    }
                }
                
                
                //alert($.isNumeric(response));
                if($.isNumeric(response) == true){
                    $('#sparepart_add_list').dataTable().fnAddData(add_data);
                    $('#sparepart_add_list').dataTable().fnDraw();
                }else{
                    bootbox.alert('Error occured.');
                }                    

            });

            $('#sparepart_add_list').on('click','.button_remove',function(){
                var id = $(this).closest('tr').find('input[name=sparepart_po_list_item_id]').val();
                var data_sent = {};
                data_sent['id'] = id;
                var url = 'admin/sparepart_po_item_remove';
                var response = sent_data(data_sent,url);
                if(response == 'OK'){
                    var row = $(this).closest('tr').get(0);
                    $('#sparepart_add_list').dataTable().fnDeleteRow(row);
                }else{
                    bootbox.alert('Error occured');
                }
            });

            $('#sparepart_add_list').on('focusout','.qty_number',function(){
                var id = $(this).closest('tr').find('input[name=sparepart_po_list_item_id]').val();
                var data_sent = {};
                data_sent['id'] = id;
                data_sent['qty'] = $(this).val();
                var url = 'admin/sparepart_po_list_item_qty_change';
                var response = sent_data(data_sent,url);
                if(response != 'OK'){
                    bootbox.alert('Error occured');
                }
            });

            function sent_data(data_sent, url){
                var response = 'fail';
                $.ajax({
                    type: "POST",
                    url: baseurl+url,
                    async: false,
                    data: data_sent,
                    success: function(msg)
                    {
                        //If username exists, set response to true
                        response = msg;
                        //alert(msg);
                    }
                });
                return response;
            }

        }

    };

}();
