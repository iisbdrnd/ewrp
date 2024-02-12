<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if(Helper::adminAccess('admin.create'))
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
            @include("perPageBox")
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="30%" data="1">Name</th>
                <th width="20%" data="2">Username</th>
                <th width="23%" data="3">Email</th>
                @if(Helper::adminAccess('admin.edit') || Helper::adminAccess('admin.destroy') || Helper::adminAccess('adminAccess', 0))
                <th class="text-center" width="7%">Action</th>
                @endif
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Username</th>
                <th>Email</th>
                @if(Helper::adminAccess('admin.edit') || Helper::adminAccess('admin.destroy') || Helper::adminAccess('adminAccess', 0))
                <th class="text-center">Action</th>
                @endif
            </tr>
        </tfoot>
        <tbody>
        <?php $paginate = $admin; ?>
		@if(count($admin)>0)
        @foreach($admin as $admin)
            <tr>
                <td>{{$sn++}}</td>
                <td>{{$admin->name}}</td>
                <td>{{$admin->username}}</td>
                <td>{{$admin->email}}</td>
                @if(Helper::adminAccess('admin.edit') || Helper::adminAccess('admin.destroy') || Helper::adminAccess('adminAccess', 0))
                <td class="text-center">@if(Helper::adminAccess('admin.edit'))<i class="fa fa-edit" id="edit" data="{{$admin->id}}"></i>@endif @if(Helper::adminAccess('admin.destroy'))<i class="fa fa-trash-o" id="delete" data="{{$admin->id}}"></i>@endif<br>@if(Helper::adminAccess('adminAccess', 0))<button url="adminAccess" data="{{$admin->id}}" callBack="adminAccessView" class="go-btn btn btn-default btn-xs" type="button">Access</button>@endif</td>
                @endif
            </tr>
        @endforeach
		@else    
			<tr>
				<td colspan="5" class="emptyMessage">Empty</td>
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