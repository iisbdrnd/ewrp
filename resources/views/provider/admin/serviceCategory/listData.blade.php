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
                        <th width="30%" data="1">Name</th>
                        <th width="50%">Description</th>
                        <th width="50%">Request Type</th>
                        <th width="10%">planner</th>
                        @if($access->edit || $access->destroy)
                        <th width="5%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Request Type</th>
                        <th>planner</th>
                        @if($access->edit || $access->destroy)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $serviceCategories; ?>
                @if(count($serviceCategories)>0)  
                @foreach($serviceCategories as $serviceCategory)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$serviceCategory->name}}</td>
                        <td>{{$serviceCategory->description}}</td>
                        <td>@if($serviceCategory->approval_status==1){{'Auto'}}@else{{'Menual'}}@endif</td>
                        <td>
                            <button url="slectActionplanner?s_cat={{ $serviceCategory->id }}" 
                                class="add-btn btn btn-info btn-xs" 
                                view-type="modal" 
                                modal-size="medium" 
                                type="button">
                                    <i class="glyphicon glyphicon-eye-open mr5"></i> 
                                    Planner
                            </button>
                        </td>
                        @if($access->edit || $access->destroy)
                        <td>@if($access->edit)<i class="fa fa-edit" id="edit" data="{{$serviceCategory->id}}"></i>@endif @if($access->destroy)<i class="fa fa-trash-o" id="delete" data="{{$serviceCategory->id}}"></i>@endif</td>
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