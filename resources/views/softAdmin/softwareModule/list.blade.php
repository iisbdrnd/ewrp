<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if(Helper::adminAccess('softwareModule.create'))
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
            @include("perPageBox")
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="25%" data="1">Folder</th>
                <th width="25%" data="2">Module Name</th>
                <th width="18%" data="3">URL Prefix</th>
                <th width="17%" data="4">Route Prefix</th>
                <th width="20%" data="5">Last Updated</th>
                <th width="8%" data="6">Status</th>
                @if(Helper::adminAccess('softwareModule.edit') || Helper::adminAccess('softwareModule.destroy'))
                <th width="7%">Action</th>
                @endif
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Folder</th>
                <th>Module Name</th>
                <th>URL Prefix</th>
                <th>Route Prefix</th>
                <th>Last Updated</th>
                <th>Status</th>
                @if(Helper::adminAccess('softwareModule.edit') || Helper::adminAccess('softwareModule.destroy'))
                <th>Action</th>
                @endif
            </tr>
        </tfoot>
        <tbody>
		<?php $paginate = $softwareModules; ?>
		@if(count($softwareModules)>0)
			@foreach($softwareModules as $softwareModule)
				<tr>
                    <td>{{$sn++}}</td>
					<td>{{$softwareModule->folder_name}}</td>
					<td><i class="s16 {{$softwareModule->module_icon}}"></i> {{$softwareModule->module_name}}</td>
					<td>{{$softwareModule->url_prefix}}</td>
					<td>{{$softwareModule->route_prefix}}</td>
					<td>{{$softwareModule->updated_at}}</td>
					<td>@if($softwareModule->status==1){{'Active'}}@else{{'Inactive'}}@endif</td>
					@if(Helper::adminAccess('softwareModule.edit') || Helper::adminAccess('softwareModule.destroy'))
					<td>@if(Helper::adminAccess('softwareModule.edit'))<i class="fa fa-edit" id="edit" data="{{$softwareModule->id}}"></i>@endif @if(Helper::adminAccess('softwareModule.destroy'))<i class="fa fa-trash-o" id="delete" data="{{$softwareModule->id}}"></i>@endif</td>
					@endif
				</tr>
			@endforeach
		@else    
			<tr>
				<td colspan="7" class="emptyMessage">Empty</td>
			</tr>
		@endif
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <?php $paginate = $softwareModules; ?>
            @include("pagination")
        </div>
    </div>
</div>