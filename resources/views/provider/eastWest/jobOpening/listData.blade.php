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
                        <th width="20%" data="1">Company</th>
                        <th width="10%">Country</th>
                        <th width="15%">Trade</th>
                        <th width="8%">Qty.</th>
                        <th width="7%">Salary</th>
                        <th width="8%">Food</th>
                        <th width="8%">Room</th>
                        <th width="6%">Age</th>
                        <th width="8%">Interview</th>
                        @if($access->edit || $access->destroy)
                        <th width="5%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Company</th>
                        <th>Country</th>
                        <th>Trade</th>
                        <th>Qty.</th>
                        <th>Salary</th>
                        <th>Food</th>
                        <th>Room</th>
                        <th>Age</th>
                        <th>Interview</th>
                        @if($access->edit || $access->destroy)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $jobs; ?>
                @if(count($jobs)>0)  
                @foreach($jobs as $job)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$job->company_name}}</td>
                        <td>{{$job->country_name}}</td>
                        <td>{{ $job->category_name }}</td>
                        <td>{{$job->quantity}}</td>
                        <td>{{$job->salary}}</td>
                        <td>{{$job->food_status}}</td>
                        <td>{{$job->accommodation_status}}</td>
                        <td>{{$job->age}}</td>
                        <td>
                            <button  url="setInterview?job_id={{$job->id}}" class="add-btn btn {{$job->interview_status == 1 ? 'btn-success' : 'btn-info'}} btn-xs mt-2" view-type="modal" modal-size="medium" type="button" title="Set Job Interview" style="margin-top: 2px;">Set Job Interview</button>
                        </td>
                        @if($access->edit || $access->destroy)
                        <td>@if($access->edit)<i class="fa fa-edit" id="edit" data="{{$job->id}}"></i>@endif @if($access->destroy)<i class="fa fa-trash-o" id="delete" data="{{$job->id}}"></i>@endif</td>
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
