<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}"placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if(Helper::adminAccess('providerDesignation.create'))
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
            @include("perPageBox")
        </div>
    </div>
</div>
<div id=myTabContent2 class=tab-content>
    <div class="tab-pane fade active in" id=home2>
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="20%" data="1"> Designation Name</th>
                        <th width="15%" data="2">Grade</th>
                        <th width="15%" data="3"> Project ID</th>
                        <th data="12">Last Update</th>
                        @if(Helper::adminAccess('providerDesignation.edit') || Helper::adminAccess('providerDesignation.destroy'))
                        <th width="5%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Designation Name</th>
                        <th>Grade</th>
                        <th>Project ID</th>
                        <th>Last Update</th>
                        @if(Helper::adminAccess('providerDesignation.edit') || Helper::adminAccess('providerDesignation.destroy'))
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $enProviderDesignations; ?>
				@if(count($enProviderDesignations)>0)
					@foreach($enProviderDesignations as $adminDesignation)
						<tr>
							<td>{{$sn++}}</td>
							<td>{{$adminDesignation->name}}</td>
							<td>{{$adminDesignation->grade}}</td>
							<td>{{$adminDesignation->project_id}}</td>
							<td>{{$adminDesignation->updated_at}}</td>
							@if(Helper::adminAccess('providerDesignation.edit') || Helper::adminAccess('providerDesignation.destroy'))
							<td>@if(Helper::adminAccess('providerDesignation.edit'))<i class="fa fa-edit" id="edit" data="{{$adminDesignation->id}}"></i>@endif @if(Helper::adminAccess('providerDesignation.destroy'))<i class="fa fa-trash-o" id="delete" data="{{$adminDesignation->id}}"></i>@endif</td>
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
                    @include("pagination")
                </div>
            </div>
        </div>
    </div>
</div>
