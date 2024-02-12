<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
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
                        <th width="20%" data="1">Area Name</th>
                        <th width="25%" data="2">Area Details</th>
                        <th width="10%" data="3">Project ID</th>
                        <th width="20%" data="4">Project Name</th>
                        <th width="14%" data="5">Last Update</th>
                        <th width="6%">Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Area Name</th>
                        <th>Area Details</th>
                        <th>Project ID</th>
                        <th>Project Name</th>
                        <th>Last Update</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $jobArea; ?>
				@if(count($jobArea)>0)
					@foreach($jobArea as $jobArea)
						<tr>
							<td>{{$sn++}}</td>
							<td>{{$jobArea->area_name}}</td>
							<td>{{$jobArea->area_details}}</td>
							<td>{{$jobArea->project_id_view}}</td>
							<td>{{$jobArea->project_name}}</td>
							<td>{{$jobArea->updated_at}}</td>
							<td><i class="fa fa-edit" id="edit" data="{{$jobArea->id}}"></i><i class="fa fa-trash-o" id="delete" data="{{$jobArea->id}}"></i></td>
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
