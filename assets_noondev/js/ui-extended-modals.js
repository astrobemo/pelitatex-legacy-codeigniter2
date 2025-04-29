var UIExtendedModals = function () {

    
    return {
        //main function to initiate the module
        init: function () {
        
            // general settings
            $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = 
              '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
                '<div class="progress progress-striped active">' +
                  '<div class="progress-bar" style="width: 100%;"></div>' +
                '</div>' +
              '</div>';

            $.fn.modalmanager.defaults.resize = true;

            //ajax demo:
            var $modal = $('#ajax-modal');

            $('#general_table').on('click','.btn-manage', function(){
              // create the backdrop and wait for next modal to be triggered
              var posisi_id = $(this).closest('tr').find('.id').html();
              $('body').modalmanager('loading');

              setTimeout(function(){
                  $modal.load(baseurl+'delegate/menu_posisi_list_manage?posisi_id='+posisi_id, '', function(){
                  $modal.modal();
                });
              }, 1000);
            });

        }

    };

}();

var menuListDetail = function () {

    
    return {
        //main function to initiate the module
        init: function () {
        
            // general settings
            $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = 
              '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
                '<div class="progress progress-striped active">' +
                  '<div class="progress-bar" style="width: 100%;"></div>' +
                '</div>' +
              '</div>';

            $.fn.modalmanager.defaults.resize = true;

            //ajax demo:
            var $modal = $('#ajax-modal');

            $('#general_table').on('click','.btn-manage', function(){
              // create the backdrop and wait for next modal to be triggered
              var menu_id = $(this).closest('tr').find('.id').html();
              var menu_name = $(this).closest('tr').find('.text').html();
              $('body').modalmanager('loading');

              setTimeout(function(){
                $modal.load(baseurl+'delegate/menu_detail_list?menu_id='+menu_id+'&menu_name'+menu_name, '', function(){
                  $modal.modal();
                });
              }, 1000);
            });

        }

    };

}();


var ModalsPembelianEdit = function () {

    
    return {
        //main function to initiate the module
        init: function () {
        
            // general settings
            $.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner = 
              '<div class="loading-spinner" style="width: 200px; margin-left: -100px;">' +
                '<div class="progress progress-striped active">' +
                  '<div class="progress-bar" style="width: 100%;"></div>' +
                '</div>' +
              '</div>';

            $.fn.modalmanager.defaults.resize = true;

            //ajax demo:
            var $modal = $('#pembelian-modal');

            $('#general_table').on('click','.btn-edit', function(){
              // create the backdrop and wait for next modal to be triggered
              var id = $(this).closest('tr').find('.id').html();
              $('body').modalmanager('loading');

              setTimeout(function(){
                  $modal.load('transaction/pembelian_list_edit?id='+id, '', function(){
                  $modal.modal();
                });
              }, 1000);
            });

        }

    };

}();