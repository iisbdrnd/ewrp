<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
            <div class="input-group">
                <button  view-type="modal" modal-size="large" url="generateMobilizeList" class="add-btn hand btn btn-info">
                    Sorting Moblization List
                </button>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            @if(Auth::user()->get()->id == 48)
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
                        <th width="5%">No. {{ Auth::user()->get()->id }}</th>
                        <th width="30%" data="1">Mobilization Name</th>
                        <th width="30%" data="1">Mobilization Action</th>
                        <th data="3" width="30%">Last Update</th>
                        @if(Auth::user()->get()->name == 'Shere Ali')
                            @if($access->edit || $access->destroy)
                            <th width="5%">Action</th>
                            @endif
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Mobilization Name</th>
                        <th>Mobilization Action</th>
                        <th>Last Update</th>
                        @if(Auth::user()->get()->name == 'Shere Ali')
                            @if($access->edit || $access->destroy)
                            <th>Action</th>
                            @endif
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $ewMobilizations; ?>
                @if(count($ewMobilizations)>0)  
                @foreach($ewMobilizations as $ewMobilization)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$ewMobilization->name}}</td>
                        <td>{{ $ewMobilization->mobilize_action == 1?'Follow Up':($ewMobilization->mobilize_action == 2?'Operation':'') }}</td>
                        <td>{{$ewMobilization->updated_at}} </td>
                        @if($access->edit || $access->destroy)
                         @if(Auth::user()->get()->name == 'Shere Ali')
                         <td>
                            @if($access->edit)
                                <i class="fa fa-edit" title="Update" view-type="modal" id="edit" data="{{$ewMobilization->id}}"></i>
                            @endif
                           
                                @if($access->destroy)
                                    <i class="fa fa-trash-o" id="delete" data="{{$ewMobilization->id}}"></i>
                                
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
