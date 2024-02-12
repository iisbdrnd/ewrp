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
                        <th width="20%" data="1">Company Name</th>
                        <th width="20%" data="2">Account Name</th>
                        <th width="10%" data="3">Acc Code</th>
                        <th width="10%" data="4">Contact Person</th>
                        <th width="15%" data="5">Contact Number</th>
                        <th width="15%" data="6">Address</th>
                        @if($access->edit || $access->destroy)
                        <th width="5%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Company Name</th>
                        <th>Account Name</th>
                        <th>Acc Code</th>
                        <th>Contact Person</th>
                        <th>Contact Number</th>
                        <th>Address</th>
                        @if($access->edit || $access->destroy)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $aviation; ?>
                @if(count($aviation)>0)  
                @foreach($aviation as $aviation)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$aviation->company_name}}</td>
                        <td>{{$aviation->account_name}}</td>
                        <td>{{$aviation->account_code}}</td>
                        <td>{{$aviation->contact_person}}</td>
                        <td>{{$aviation->contact_no}}</td>
                        <td>{{$aviation->address}}</td>
                        @if($access->edit || $access->destroy)
                        <td>
                            @if($access->edit)<i class="fa fa-edit" title="Update" id="edit" data="{{$aviation->id}}"></i>
                            @endif
                            @if($access->destroy)
                            <i class="fa fa-trash-o" id="delete" data="{{$aviation->id}}"></i>@endif
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
