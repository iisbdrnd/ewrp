<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-3 col-xs-12 pl0" style="margin-left: -17px;">
            <select id="projects" name="project" event="change" class="data-search form-control select2" style="width: 100%;">
                <option value="">Select Project</option>
                @foreach($projectIdData as $projectIdDa)
                    <option @if($projectId==$projectIdDa->id){{'selected'}}@endif value="{{$projectIdDa->id}}">{{$projectIdDa->name}} [{{$projectIdDa->project_id}}]</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 col-xs-12">
            <select name="email_verified" event="change" data-fv-icon="false" class="select2 form-control ml0 data-search">
                <option value=3 @if($email_verified==3){{'selected'}}@endif>All Employee</option>
                <option value=1 @if($email_verified==1){{'selected'}}@endif>Email Verified</option>
                <option value=2 @if($email_verified==2){{'selected'}}@endif>Email Unverified</option>
            </select>
        </div>
        <div class="col-md-3 col-xs-12" style="float:right;">
            @if(Helper::adminAccess('user.create'))
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
            @include("perPageBox")
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="5%" data="1">Emp.ID</th>
                <th width="13%" data="2">Name</th>
                <th width="10%" data="3">Designation</th>
                <th width="11%" data="4">Email</th>
                <th width="12%" data="5">Project ID</th>
                <th width="15%" data="6">Report To</th>
                <th width="7%" data="7">Verified</th>
                <th width="6%" data="8">Status</th>
                <th width="12%" data="9">Reg. Date</th>
                @if(Helper::adminAccess('user.edit') || Helper::adminAccess('user.destroy') || Helper::adminAccess('userAccess', 0))
                <th width="6%" class="text-center">Action</th>
                @endif
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Emp. ID</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Email</th>
                <th>Project ID</th>
                <th>Report To</th>
                <th>Verified</th>
                <th>Status</th>
                <th>Reg. Date</th>
                @if(Helper::adminAccess('en_provider_user.edit') || Helper::adminAccess('en_provider_user.destroy') || Helper::adminAccess('userAccess', 0))
                <th class="text-center">Action</th>
                @endif
            </tr>
        </tfoot>
        <tbody>
		<?php $paginate = $users; ?>
		@if(count($users)>0)
			@foreach($users as $user)
				<tr>
					<td>{{$sn++}}</td>
					<td>{{$user->emp_id}}</td>
                    <td>@if(Helper::adminAccess('en_provider_user.show'))<a href="user/{{$user->id}}" menu-active="user" class="ajax-link hand" data-toggle="tooltip" data-placement="right" data-html="true" title="{{$user->name}}">{{$user->name}}</a>@else{{$user->name}}@endif</td>
					<td>{{$user->designation}}</td>
					<td>{{$user->email}}</td>
					<td>{{$user->project_identity}}<br>[{{$user->project_name}}]</td>
					<td>@if(!empty($user->report_to_name)){{$user->report_to_name}}@else{{'N/A'}}@endif</td>
					<td style="text-align:center;">@if($user->email_verified==1){{'Yes'}}@else{{'No'}} 
                        <button class="btn btn-default btn-xs userEmailResend" type="button" onclick="userEmailResend('{{$user->userId}}','{{$user->project_id}}');">Resend Email</button>@endif
                    </td>
                    <td>{{$user->status}}</td>
                    <?php
                        $regDate = DateTime::createFromFormat('Y-m-d H:i:s', $user->regDate);
                        $reg_date = $regDate->format('d/m/Y g:i A');
                    ?>
                    <td>{{$reg_date}}</td>
					@if(Helper::adminAccess('en_provider_user.edit') || Helper::adminAccess('en_provider_user.destroy') || Helper::adminAccess('userAccess', 0))
					<td class="text-center">@if(Helper::adminAccess('en_provider_user.edit'))<i class="fa fa-edit" id="edit" data="{{$user->user_id}}"></i>@endif @if(Helper::adminAccess('en_provider_user.destroy'))<i class="fa fa-trash-o" id="delete" data="{{$user->user_id}}"></i>@endif<br>@if(Helper::adminAccess('userAccess', 0))<button url="userAccess" data="{{$user->user_id}}" callBack="userAccessView" class="go-btn btn btn-default btn-xs" type="button">Access</button>@endif<br>@if(Helper::adminAccess('userLogin', 0))<button data="{{$user->user_id}}" class="user-login btn btn-default btn-xs mt5" type="button">Login</button>@endif</td>
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
            <?php $paginate = $users; ?>
            @include("pagination")
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select Project"
        });

        $(".user-login").on("click", function(e){
            e.preventDefault();
            $.ajax({
                url : appUrl.getSiteAction('/userLogin'),
                data: {id: $(this).attr('data')},
                type: 'GET',
                dataType: "json",
                success: function(data) {
                    if(parseInt(data)===0) {
                        redirectLoginPage();
                    } else {
                        if(data.result) {
                            window.open("{{route('provider.apps')}}");
                        } else {
                            swal("Cancelled", data.msg, "error");
                        }
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    swal("Cancelled", errorThrown, "error");
                },
                async: false
            });
        });

    });

    function userEmailResend(userId,project_id){
        if(userId!='' && project_id!=''){
            $.ajax({
                url : appUrl.getSiteAction('/userEmailResend'),
                type: "GET",
                data: {"user_id":userId, "project_id":project_id},
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
         }
        }
</script>