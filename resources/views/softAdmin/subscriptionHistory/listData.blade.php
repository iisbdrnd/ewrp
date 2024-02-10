<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <select name="project" event="change" data-fv-icon="false" class="select2 form-control ml0 data-search">
                <option value="" >All Subscription History</option>
                @foreach($projectIdData as $projectIdDa)
                    <option @if($projectId==$projectIdDa->id){{'selected'}}@endif value="{{$projectIdDa->id}}">{{$projectIdDa->name}} [{{$projectIdDa->project_id}}]</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 col-xs-12">
            @include("perPageBox")
        </div>
    </div>
</div>
<div id="myTabContent2" class="tab-content">
    <div class="tab-pane fade active in" id="home2">
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered searchLoader">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="12%" data="2">Subscription For</th>
                        <th width="9%" data="3">Ext. User</th>
                        <th width="10%" data="4">Current User</th>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            
                        <th width="10%" data="5">Ext. Duration</th>
						<th width="10%" data="6">Expire Date</th>
						<th width="12%" data="7">Subscription Date</th>
						<th width="12%" data="8">Subscription By</th>
                        <th width="10%" data="9">Amount</th>
                        <th width="10%" data="10">Project Name</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Subscription For</th>
                        <th>Ext. User</th>
                        <th>Current User</th>
						<th>Ext. Duration</th>
						<th>Expire Date</th>
						<th>Subscription Date</th>
						<th>Subscription By</th>
                        <th>Amount</th>
                        <th>Project Name</th>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $crmSubscriptionHistory; ?>
                @if(count($crmSubscriptionHistory)>0)  
                    @foreach($crmSubscriptionHistory as $crmSubscriptionHis)
                        <tr>
                            <td>{{$sn++}}</td>                            
							<td>@if($crmSubscriptionHis->reason==1){{'New Registration'}}@else{{'Extended Package'}}@endif</td>
							<td>{{$crmSubscriptionHis->extended_user}} Users</td>
							<td>{{$crmSubscriptionHis->current_user}} Users</td>
							<td>{{$crmSubscriptionHis->extended_duration}} Month</td>
							<td>
                                <?php 
                                    $expireDate = DateTime::createFromFormat('Y-m-d', $crmSubscriptionHis->current_expire_date);
                                    $current_expire_date = $expireDate->format('d/m/Y');
                                    echo $current_expire_date;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    $subscriptionDate = DateTime::createFromFormat('Y-m-d H:i:s', $crmSubscriptionHis->created_at);
                                    $subscription_date = $subscriptionDate->format('d/m/Y g:i A');
                                    echo $subscription_date;
                                ?>
                            </td>
							<td>{{$crmSubscriptionHis->name}}</td>
                            <td>@if($crmSubscriptionHis->currency==1){{'&#2547;'}}@elseif($crmSubscriptionHis->currency==2){{'&#36;'}}@endif{{$crmSubscriptionHis->amount}}</td>
                            <td>{{$crmSubscriptionHis->project_name.' ['.$crmSubscriptionHis->projectId.']'}}</td>
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




