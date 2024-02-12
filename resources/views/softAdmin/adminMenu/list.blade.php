<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if(Helper::adminAccess('adminMenu.create'))
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
            @include("perPageBox")
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="20%" data="1">Menu Name</th>
                <th width="20%" data="2">Route</th>
                <th width="20%" data="3">Parent Menu</th>
                <th width="8%" data="4">Status</th>
                @if(Helper::adminAccess('adminMenu.edit') || Helper::adminAccess('adminMenu.destroy'))
                <th width="7%">Action</th>
                @endif
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Menu Name</th>
                <th>Route</th>
                <th>Parent Menu</th>
                <th>Status</th>
                @if(Helper::adminAccess('adminMenu.edit') || Helper::adminAccess('adminMenu.destroy'))
                <th>Action</th>
                @endif
            </tr>
        </tfoot>
        <tbody>
        <?php $paginate = $adminMenu; ?>
		@if(count($adminMenu)>0)
			@foreach($adminMenu as $adminMenu)
				<tr>
					<td>{{$sn++}}</td>
					<td>{{$adminMenu->menu_name}}</td>
					<td>{{$adminMenu->route}}</td>
					<td>{{$adminMenu->parent_menu_name}}</td>
					<td>@if($adminMenu->status==1){{'Active'}}@else{{'Inactive'}}@endif</td>
					@if(Helper::adminAccess('adminMenu.edit') || Helper::adminAccess('adminMenu.destroy'))
					<td>@if(Helper::adminAccess('adminMenu.edit'))<i class="fa fa-edit" id="edit" data="{{$adminMenu->id}}"></i>@endif @if(Helper::adminAccess('adminMenu.destroy'))<i class="fa fa-trash-o" id="delete" data="{{$adminMenu->id}}"></i>@endif</td>
					@endif
				</tr>
			@endforeach
		@else    
			<tr>
				<td colspan="6" class="emptyMessage">Empty</td>
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