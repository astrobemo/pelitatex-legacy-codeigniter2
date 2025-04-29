var TableAddRemove = function () {

    var handleTable = function () {

        var tableActive = $('#table_active_drivers');
        var tablePrimary = $('#table_primary_drivers');

        function get_data(url,data_st){
            var hasil = "fail";
            $.ajax({
                type:"POST",
                url:baseurl+url,
                async:false,
                data:data_st,
                success: function(data)
                {
                    hasil = data;
                }
            });
            return hasil;
        }


        tablePrimary.on('click', '.button_activate', function (e) {
            var point_to_this = $(this);
            bootbox.confirm("Are you sure to activate driver ?", function(result) {
                if (result)
                {

                    tr = point_to_this.closest('tr');
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
                            var driver_user_id = point_to_this.closest('tr').find('input[name=driver_user_id]').val();
                            add_data[i] = '<input hidden="hidden" name="driver_user_id" value='+driver_user_id+'>'+kolom[i];
                        }else if(i != 5 && i != 6){
                            add_data[i] = kolom[i];
                        }else if(i == 5){
                            add_data[i]  = 'Active';
                        }else if(i == 6){
                            add_data[i]  = '<a href="'+baseurl+'company/driver_view" class="btn-xs btn blue-madison"><i class="fa fa-search"></i> Details</a> <a href="#" class="btn-xs btn red button_deactivate"><i class="fa fa-times"></i> Deactivate</a>';
                        }
                    }

                   
                    var data_st = {};
                    data_st['driver_user_id'] = point_to_this.closest('tr').find('input[name=driver_user_id]').val();
                    data_st['status'] = 0;
                    url = 'company/driver_change_status';
                    var result = get_data(url,data_st);


                    if(result == "OK"){
                        var row = point_to_this.closest('tr').get(0);
                        $('#table_active_drivers').dataTable().fnAddData(add_data);
                        $('#table_active_drivers').dataTable().fnDraw();    
                        $('#table_primary_drivers').dataTable().fnDeleteRow(row);

                        var settings = {
                            theme: "lime",
                            sticky: false,
                            horizontalEdge: "bottom",
                            verticalEdge: "right",
                            heading: "Success",
                            life: 5000
                        };

                        $.notific8('zindex', 11500);
                        $.notific8("Driver has been activated", settings);
                    }
                    
                }
            }); 
        });

        tableActive.on('click', '.button_deactivate', function (e) {
            var point_to_this = $(this);
            bootbox.confirm("Are you sure you want to deactivate driver  ?", function(result) {
                if (result)
                {

                    var response;
                    tr = point_to_this.closest('tr');
                    var td = tr.find("td");
                    var kolom =[];
                    var index =0;   
                    
                    td.each(function(k,v){
                        kolom[kolom.length] = $(this).text();
                        index=index+1;
                    });
                    
                    index = index -1;
                    var add_data = [];
                    for(var i=0;i<=index;i++){
                        if(i == 0){
                            var driver_user_id = point_to_this.closest('tr').find('input[name=driver_user_id]').val();
                            add_data[i] = '<input hidden="hidden" name="driver_user_id" value='+driver_user_id+'>'+kolom[i];
                        }else if(i != 5){
                            add_data[i] = kolom[i];
                        }else if(i == 5){
                            var status = kolom[i].trim();
                            add_data[i]  = '<a href="'+baseurl+'company/driver_view" class="btn-xs btn blue-madison"><i class="fa fa-search"></i> Details</a> <a href="#" class="btn-xs btn green button_activate"><i class="fa fa-times"></i> Activate</a>';
                        }
                    }
                    var data_st = {};
                    data_st['driver_user_id'] = point_to_this.closest('tr').find('input[name=driver_user_id]').val();
                    if(status != 'Borrowed'){
                        data_st['status'] = 1;   
                    }else{
                        data_st['status'] = status;
                    }

                    url = 'company/driver_change_status';
                    var result = get_data(url,data_st);
                    if(result == 'OK'){
                        var row = point_to_this.closest('tr').get(0);
                        if(status != 'Borrowed'){
                            $('#table_active_drivers').dataTable().fnAddData(add_data);
                            $('#table_active_drivers').dataTable().fnDraw();    
                        }   
                        $('#table_active_drivers').dataTable().fnDeleteRow(row);   
                        
                        var settings = {
                            theme: "lime",
                            sticky: false,
                            horizontalEdge: "bottom",
                            verticalEdge: "right",
                            heading: "Success",
                            life: 5000
                        };

                        $.notific8('zindex', 11500);
                        $.notific8("Driver has been deactivated", settings);
                    }
                }
            }); 
        });
    }

    return {

        //main function to initiate the module
        init: function () {
            handleTable();
        }

    };

}();

var TableAddRemove2 = function () {

    var handleTable = function () {

        var tableActive = $('#table_active_drivers');

        $('.start_payroll').click(function(){
            
        });

        tableActive.on('click', '.button_remove', function (e) {
            var point_to_this = $(this);
            bootbox.confirm("Are you sure you want to remove this driver from current payroll  ?", function(result) {
                if (result)
                {
                    var settings = {
                        theme: "lime",
                        sticky: false,
                        horizontalEdge: "bottom",
                        verticalEdge: "right",
                        heading: "Success",
                        life: 5000
                    };

                    $.notific8('zindex', 11500);
                    $.notific8("Driver has been remove from current payroll", settings);

                    point_to_this.closest('tr').remove();
                }
            }); 
        });

        tableActive.on('click', '.button_deactivate', function (e) {
            var point_to_this = $(this);
            bootbox.confirm("Are you sure you to remove this driver from current payroll and mark as inactive driver  ?", function(result) {
                if (result)
                {
                    var settings = {
                        theme: "lime",
                        sticky: false,
                        horizontalEdge: "bottom",
                        verticalEdge: "right",
                        heading: "Success",
                        life: 5000
                    };

                    $.notific8('zindex', 11500);
                    $.notific8("Driver has been remove from current payroll and mark as inactive", settings);

                    point_to_this.closest('tr').remove();
                }
            }); 
        });

        $('.amount_txt').keypress(function (e) {
            if (e.which == 13) {
                var tr = $(this).closest('tr');
                var tr_next = tr.next('tr');
                var td = tr_next.find('td');
                td.find('.amount_txt').focus();
            }
        });

        
    }

    return {

        //main function to initiate the module
        init: function () {
            handleTable();
        }

    };

}();

var TableAddRemoveCurrent = function () {

    var handleTable = function () {

        var tableActive = $('#table_drivers');


        tableActive.on('click', '.button_add', function (e) {
            var point_to_this = $(this);
            bootbox.confirm("Are you sure you want to add this driver to current payroll  ?", function(result) {
                if (result)
                {
                    var settings = {
                        theme: "lime",
                        sticky: false,
                        horizontalEdge: "bottom",
                        verticalEdge: "right",
                        heading: "Success",
                        life: 5000
                    };

                    window.location.replace("company_current_payroll_success.php");
                }
            }); 
        });
    }

    return {

        //main function to initiate the module
        init: function () {
            handleTable();
        }

    };

}();

var FormSearchDriver = function () {

    var handleValidationSearch = function(){
        var form2 = $('#form_search');
        var error2 = $('.alert-danger', form2);
        var success2 = $('.alert-success', form2);

        form2.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                var icon = $(element).parent('.input-icon').children('i');
                icon.removeClass('fa-check').addClass("fa-warning");  
                icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
            },


            invalidHandler: function (event, validator) { //display error alert on form submit              
                success2.hide();
                error2.show();
                Metronic.scrollTo(error2, -200);
            },

            
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
            },

            unhighlight: function (element) { // revert the change done by hightlight
                
            },

            success: function (label, element) {
                var icon = $(element).parent('.input-icon').children('i');
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                icon.removeClass("fa-warning").addClass("fa-check");
            },

            submitHandler: function (form) {
                success2.show();
                error2.hide();
            }
        });


        $('#form_search .button-search').click(function () {
            if (form2.valid())
            {
                $('.result-table').show('fast');
            }
        });

        var tableActive = $('#table_drivers');

        tableActive.on('click', '.button_add', function (e) {
            var point_to_this = $(this);
            bootbox.confirm("Are you sure you want to add this driver to current payroll  ?", function(result) {
                if (result)
                {
                    var settings = {
                        theme: "lime",
                        sticky: false,
                        horizontalEdge: "bottom",
                        verticalEdge: "right",
                        heading: "Success",
                        life: 5000
                    };

                    window.location.replace("company_current_payroll_success.php");
                }
            }); 
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleValidationSearch();            
        }

    };

}();

var TableAddRemoveCurrentAdmin = function () {

    var handleTable = function () {

        var tableActive = $('#table_drivers');

        tableActive.on('click', '.button_add', function (e) {
            var point_to_this = $(this);
            bootbox.confirm("Are you sure you want to add this driver to current payroll  ?", function(result) {
                if (result)
                {
                    var settings = {
                        theme: "lime",
                        sticky: false,
                        horizontalEdge: "bottom",
                        verticalEdge: "right",
                        heading: "Success",
                        life: 5000
                    };

                    window.location.replace("payroll_current_success.php");
                }
            }); 
        });
    }

    return {

        //main function to initiate the module
        init: function () {
            handleTable();
        }

    };

}();
var FormSearchDriverAdmin = function () {

    var handleValidationSearch = function(){
        var form2 = $('#form_search');
        var error2 = $('.alert-danger', form2);
        var success2 = $('.alert-success', form2);

        form2.validate({
            errorElement: 'span', //default input error message container
            errorClass: 'help-block help-block-error', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            ignore: "",  // validate all fields including form hidden input
            rules: {
                first_name: {
                    required: true
                },
                last_name: {
                    required: true
                }
            },

            messages: { // custom messages for radio buttons and checkboxes
                
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                var icon = $(element).parent('.input-icon').children('i');
                icon.removeClass('fa-check').addClass("fa-warning");  
                icon.attr("data-original-title", error.text()).tooltip({'container': 'body'});
            },


            invalidHandler: function (event, validator) { //display error alert on form submit              
                success2.hide();
                error2.show();
                Metronic.scrollTo(error2, -200);
            },

            
            highlight: function (element) { // hightlight error inputs
                $(element)
                    .closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
            },

            unhighlight: function (element) { // revert the change done by hightlight
                
            },

            success: function (label, element) {
                var icon = $(element).parent('.input-icon').children('i');
                $(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                icon.removeClass("fa-warning").addClass("fa-check");
            },

            submitHandler: function (form) {
                success2.show();
                error2.hide();
            }
        });


        $('#form_search .button-search').click(function () {
            if (form2.valid())
            {
                $('.result-table').show('fast');
            }
        });

        var tableActive = $('#table_drivers');

        tableActive.on('click', '.button_add', function (e) {
            var point_to_this = $(this);
            bootbox.confirm("Are you sure you want to add this driver to current payroll  ?", function(result) {
                if (result)
                {
                    var settings = {
                        theme: "lime",
                        sticky: false,
                        horizontalEdge: "bottom",
                        verticalEdge: "right",
                        heading: "Success",
                        life: 5000
                    };

                    window.location.replace("payroll_current_success.php");
                }
            }); 
        });
    }

    return {
        //main function to initiate the module
        init: function () {
            handleValidationSearch();            
        }

    };

}();