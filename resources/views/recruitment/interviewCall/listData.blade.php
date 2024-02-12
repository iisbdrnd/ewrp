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
            <button class="add-btn btn btn-default pull-right btn-sm" view-type="modal" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Assign Project For Interview</button>
            @endif
            @include("perPageBox")
        </div>
    </div>
</div>
<div id=myTabContent2 class=tab-content>
    <div class="tab-pane fade active in" id=home2>
         {{-- <h1>{{ $ew_project_id }}</h1> --}}
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="1%">No.</th>
                        <th width="25%" data="1">Project Name</th>
                        <th width="5%" data="3">Status</th>
                        @if($access->edit || $access->destroy)
                        <th width="1%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Project Name</th>
                        <th>Status</th>
                        @if($access->edit || $access->destroy)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $interviewCalls; ?>
                @if(count($interviewCalls)>0)  
                @foreach($interviewCalls as $interviewCall)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{ @Helper::projects($interviewCall->ew_project_id)->project_name }}</td>
                        <td class="text-center">
                            <button url="interview-call-status/{{ $interviewCall->id }}" 
                                class="add-btn btn  {{ $interviewCall->status == 1?
                                'btn-success': ($interviewCall->status == 2?
                                'btn-danger':'btn-default')  }} btn-xs" 
                                view-type="modal" 
                                type="button">
                                <i class="glyphicon glyphicon-pencil mr5"></i> 
                                {{ $interviewCall->status == 1?
                                    'Running': ($interviewCall->status == 2?
                                    'Closed':'Select Status')  }}
                            </button>
                        </td>
                        @if($access->edit || $access->destroy)
                        <td>
                            @if($access->edit)
                                <i class="fa fa-edit" view-type="modal" title="Update" id="edit" data="{{$interviewCall->id}}"></i>
                            @endif
                            @if($access->destroy)
                                <i class="fa fa-trash-o" id="delete" data="{{$interviewCall->id}}"></i>
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
