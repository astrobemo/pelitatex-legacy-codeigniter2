var LoginCheck = function () {

    var login_to = function () {
        $('.login_button').click(function(){
            var username_info = $('input[name=username]').val().toLowerCase();
            if(username_info == 'driver'){
                $('.login-form').attr('action','driver');
                $('.login-form').submit();
            }else if(username_info == 'company'){
                $('.login-form').attr('action','company');
                $('.login-form').submit();
            }else{
                $('.login-form').attr('action','admin');
                $('.login-form').submit();
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