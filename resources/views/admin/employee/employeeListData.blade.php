<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <select name="email_verified" event="change" data-fv-icon="false" class="select2 form-control ml0 data-search">
                <option value=3 @if($email_verified==3){{'selected'}}@endif>All Employee</option>
                <option value=1 @if($email_verified==1){{'selected'}}@endif>Email Verified</option>
                <option value=2 @if($email_verified==2){{'selected'}}@endif>Email Unverified</option>
            </select>
        </div>
        <div class="col-md-6 col-xs-12">
            @if(Helper::userAccess('employee.create', 'admin'))
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
            @include("perPageBox")
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="19%" data="1">Employee Id</th>
                <th width="18%" data="2">Name</th>
                <th width="18%" data="3">Designation</th>
                <th width="15%" data="4">Email</th>
                <th width="8%" data="6">Verified</th>
                <th width="6%" data="7">Status</th>
                <th width="6%" data="8">Reg.Date</th>
                @if(Helper::userAccess('employee.edit', 'admin') || Helper::userAccess('employee.destroy', 'admin')|| Helper::userAccess('employeeAccess', 'admin', 0))
                <th width="6%">Action</th>
                @endif
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Employee Id</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Email</th>
                <th>Verified</th>
                <th>Status</th>
                <th>Reg.Date</th>
                @if(Helper::userAccess('employee.edit', 'admin') || Helper::userAccess('employee.destroy', 'admin')|| Helper::userAccess('employeeAccess', 'admin', 0))
                <th>Action</th>
                @endif
            </tr>
        </tfoot>
        <tbody>
        <?php $paginate = $employeeBasicInfo; ?>
        @if(count($employeeBasicInfo)>0)
        @foreach($employeeBasicInfo as $employeeBasicInfo)
            <tr>
                <td>{{$sn++}}</td>
                <td>{{$employeeBasicInfo->employee_id}}</td>
                <td>{{$employeeBasicInfo->name}}</td>
                <td>{{$employeeBasicInfo->designation}}</td>
                <td>{{$employeeBasicInfo->email}}</td>
                <td style="text-align:center;">@if($employeeBasicInfo->email_verified==1){{'Yes'}}@else{{'No'}} 
                    <button class="btn btn-default btn-xs employeeEmailResend" type="button" value="{{$employeeBasicInfo->user_id}}">Resend Email</button>@endif
                </td>
                <td>{{$employeeBasicInfo->status}}</td>
                <?php
                        $regDate = DateTime::createFromFormat('Y-m-d H:i:s', $employeeBasicInfo->regDate);
                        $reg_date = $regDate->format('d/m/Y g:i A');
                    ?>
                <td>{{$reg_date}}</td>
                @if(Helper::userAccess('employee.edit', 'admin') || Helper::userAccess('employee.destroy', 'admin')|| Helper::userAccess('employeeAccess', 'admin', 0))
                <td class="text-center"> @if(Helper::userAccess('employee.edit', 'admin'))<i class="fa fa-edit" id="edit" data="{{$employeeBasicInfo->user_id}}"></i>@endif @if(Helper::userAccess('employee.destroy', 'admin'))<i class="fa fa-trash-o" id="delete" data="{{$employeeBasicInfo->user_id}}"></i>@endif<br> @if(Helper::userAccess('employeeAccess', 'admin', 0))<button url="employeeAccess" data="{{$employeeBasicInfo->user_id}}" callBack="employeeAccessView" class="go-btn btn btn-default btn-xs" type="button">Access</button>@endif</td>
                @endif
            </tr>
        @endforeach
        @else    
            <tr>
                <td colspan="10" class="emptyMessage">Empty</td>
            </tr>
        @endif
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            @include("pagination")
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
         $(".select2").select2({
            placeholder: "Select"
        });
    });

    $(".employeeEmailResend").click(function(){
        var user_id = $(this).val();
        $.ajax({
            url : appUrl.getSiteAction('/employeeEmailResend'),
            type: "GET",
            data: {"user_id":user_id},
            dataType: "json",
            success:function(data){
                $.gritter.add({
                    title: data.msg_title,
                    text: data.messege,
                    time: "",
                    close_icon: "entypo-icon-cancel s12",
                    icon: data.messege_icon,
                    class_name: data.msgType
                });
            }
        });
    });
</script>