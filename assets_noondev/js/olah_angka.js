var OlahAngka = function () {

    return {
        //main function to initiate the module
        init: function () {

            $('#sparepart_list, #sparepart_add_list, #vehicle_table, #driver_table, #employee_table, #billing_table, #claim_table, #general_table, #approved_table, #history_table').on('click','.angka',function(){
				var nama = $(this).attr('name');
				if(nama != 'fuel_qty'){
					if($(this).val() == 0){
						$(this).val('');
					}else if(reset_number_format($(this).val()) >= 1000){
						$(this).val(reset_number_format($(this).val()));
					}
				}
				//alert('test');
			});

			$('#sparepart_list, #sparepart_add_list, #vehicle_table, #driver_table, #employee_table, #billing_table, #claim_table, #general_table, #approved_table, #history_table').on('focus','.angka',function(){
				var nama = $(this).attr('name');
				if(nama != 'fuel_qty'){
					if($(this).val() == 0){
						$(this).val('');
					}else if(reset_number_format($(this).val()) >= 1000){
						$(this).val(reset_number_format($(this).val()));
					}
				}
				//alert('test');
			});

			$('#sparepart_list, #sparepart_add_list, #vehicle_table, #driver_table, #employee_table, #billing_table, #claim_table, #general_table, #approved_table, #history_table').on('focusout','.angka',function(){
				var nama = $(this).attr('name');
				if(nama != 'fuel_qty'){
					if($(this).val() == ''){
						$(this).val(0);
					}else if(reset_number_format($(this).val()) >= 1000){
						$(this).val(change_number_format($(this).val()));
					}
				}
					
			});            
        }

    };

}();

