<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if(Helper::userAccess('area.create', 'admin'))
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
                        <th width="40%" data="1">Area Name</th>
                        <th width="20%" data="2">Area Details</th>
                        <th data="3">Last Update</th>
                        @if(Helper::userAccess('area.edit', 'admin') || Helper::userAccess('area.destroy', 'admin'))
                        <th width="8%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Area Name</th>
                        <th>Area Details</th>
                        <th>Last Update</th>
                        @if(Helper::userAccess('area.edit', 'admin') || Helper::userAccess('area.destroy', 'admin'))
                        <th>Action</th>
                        @endif
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
                        <td>{{$jobArea->updated_at}}</td>
                        @if(Helper::userAccess('area.edit', 'admin') || Helper::userAccess('area.destroy', 'admin'))
                        <td>@if(Helper::userAccess('area.edit', 'admin'))<i class="fa fa-edit" id="edit" data="{{$jobArea->id}}"></i>@endif @if(Helper::userAccess('area.destroy', 'admin'))<i class="fa fa-trash-o" id="delete" data="{{$jobArea->id}}"></i>@endif</td>
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
    </div>
</div>
