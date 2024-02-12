<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if($access->create)
            <button class="add-btn btn btn-default pull-right btn-sm" view-type="modal" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
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
                        <th width="25%" data="1">Project Name</th>
                        <th data="2">Trades</th>
                        <th width="15%" data="3">Last Update</th>
                        @if($access->edit || $access->destroy)
                        <th width="8%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Project Name</th>
                        <th>Trades</th>
                        <th>Last Update</th>
                        @if($access->edit || $access->destroy)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $ewProjects; ?>
                @if(count($ewProjects)>0)  
                @foreach($ewProjects as $ewProjects)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$ewProjects->project_name}}</td>
                        <td>{{$ewProjects->trades}}</td>
                        <td>{{$ewProjects->updated_at}}</td>
                        @if($access->edit || $access->destroy)
                        <td>
                            @if($access->edit)
                                <i class="fa fa-edit" view-type="modal" title="Update" id="edit" data="{{$ewProjects->id}}"></i>
                            @endif
                            @if($access->destroy)
                                <i class="fa fa-trash-o" id="delete" data="{{$ewProjects->id}}"></i>
                            @endif
                        </td>
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
