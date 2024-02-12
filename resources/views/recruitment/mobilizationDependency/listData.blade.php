<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if(Auth::user()->get()->id == 4)
                @if($access->create)
                <button class="add-btn btn btn-default pull-right btn-sm" view-type="modal" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
                @endif
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
                        <th width="10%">No</th>
                        <th width="60%" data="1">Country Name</th>
                        @if(Auth::user()->get()->id == 4)
                            @if($access->edit || $access->destroy)
                            <th width="30%">Action</th>
                            @endif
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Country Name</th>
                        @if(Auth::user()->get()->id == 4)
                            @if($access->edit || $access->destroy)
                            <th>Action</th>
                            @endif
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $ewMobilizationsDependency; ?>
                @if(count($ewMobilizationsDependency)>0)  
                @foreach($ewMobilizationsDependency as $ewMobilizationdepend)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$ewMobilizationdepend->country_name}}</td>
                        @if($access->edit || $access->destroy)
                         @if(Auth::user()->get()->id == '4')
                         <td>
                            @if($access->edit)
                                <i class="fa fa-edit" title="Update" view-type="modal" id="edit" data="{{$ewMobilizationdepend->project_country_id}}"></i>
                            @endif
                           
                                @if($access->destroy)
                                    <i class="fa fa-trash-o" id="delete" data="{{$ewMobilizationdepend->project_country_id}}"></i>
                                
                            @endif
                        </td>
                        @endif
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
