<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            <button url="operationalTeamSorting" main-selector="mainDiv" clone-selector="cloneDiv" data-prefix="cl" panel-title="Commercials Sorting Sorting" class="go-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="s14 icomoon-icon-sort"></i>Sorting</button>
            @if($access->create)
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
                        <th width="10%" data="1">Image</th>
                        <th width="30%" data="1">Name</th>
                        <th width="10%" data="2">Designation</th>
                        <th width="30%">Address</th>
                        <th width="15%">Email</th>
                        <th width="10%">Phone</th>
                        @if($access->edit || $access->destroy)
                        <th width="13%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone</th>
                        @if($access->edit || $access->destroy)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $departments; ?>
                @if(count($departments)>0)  
                @foreach($departments as $department)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td><img src="{{asset('public/uploads/managementTeam/thumb')}}/{{$department->image}}" width="50"/></td>
                        <td>{{$department->name}}</td>
                        <td>{!! $department->designation !!}</td>
                        <td>{{$department->address}}</td>
                        <td>{{$department->email}}</td>
                        <td>{{$department->phone}}</td>
                        @if($access->edit || $access->destroy)
                        <td>@if($access->edit)<i class="fa fa-edit" id="edit" data="{{$department->id}}"></i>@endif @if($access->destroy)<i class="fa fa-trash-o" id="delete" data="{{$department->id}}"></i>@endif</td>
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
