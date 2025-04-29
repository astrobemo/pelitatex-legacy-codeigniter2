@extends('layouts/app')

@section('content')
<div class="container table-responsive">@include('_partial.flash_message')
    <div class="row" style="padding-top: 15px;">
    <h5 class="float-left col-lg-6">Invoices List</h5>
    <div class="col-lg-6 float-right tomb">
        <a href="invoices/create" class="btn btn-sm btn-primary float-right">NEW</a>
    </div>
    </div><br>
    @if (!empty($invoices_list))
        <table class="table table-striped table-bordered table-hover table-condensed table-sm">
            <thead><tr>
                <th>Date</th><th>Invoices No</th><th>To</th><th>Nominal</th><th style="width: 115px;">Action</th>
            </tr></thead>
            <tbody>
            <?php $total = 0 ?>
            <?php foreach ($invoices_list as $invoices) : ?>
            <tr>
                <td>{{ $invoices->dot->format('d-m-Y') }}</td>
                <td>{{ $invoices->invoices_no }}</td>
                <td>{{ $invoices->pay_to }}</td>
                <td style="text-align: right">{{ number_format($invoices->nominal,0,",",".") }}</td>
                <td>
                    @if ($invoices->status == 's')
                    <div class="box-button">{{link_to('invoices/'.$invoices->id.'/edit','',['class'=>'btn btn-warning btn-sm fa fa-pencil-square-o'])}}</div>
                    <div class="box-button">{!! Form::open(['method'=>'DELETE', 'action'=>['InvoicesController@destroy',$invoices->id]]) !!}
                        {!! Form::button('', ['type' => 'submit', 'class' => 'btn btn-danger btn-sm fa fa-trash',
                        'onclick'=> 'return confirm("Are you sure you want to delete it??")']) !!}{!! Form::close() !!}</div>
                    <button type='button' class="printdm btn btn-info btn-sm fa fa-print" data-id="{{$invoices->id}}"></button>
                    @else
                    <button type='button' class="printdm btn btn-info btn-sm fa fa-print" data-id="{{$invoices->id}}"> Reprint</button>
                    @endif
                    <!--<div class="box-button">{{ link_to('invoices/'.$invoices->id.'/print','',['class'=>'btn btn-primary btn-sm fa fa-print'])}}</div>-->
                    
                </td>
            </tr>
            <?php endforeach ?>
            <tr><td colspan="7">
                    {!! $invoices_list->links('vendor.pagination.bootstrap-4') !!}
                </td></tr>
            </tbody>
        </table>
    @else
        <p>Tidak ada data invoices yang belum di upload</p>
    @endif            
</div>
@stop

@section('scripts')
<script src="{{ asset('js/webprint.js') }}"></script>
<script>
    webprint = new WebPrint(true, {
            relayHost: "127.0.0.1",
            relayPort: "8080",
            readyCallback: function(){
                
            }
        });

    /*$(function(){
        $.ajax({
            url: "http://kaskecil.app/",
            async: false,
            cache: false,
            dataType: "json",
            success: function (data, textStatus, jq) {
                buffer = "";
                buffer = data.string;
            }
        });
    });*/

    /*$(document).on('click', '.edit-modal', function() {
            $('.modal-title').text('Edit Description');
            $('#description_edit').val($(this).data('title'));
            $('#nominal_edit').val($(this).data('content'));
            id = $(this).data('id');
            nom_edit = $(this).data('content');
            $('#editModal').modal('show');
        });*/
    $(document).on('click', '.printdm', function() {
        $.ajax({
            type: 'GET',
            url: 'http://kaskecil.app/invoices/' + $(this).data('id') + '/print',
            async: false,
            cache: false,
            dataType: "text",
            success: function(data, textStatus, jq) {
                webprint.printRaw(data, 'lx300');
                toastr.success('Successfully printed!', 'Success Alert', {timeOut: 2500});
                location.reload();
            }
        });
    });
</script>
@stop