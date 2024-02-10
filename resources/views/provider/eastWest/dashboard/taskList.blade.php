<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
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
                    <th width="13%" data="1">Subject</th>
                    <th width="13%" data="2">Start Date</th>
                    <th width="12%" data="3">End Date</th>
                    <th width="14%" data="4">Related To</th>
                    <th width="8%" data="5">Status</th>
                    <th width="15%" data="6">Assigned To</th>
                    @if($access->edit || $access->destroy)
                        <th width="5%">Action</th>
                    @endif
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Subject</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Related To</th>
                    <th>Status</th>
                    <th>Related To</th>
                    @if($access->edit || $access->destroy)
                        <th>Action</th>
                    @endif
                </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $crmActivitiesTask; ?>
                @if(count($crmActivitiesTask)>0)
                @foreach($crmActivitiesTask as $crmActivitiesTask)
                    <tr>
                        <td>{{$sn++}}</td>
                        <!-- <td>@if($access->show)<a class="show-details" url="activities/meeting/{{$crmActivitiesTask->id}}">{{$crmActivitiesTask->subject}}</a>@else{{$crmActivitiesTask->subject}}@endif</td> -->
                        <td>@if($access->show)<a href="activities/task/{{$crmActivitiesTask->id}}" menu-active="activities/task" class="ajax-link hand">{{$crmActivitiesTask->subject}}</a>@else{{$crmActivitiesTask->subject}}@endif</td>
                        <td><?php
                            $startDateTime = DateTime::createFromFormat('Y-m-d', $crmActivitiesTask->start_date);
                            $start_date = $startDateTime->format('d/m/Y');
                            echo $start_date;
                            ?></td>
                        <td><?php
                            $endDateTime = DateTime::createFromFormat('Y-m-d', $crmActivitiesTask->end_date);
                            $end_date = $endDateTime->format('d/m/Y');
                            echo $end_date;
                            ?></td>
                        <td>{{$crmActivitiesTask->related_to_name}}</td>
                        <td>{{$crmActivitiesTask->status_name}}</td>
                        <td>{{$crmActivitiesTask->assignedToName}}</td>
                        @if($access->edit || $access->destroy)
                            <td class="text-center">@if($access->edit)<i 
                            @if($crmActivitiesTask->created_by==$user_id || ($crmActivitiesTask->assign_to==$user_id && $crmActivitiesTask->assign_status==1)) class="fa fa-edit" id="edit" 
                            @else class="fa fa-edit icon-disabled" @endif 
                            data="{{$crmActivitiesTask->id}}"></i>@endif @if($access->destroy)<i 
                            @if($crmActivitiesTask->created_by==$user_id) class="fa fa-trash-o" id="delete" 
                            @else class="fa fa-trash-o icon-disabled" @endif 
                            data="{{$crmActivitiesTask->id}}"></i>@endif</td>
                        @endif
                    </tr>
                @endforeach
                @else    
                    <tr>
                        <td colspan="8" class="emptyMessage">Empty</td>
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
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
    });
</script>