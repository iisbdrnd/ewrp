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
                <option value="" >All Payment History</option>
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
                        <th width="15%" data="1">Payment For</th>
                        <th width="20%" data="2">Payment Method</th>
						<th width="15%" data="3">Amount</th>
                        <th width="15%" data="4">Payment Date</th>
                        <th width="15%" data="5">Payment By</th>
						<th width="20%" data="6">Project Name</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Payment For</th>
                        <th>Payment Method</th>
						<th>Amount</th>
                        <th>Payment Date</th>
                        <th>Payment By</th>
						<th>Project Name</th>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $crmUserPaymentHistory; ?>
                @if(count($crmUserPaymentHistory)>0)  
                    @foreach($crmUserPaymentHistory as $crmUserPaymentHis)
                        <tr>
                            <td>{{$sn++}}</td>
                            <td>@if($crmUserPaymentHis->reason_id==1){{'Registration'}}@else{{'Extended Package'}}@endif</td>
                            <td>{{$crmUserPaymentHis->payment_method}}</td>
							<td>@if($crmUserPaymentHis->currency==1){{'&#2547;'}}@elseif($crmUserPaymentHis->currency==2){{'&#36;'}}@endif{{$crmUserPaymentHis->amount}}</td>
                            <td>
                                <?php 
                                    $paymentDate = DateTime::createFromFormat('Y-m-d H:i:s', $crmUserPaymentHis->created_at);
                                    $payment_date = $paymentDate->format('d/m/Y g:i A');
                                    echo $payment_date;
                                ?>
                            </td>
                            <td>{{$crmUserPaymentHis->name}}</td>
							<td>{{$crmUserPaymentHis->project_name.' ['.$crmUserPaymentHis->projectId.']'}}</td>
                        </tr>
                    @endforeach
                @else    
                    <tr>
                        <td colspan="6" class="emptyMessage">Empty</td>
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



