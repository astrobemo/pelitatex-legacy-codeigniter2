    
    //populate list
    var customers = new Bloodhound({
      datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.name); },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      limit: 10,
      prefetch: {
        url: baseurl+'admin/customers_list',
        filter: function(list) {
          return $.map(list, function(value) { return { name: value.nama, id: value.id }; });
        }
      }
    });

    var barang = new Bloodhound({
      datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.name); },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      limit: 10,
      prefetch: {
        url: baseurl+'admin/barang_list',
        filter: function(list) {
          return $.map(list, function(value) { return { name: value.nama_jual.toUpperCase(), id: value.id }; });
        }
      }
    });

    var warna = new Bloodhound({
      datumTokenizer: function(d) { return Bloodhound.tokenizers.whitespace(d.name); },
      queryTokenizer: Bloodhound.tokenizers.whitespace,
      limit: 10,
      prefetch: {
        url: baseurl+'admin/warna_list',
        filter: function(list) {
          return $.map(list, function(value) { return { name: value.warna_jual.toUpperCase(), id: value.id }; });
        }
      }
    });

    customers.initialize();
    barang.initialize();
    warna.initialize();

    // initiate typeahead
    $('#customers_note').typeahead({
      autoselect: true,
      }, {
      name: 'customers_note',
      displayKey: 'name',
      hint: true,
      source: customers.ttAdapter()
    });

    $('#nama-barang-note, #nama-barang-note-edit').typeahead({
      autoselect: true,
      }, {
      name: 'nama_barang_note',
      displayKey: 'name',
      hint: true,
      source: barang.ttAdapter()
    });

    $('#nama-warna-note, #nama-warna-note-edit').typeahead({
      autoselect: true,
      }, {
      name: 'nama_warna_note',
      displayKey: 'name',
      hint: true,
      source: warna.ttAdapter()
    });


    //enter keyboard behaviour
    var map = {13: false};
    var form_note = $("#form_add_note_order");
    $("#portlet-config-note-order input").keydown(function(e) {
      if (e.keyCode in map) {
        map[e.keyCode] = true;
        if (map[13]) {
          var tabindex = $(this).attr('tabindex');
          tabindex++;
          console.log(tabindex);
          if (tabindex != 4) {
            // alert($(this).attr('id'));
            $('#portlet-config-note-order').find('input[tabindex='+tabindex+']').focus();
          }else if(tabindex == 4){
            setTimeout(function(){
              $("#catatan-note-order").focus();
            },100);
          }
          // document.forms[idx].elements[2].focus();
        }
      }            
    }).keyup(function(e) {
        if (e.keyCode in map) {
            map[e.keyCode] = false;
        }
    });

    $("#form_add_note_order input").keydown(function(e) {
      if (e.keyCode in map) {
        map[e.keyCode] = true;
        if (map[13]) {
          e.preventDefault();
          var tabindex = $(this).attr('tabindex');
          tabindex++;
          console.log(tabindex);
          if (tabindex < 6) {
           $("#form-note-div").find('input[tabindex='+tabindex+']').focus();
          }else if(tabindex == 6){
            setTimeout(function(){
              $("#catatan-note-order").focus();
            },100);
          }else if(tabindex <= 10){
            $("#barang-note-form").find('input[tabindex='+tabindex+']').focus();
          }
          // document.forms[idx].elements[2].focus();
        }
      }            
    }).keyup(function(e) {
        if (e.keyCode in map) {
            map[e.keyCode] = false;
        }
    });

    /*$("#customers_note").focus(function(){
      $("#form_add_note_order .info-alamat").show();
    }).focusout(function(){
      $("#form_add_note_order .info-alamat").hide();
    });*/

    //check if customers is registered or new
    $("#customers_note").change(function(){
      var ini = $(this);
      $("#form_add_note_order .info-alamat").html('');
      var text = $(this).val().toUpperCase();
      var data = $('#customer-note-list [value="'+text+'"]').text().split('??');
      var customer_id = data[0];
      var alamat = data[1];
      var contact = data[2];

      if (customer_id != '') {
        $("#form_add_note_order [name=customer_id]").val(customer_id);
        if (contact != '') {
          $('[name=contact_info]').val(data);
        };
        if (alamat != '') {$("#form_add_note_order .info-alamat").html(text+'<br/>'+alamat)};
        ini.closest('td').find('.terdaftar').show();
      }else{
        $("#form_add_note_order [name=customer_id]").val('');
        $("#form_add_note_order .info-alamat").html("Customer tidak terdaftar");
        ini.closest('td').find('.terdaftar').hide();
      };      
    });

    //klo barang nya ke pilih, get data dari barang tersebut
    $("#nama-barang-note").change(function(){
      var text = $(this).val().toUpperCase();
      // alert(text);
      var id_barang =  $('#nama-barang-note-copy > option:contains("'+text+'")').val();
      var data = $("#nama-barang-note-copy [value='"+id_barang+"']").text();
      var harga_barang = 0;
      var id_barang = 0;
      if (data != '') {
        var brk = data.split('??');
        harga_barang = brk[1];
        id_barang = brk[2];
      };
      $("#nama-warna-note").val('');
      $("#harga-barang-note").val(harga_barang);
      $("#id-barang-note").val(id_barang);
    });

    $("#nama-warna-note").change(function(){
      // alert('test');
      var text = $(this).val().toUpperCase();
      // alert(text);
      var id_warna =  $('#nama-warna-note-copy > option:contains("'+text+'")').val();
      $("#id-warna-note").val(id_warna);
    });

    $("#qty-barang-note").change(function(){
      $("#total-barang-note").val($(this).val() * $("#harga-barang-note").val());
      add_baris_note();
    });

    $("#btn-add-note-item").click(function(){
      if (!add_baris_note()) {
        // alert("Mohon lengkapi data");
      }else{
        $("#note-item-index").val('');
      }
    });

    $("#sales-pin").change(function(){
      if($(this).val() == '1234'){
      };
    });

//==============================================================================

    $('.btn-note-order-save').click(function(){
        var form = "#form_add_note_order";
        var sales_pin = $("#sales-pin").val();
        if (sales_pin == '1234') {
            submit_order_note(form);
        }else{
          alert("PIN salah");
        }
      });

    $("#note-order-list").on("click",'.btn-note-item-remove',function () {
      $(this).closest('tr').remove();
    })

    var new_badge = "<span class='badge badge-roundless badge-danger'>new</span>";


    $("#btn-cancel-note-item").click(function () {
      reset_form_order_item();
      $("#note-order-list tr").css('background','transparent');
      $('#nama-barang-note').focus();
    })

    $('#note-order-list').on('click','.btn-note-item-edit', function(){
      var ini = $(this).closest('tr');
      var id_detail = ini.find(".id_detail").html();
      // alert(id_detail);
      var id = ini.find(".id").html();
      var id_warna = ini.find(".id-warna").html();
      var nama_barang = ini.find(".nama-barang").html();
      var nama_warna = ini.find(".nama-warna").html();
      var qty = ini.find(".qty").html();
      var harga = ini.find(".harga").html();

      // $("#nama-barang-note").attr("value",nama_barang);
      // alert(nama_barang);

      $('#nama-barang-note').typeahead('val',nama_barang);
      $('#nama-warna-note').typeahead('val',nama_warna);
      $("#qty-barang-note").val(qty);
      $("#harga-barang-note").val(harga);
      $("#total-barang-note").val(change_number_format(reset_number_format(harga)*qty) );
      $('#id-detail-note').val(id_detail);
      $('#id-barang-note').val(id);
      $('#id-warna-note').val(id_warna);

      $("#note-item-index").val(ini.index());

      var ind_now = parseInt(ini.index()) + 1
      // $("#note-order-list tr").eq(ind_now).html("<td colspan='6' class='text-center'><i>edit</i></td>");
      // $("#note-order-list tr").css('background','transparent');
      // $("#note-order-list tr").eq(ind_now).css('background','yellow');

      var baris = $("#barang-note-form").detach().css('background','yellow');
      // alert(baris);
      ini.hide();
      $("#note-order-list > tbody > tr").eq(parseInt(ini.index())).after(baris);
      
      // $("#form_add_note_order")
    });

    $('.note-order-list-horizontal').on('click','.btn-edit-note', function(){
      var ini = $(this).closest('td');
      var row = $(this).closest('tr');
      var note_order_id = row.find(".note_order_id").html();
      var id_detail = ini.find(".note_order_detail_id").html();

      var id = row.find(".id-barang").html();
      var id_warna = ini.find(".id-warna").html();
      var nama_barang = row.find(".nama-barang").html();
      var nama_warna = ini.find(".nama-warna").html();
      var qty = ini.find(".qty").html();
      var harga = ini.find(".harga").html();

      $("#note-order-id-edit").val(note_order_id);
      $('#nama-barang-note-edit').typeahead('val',nama_barang);
      $('#nama-warna-note-edit').typeahead('val',nama_warna);
      $("#qty-barang-note-edit").val(qty);
      $("#harga-barang-note-edit").val(harga);
      $('#id-detail-note-edit').val(id_detail);
      $('#id-barang-note-edit').val(id);
      $('#id-warna-note-edit').val(id_warna);

      $("#note-item-index").val(ini.index());
      $("#div-note-detail-action").hide();
    });

    $("#note_order_table").on("click", ".btn-edit-note-remove", function(){
      var ini = $(this).closest('tr');
      var id = ini.find('.id').html();
      bootbox.confirm("Yakin hapus note ini ? ", function(respond){
        if (respond) {
          alert(id);
          var data = {};
          data['id'] = id;
          var url = 'admin/note_order_item_remove';
          ajax_data_sync(url,data).done(function(data_respond ){
            if (data_respond == 'OK') {
              ini.remove();
            }else{
              alert("Error");
            };
          });
        };
      });
    });
    
    $(".btn-note-order-detail-save").click(function(){
      var form = $("#form_add_note_order_detail");
      var nama_barang = form.find('#nama-barang-note-edit').val();
      var nama_warna = form.find('#nama-warna-note-edit').val();
      var qty = form.find('#qty-barang-note-edit').val();
      var harga = form.find('#harga-barang-note-edit').val();
      if (nama_barang != '' && nama_warna != '' && qty != '') {
        $("#form_add_note_order_detail").submit();
      }else{
        alert("Mohon lengkapi barang, nama dan qty");
      }
    });

//======================== note order dashboard================================
    $('#note_order_table').on('dblclick','.check_note_order', function(){
      var id = $(this).closest('tr').find('.note_order_detail_id').html();
      var status = 1;
      var done_by = $(this).closest('tr').find('.status').html();
      if (done_by == '1' || done_by == '-1') {
        status = 0;
      };
      // alert(status);
      window.location.replace(baseurl+"admin/note_order_status_update?id="+id+"&status="+status);
    });

    $('#note_order_table').on('dblclick','.btn-remove', function(){
      var id = $(this).closest('tr').find('.id').html();
      var status = -1;
      // alert(status);
      window.location.replace(baseurl+"admin/note_order_status_update?id="+id+"&status="+status);
    });

    // $('.btn-remove-nota-order').click( function(){
    //  var id = $(this).closest('tr').find('.id').html();
    //  var status = -1;
    //  // alert(status);
    //  window.location.replace(baseurl+"admin/note_order_status_update?id="+id+"&status="+status);
    // });

    // btn-remove-nota-order

    $("#note_order_table").on('click','.btn-edit-note-data', function(){
      // alert("test");
      $("#note-order-list tbody").html('');

      var form = '#form_add_note_order';
      var ini = $(this).closest('tr');
      $(form+" [name=id]").val(ini.find('.id').html());
      $(form+" [name=tanggal]").val(ini.find('.tanggal_note_order').html());
      // $(form+" [name=tanggal_target]").val(ini.find('.tanggal_target').html());
      // alert(ini.find('.nama_customer').html());
      
      $(form+" [name=customer_id]").val(ini.find('.customer_id').html());
      $(form+" [name=note_order_id]").val(ini.find('.note_order_id').html());
      $("#customers_note").val(ini.find('.nama_customer').html());
      $(form+" [name=contact_person]").val(ini.find('.contact_person').html());
      $(form+" [name=contact_info]").val(ini.find('.contact_info').html());
      $("#catatan-note-order").val(ini.find('.catatan').html());
      $("#form_add_note_order .barang-list-note-item").remove();


      var tbl_item = ini.find(".note-order-item-registered");
      var new_badge = "<span class='badge badge-roundless badge-danger'>new</span>";

      tbl_item.find('tr').each(function(){
        var ini = $(this);
        var note_order_id = ini.find(".note_order_id").html();
        var note_order_detail_id = ini.find(".note_order_detail_id").html();
        var id = ini.find(".barang_id").html();
        var nama_barang = ini.find('.nama_barang').html();
        var id_warna = ini.find('.warna_id').html();
        var nama_warna = ini.find('.nama_warna').html();
        var qty = ini.find('.qty').html();
        var harga = ini.find('.harga').html();
        var note_order_detail_id = ini.find('.note_order_detail_id').html();
        var badge_barang = '';
        var badge_warna ='';

        if (id == 0) {badge_barang = new_badge;};
        if (id_warna == 0) {badge_warna = new_badge;};
        var new_baris = `<tr>
            <td><span class='nama-barang'>${nama_barang}</span> ${badge_barang} </td>
            <td><span class='nama-warna'>${nama_warna}</span> ${badge_warna} </td>
            <td><span class='qty'>${qty}</span></td>
            <td><span class='harga'>${change_number_format(harga)}</span></td>
            <td><span class='harga'>${change_number_format(harga*qty)}</span></td>
            <td>
              <span class='id' hidden>${id}</span>
              <span class='id-warna' hidden >${id_warna}</span>
              <span class='id_detail' hidden >${note_order_detail_id}</span>
              <button type='button' class='btn btn-xs green btn-note-item-edit'><i class='fa fa-edit'></i></button> 
              <button type='button' class='btn btn-xs red btn-note-item-remove'><i class='fa fa-times'></i></button> 
            </td>
          </tr>`;

          $("#note-order-list tbody").append(new_baris);
      });
      
      rekap_note_order_item();
      
    });
    
    $("#note_order_table").on('click','.btn-remove-item-note', function(){
      var form = '#form_add_note_order_detail';
      var ini = $(this).closest('tr');
      var note_order_detail_id = ini.find('.note_order_detail_id').html();
      bootbox.confirm("Hapus item?", function(respond){
        if (respond) {
          var data_st = {};
            data_st['note_order_detail_id'] = note_order_detail_id;
            var url = 'admin/note_order_item_remove';
            ajax_data_sync(url,data_st).done(function(data_respond ){
              if (data_respond == 'OK') {
                ini.remove();
              }else{
                alert("Error");
              };
            });
        };
      });



    });

    $("#nama-barang-note-edit").change(function(){
      var text = $(this).val().toUpperCase();
      var id_barang =  $('#nama-barang-note-copy-edit > option:contains("'+text+'")').val();
      // alert(id_barang);
      var data = $("#nama-barang-note-copy-edit [value='"+id_barang+"']").text();
      var harga_barang = 0;
      var id_barang = 0;
      if (data != '') {
        var brk = data.split('??');
        harga_barang = brk[1];
        id_barang = brk[2];
      };
      $("#harga-barang-note-edit").val(harga_barang);
      $("#id-barang-note-edit").val(id_barang);
    });

    $("#nama-warna-note-edit").change(function(){
      // alert('test');
      var text = $(this).val().toUpperCase();
      // alert(text);
      var id_warna =  $('#nama-warna-note-copy-edit > option:contains("'+text+'")').val();
      $("#id-warna-note-edit").val(id_warna);
    });

    $("#note_order_table").on('click','.btn-edit', function(){
      var form = '#form_add_note_order';
      var ini = $(this).closest('tr');
      $(form+" [name=id]").val(ini.find('.id').html());
      $(form+" [name=tanggal_note_order]").val(ini.find('.tanggal_note_order').html());
      $(form+" [name=tanggal_target]").val(ini.find('.tanggal_target').html());
      
      var tipe_customer = ini.find('.tipe_customer').html();
      $(form+" [name=tipe_customer][value="+tipe_customer+"]").prop("checked", true);
      $.uniform.update($(form+" [name=tipe_customer]"));
      if (tipe_customer == 1) {
          $('.note-customer').show();
          $('.note-non-customer').hide();
        }else{
          $('.note-customer').hide();
          $('.note-non-customer').show();
        };

      $(form+" [name=customer_id]").val(ini.find('.customer_id').html());
      $(form+" [name=nama_customer]").val(ini.find('.nama_customer').html());
      $(form+" [name=contact_info]").val(ini.find('.contact_info').html());
      
      var tipe_barang = ini.find('.tipe_barang').html();
      $(form+" [name=tipe_barang][value="+tipe_barang+"]").prop("checked", true);
      $.uniform.update($(form+" [name=tipe_barang]"));
      if (tipe_barang == 1) {
          $('#barang_terdaftar').show();
          $('#barang_tidak_terdaftar').hide();
        }else if (tipe_barang == 2) {
          $('#barang_terdaftar').hide();
          $('#barang_tidak_terdaftar').show();
        };


      $(form+" [name=barang_id]").val(ini.find('.barang_id').html());
      $(form+" [name=warna_id]").val(ini.find('.warna_id').html()).trigger('change.select2');;
      $(form+" [name=nama_barang]").val(ini.find('.nama_barang').html());
      $(form+" [name=qty]").val(ini.find('.qty').html());
      $(form+" [name=harga]").val(ini.find('.harga').html());
      $("#detail-on-order").hide();
      $("#portlet-config-note-order").modal('toggle');
      
    });

    $("#note_order_table").on('click','.btn-add', function(){
      var id = $(this).closest('tr').find('.id').html();
      var form = $('#form_add_note_order_detail');
      form.find('[name=note_order_id]').val(id);
      // alert(form.html());
      $("#portlet-config-note-order-detail").modal('toggle');
    });

    $("#note_order_table").on('click', '.btn-reminder', function(){
      var ini = $(this).closest('tr');
      // $('.form-reminder').hide();
      ini.find('.form-reminder').toggle();
    });

    function add_baris_note(){
      if ($("#harga-barang-note").val() != '' && $("#nama-warna-note").val() != '' && $("#qty-barang-note").val() != '' ) {
        var hidden = '';
        var badge_barang = '';
        var badge_warna = '';
        $("#btn-add-note-item").prop('disabled',true);
        var nama = $("#nama-barang-note").val().toUpperCase();
        var warna = $("#nama-warna-note").val().toUpperCase();
        var qty = $("#qty-barang-note").val();
        var id = $("#id-barang-note").val();
        var id_detail = $("#id-detail-note").val();
        if (id_detail == '') {id_detail=0};

        if (id == '' || id == 0) {id=0; badge_barang=new_badge;};
        var id_warna = $("#id-warna-note").val();
        if (id_warna == '') {id_warna=0; badge_warna=new_badge;};
        var harga = reset_number_format($("#harga-barang-note").val());
        var new_baris = `
            <td><span class='nama-barang'>${nama}</span> ${badge_barang} </td>
            <td><span class='nama-warna'>${warna}</span> ${badge_warna} </td>
            <td><span class='qty'>${qty}</span></td>
            <td><span class='harga'>${change_number_format(harga)}</span></td>
            <td><span class='harga'>${change_number_format(harga * qty)}</span></td>
            <td>
              <span class='id' hidden>${id}</span>
              <span class='id-warna' hidden >${id_warna}</span>
              <span class='id_detail' hidden >${id_detail}</span>
              <button type='button' class='btn btn-xs green btn-note-item-edit'><i class='fa fa-edit'></i></button> 
              <button type='button' class='btn btn-xs red btn-note-item-remove'><i class='fa fa-times'></i></button> 
            </td>`;
        var idx = $("#note-order-list tbody tr").length;
        // var new_input = `<input name='barang_list[${idx}]' value='${id},${nama},${id_warna},${warna},${qty},${harga}' ${hidden} > `;
        // $("#form_add_note_order").append(new_input);
        var index = $('#note-item-index').val();
        if (index != '') {
          // alert(index);
          // console.log(index);
          index = (isNaN(parseInt(index)) ? '' : parseInt(index));
          var css = ((index % 2) == 1 ? '#fff' : '#eee');
          $("#barang-note-form").detach().appendTo("#note-order-list tfoot").css('background','transparent');
          $("#note-order-list tr").eq(index+1).html(new_baris).show();

        }else{
          var index = $("#note-order-list tbody tr").length;
          // alert(index);
          var css = ((index % 2) == 1 ? '#fff' : '#eee');
          $("#note-order-list tbody").append(`<tr style='background:${css}'>${new_baris}</tr>`);
        }

        rekap_note_order_item();

        // $("#form_add_note_order").

        // $("#nama-barang-note").change();
        // $("#nama-warna-note").change();
        
        $("#nama-warna-note").val('');
        $("#id-detail-note").val('');
        $("#id-warna-note").val('');
        $("#total-barang-note").val('');
        // $("#harga-barang-note").val('');
        // $("#nama-warna-note").select2("open");
        
        $("#qty-barang-note").val('');
        $("#btn-add-note-item").prop('disabled',false);

        $("#nama-warna-note").focus();
        
        return true
      }else{
        alert("data tidak lengkap");
        return false;
      }
    }

    function rekap_note_order_item(){
      var total_qty = 0; var total_nilai = 0;
      $("#note-order-list tbody tr").each(function(){
        var ini = $(this);
        var qty = ini.find('.qty').html();
        var harga = ini.find('.harga').html();
        var subtotal = qty * reset_number_format(harga);

        total_qty += parseFloat(qty);
        total_nilai += parseFloat(subtotal);
      });

      $("#note-order-list .total-qty").html(total_qty);
      $("#note-order-list .total-nilai").html(change_number_format(total_nilai) );
    }

    function reset_form_order_item () {
      $("#id-detail-note").val('');
      $('#id-barang-note').val('');
      $('#id-warna-note').val('');
      
      $("#nama-barang-note").val('');
      $("#nama-warna-note").typeahead('val','');
      
      $("#qty-barang-note").val('');
      $("#note-item-index").val('');
      $("#harga-barang-note").val("");
    }

    function submit_order_note(form){
      $("#note-order-list tbody tr").each(function(){
        var ini = $(this);
        var id = ini.find('.id').html();
        var id_detail = ini.find('.id_detail').html();
        var nama = ini.find('.nama-barang').html();
        var id_warna = ini.find('.id-warna').html();
        var warna = ini.find('.nama-warna').html();
        var qty = ini.find('.qty').html();
        var harga = ini.find('.harga').html();
        var new_input = `<input name='barang_list[]' class='barang-list-note-item' value='${id},${nama},${id_warna},${warna},${qty},${harga},${id_detail}' > `;
        $(form).append(new_input);
        $(form).submit();

      });

      // $("#form_add_note_order").submit();
      // $(this).val('submit....');
      // $(this).prop('disabled', true);
    }