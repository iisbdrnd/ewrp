<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if(Helper::adminAccess('softwareInternalLink.create'))
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
            @include("perPageBox")
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="10%" data="1">Folder</th>
                <th width="15%" data="2">Link Name</th>
                <th width="20%" data="3">Route</th>
                <th width="18%" data="4">Menu</th>
                <th width="19%" data="5">Module</th>
                <th width="8%" data="6">Status</th>
                @if(Helper::adminAccess('softwareInternalLink.edit') || Helper::adminAccess('softwareInternalLink.destroy'))
                <th width="5%">Action</th>
                @endif
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Folder</th>
                <th>Link Name</th>
                <th>Route</th>
                <th>Menu</th>
                <th>Module</th>
                <th>Status</th>
                @if(Helper::adminAccess('softwareInternalLink.edit') || Helper::adminAccess('softwareInternalLink.destroy'))
                <th>Action</th>
                @endif
            </tr>
        </tfoot>
        <tbody>
		<?php $paginate = $softwareInternalLinkLists; ?>
		@if(count($softwareInternalLinkLists)>0)
        @foreach($softwareInternalLinkLists as $softwareInternalLink)
            <tr>
                <td>{{$sn++}}</td>
                <td>{{$softwareInternalLink->folder_name}}</td>
                <td>{{$softwareInternalLink->link_name}}</td>
                <td>{{$softwareInternalLink->route}}</td>
                <td>{{$softwareInternalLink->menu_name}}</td>
                <td>{{$softwareInternalLink->module_name}}</td>
                <td>@if($softwareInternalLink->status==1){{'Active'}}@else{{'Inactive'}}@endif</td>
                @if(Helper::adminAccess('softwareInternalLink.edit') || Helper::adminAccess('softwareInternalLink.destroy'))
                <td>@if(Helper::adminAccess('softwareInternalLink.edit'))<i class="fa fa-edit" id="edit" data="{{$softwareInternalLink->id}}"></i>@endif @if(Helper::adminAccess('softwareInternalLink.destroy'))<i class="fa fa-trash-o" id="delete" data="{{$softwareInternalLink->id}}"></i>@endif</td>
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
            <?php $paginate = $softwareInternalLinkLists; ?>
            @include("pagination")
        </div>
    </div>
</div>