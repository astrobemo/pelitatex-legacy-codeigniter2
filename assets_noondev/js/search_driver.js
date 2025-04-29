var FormSearch = function () {

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

        function get_data(url,data_st){
            var hasil = "test";
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


        $('#form_search .button-search').click(function () {
            if (form2.valid())
            {
                $('#search_driver').dataTable().fnDestroy();
                var response;
                var data_st = {};
                data_st['first_name'] = $('input[name=first_name]').val();
                data_st['last_name'] = $('input[name=last_name]').val();
                var url = "company/show_borrowed_driver";
                var response = get_data(url,data_st);
                $('#search_driver tbody').html(response);
                TableAdvanced.init();
                $('.result-table').show('fast');
            }
        });


        $('#search_driver').on('click','.button_activate',function(){
            bootbox.confirm("Are you sure add this to active driver ?", function(result) {
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

                    $('#add_driver').attr('action','add_borrowed_driver');
                    $('#add_driver').submit();
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