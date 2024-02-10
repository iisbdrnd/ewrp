<?php $panelTitle = "Chain update"; ?>
@include("panelStart")

<style type="text/css">
    .dd{
        padding: 5px;
    }
    .handle-right{
        right: 0;
        color: red;
        left: auto;
        background: red;
        cursor: pointer;
    }
    .handle-right:hover{
        background: red;
    }
</style>

<form type="update" panelTitle="{{$panelTitle}}" action="{{route('provider.admin.updateChainAction')}}" method="POST" class="form-load form-horizontal group-border stripped" id="chainHandle">
    @method('PUT')
    {{csrf_field()}}
    <input type="hidden" value="{{$cat_id}}" name="cat_id">
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Select Type</label>
        <div class="col-lg-6 col-md-6">
            <input type="hidden" value="{{@$ServiceCategoryType->approval_status}}" id="chain_type">
            <input type="radio" name="type" value="1" class="type" @if(@$ServiceCategoryType->approval_status == 1) checked  @endif> Auto
            <input type="radio" name="type" value="0" class="type" @if(@$ServiceCategoryType->approval_status == 0) checked @endif> Manual
        </div>
    </div>

    <div id="employee_div" style="display: none">
        <div class="form-group">
            <label class="col-lg-2 col-md-3 control-label required">Employee Name</label>
            <div class="col-lg-6 col-md-6">
                <select id="employee" data-fv-icon="false" class="select2 form-control ml0">
                    <option value=""></option>
                    @foreach($employees as $employee)
                        <option value="{{$employee->id}}">{{$employee->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-2 col-md-2">
                <button type="Button" class="btn btn-primary ml15" id="chainAdd">Add to Chain</button>
            </div>
        </div>
    </div>

    <div id="serialize_employee" style="display: none">
        <div class="form-group">
            <div class="row">
                <label class="col-lg-3 col-md-3 control-label "></label>
                <div class="col-lg-8 col-md-8">
                    <div class=dd>
                        <ol class=dd-list id="soft-menu-nestable">
                            @if(!empty(@$chainInfo->chain) && @$chainInfo->type==1)
                                <?php $chains = json_decode($chainInfo->chain); ?>
                                    @if(count($chains) > 0)
                                        @foreach ($chains as $key => $id)
                                            <li id="{{$id}}" class="dd-item dd3-item">
                                                <input type="hidden" name="employe_chain[]" value="{{$id}}">
                                                <i class="dd-handle dd3-handle icomoon-icon-sort-2"></i>
                                                <div class="dd3-content">{{Helper::getEmployeeName($id)}}</div>
                                                <a class="removeHandle">
                                                    <i class="handle-right dd-handle dd3-handle icomoon-icon-remove"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                            @endif
                        </ol>
                    </div>
                </div>
            </div>  
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-6">
            <button type="submit" class="btn btn-default ml15" style="float: right">Create Chain</button>
        </div>
            <div class="col-lg-6">
                <a class="btn btn-default btn-sm ajax-link" href="supply-chain" type="button" >Back to List</a>
            </div>
    </div>

    
</form>
@include("panelEnd")
<script type="text/javascript">

$(document).ready(function() {

    var serialize_employee_array = [];
    
    // when update
    var c_type =  $('#chain_type').val();
    if(c_type == 1){
        $('#employee_div').show();
        $('#serialize_employee').show();

        $("#soft-menu-nestable li").each(function(){
            var exits_id = $(this).attr('id');
            serialize_employee_array.push(exits_id);
        });
    }
    // end when update

    $(".select2").select2({
        placeholder: "Select"
    });

    $('.type').on('click', function (e){
        var type = $(this).val();
        if(type == 1){
            $('#employee_div').show();
        }else{
            $('#employee_div').hide();
        }
    });

    $('#chainAdd').on('click', function (e){

        var employee_id = $('#employee').val();
        var employee_name = $("#employee option:selected").text();
        if (employee_id == "") {
            $.gritter.add({
                title: "Sorry !!!",
                text: "Please input employee name",
                time: "",
                close_icon: "entypo-icon-cancel s12",
                icon: "icomoon-icon-close",
                class_name: "error-notice"
            });
        }else{
            if(!serialize_employee_array.includes(employee_id)){
                serialize_employee_array.push(employee_id);
                $('#serialize_employee').show();
                $('#soft-menu-nestable').append('<li id="'+employee_id+'" class="dd-item dd3-item"><input type="hidden" name="employe_chain[]" value="'+employee_id+'"><i class="dd-handle dd3-handle icomoon-icon-sort-2"></i><div class="dd3-content">'+employee_name+'</div><a class="removeHandle"><i class="handle-right dd-handle dd3-handle icomoon-icon-remove"></i></a></li>');
            }else{
                alert("it's already exits");
            }
        }

    });

    $("#soft-menu-nestable").sortable({
    beforeStop: function( event, ui ) {
            var sorted = $( "#soft-menu-nestable" ).sortable( "toArray" );

        }
    });
    $( "#soft-menu-nestable" ).disableSelection();


    $('#chainHandle').on('click', '.removeHandle', function(e) {
        var id =  $(this).closest('li').attr('id');
        var total_length = serialize_employee_array.length;
        console.log(total_length);

        if(total_length > 0){
            var i;
            for (i = 0; i < total_length; i++) {
            
                if(serialize_employee_array[i] == id){
                    serialize_employee_array.splice(i, 1);
                }
            }
            $(this).closest('li').remove();
        }

        console.log(serialize_employee_array);

    });


});
</script>