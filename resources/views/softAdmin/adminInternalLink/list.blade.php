<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if(Helper::adminAccess('adminInternalLink.create'))
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
            @include("perPageBox")
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="30%" data="1">Link Name</th>
                <th width="26%" data="2">Route</th>
                <th width="25%" data="3">Menu</th>
                <th width="8%" data="4">Status</th>
                @if(Helper::adminAccess('adminInternalLink.edit') || Helper::adminAccess('adminInternalLink.destroy'))
                <th width="6%">Action</th>
                @endif
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Link Name</th>
                <th>Route</th>
                <th>Menu</th>
                <th>Status</th>
                @if(Helper::adminAccess('adminInternalLink.edit') || Helper::adminAccess('adminInternalLink.destroy'))
                <th>Action</th>
                @endif
            </tr>
        </tfoot>
        <tbody>
        <?php $paginate = $adminInternalLink; ?>
		@if(count($adminInternalLink)>0)
        @foreach($adminInternalLink as $adminInternalLink)
            <tr>
                <td>{{$sn++}}</td>
                <td>{{$adminInternalLink->link_name}}</td>
                <td>{{$adminInternalLink->route}}</td>
                <td>{{$adminInternalLink->menu_name}}</td>
                <td>@if($adminInternalLink->status==1){{'Active'}}@else{{'Inactive'}}@endif</td>
                @if(Helper::adminAccess('adminInternalLink.edit') || Helper::adminAccess('adminInternalLink.destroy'))
                <td>@if(Helper::adminAccess('adminInternalLink.edit'))<i class="fa fa-edit" id="edit" data="{{$adminInternalLink->id}}"></i>@endif @if(Helper::adminAccess('adminInternalLink.destroy'))<i class="fa fa-trash-o" id="delete" data="{{$adminInternalLink->id}}"></i>@endif</td>
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