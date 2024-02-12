<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-2 col-xs-12 pl0">
            <select name="bill_send_type" event="change" class="data-search form-control select2" style="width: 100%;">
                <option @if($bill_send_type==3){{'selected'}}@endif value=0>All Bill</option>
                <option @if($bill_send_type==1){{'selected'}}@endif value=1>Send Bill</option>
                <option @if($bill_send_type==2){{'selected'}}@endif value=2>Not Send Bill</option>
            </select>
        </div>
        <div class="col-md-2 col-xs-12 pl0">
            <select name="bill_transfer_type" event="change" class="data-search form-control select2" style="width: 100%;">
                <option @if($bill_transfer_type==3){{'selected'}}@endif value=0>All</option>
                <option @if($bill_transfer_type==1){{'selected'}}@endif value=1>Transferred</option>
                <option @if($bill_transfer_type==2){{'selected'}}@endif value=2>Not Transferred</option>
            </select>
        </div>
        <div class="col-md-2 col-xs-12 pl0">
            <select name="bill_paid_type" event="change" class="data-search form-control select2" style="width: 100%;">
                <option @if($bill_paid_type==3){{'selected'}}@endif value=0>All</option>
                <option @if($bill_paid_type==1){{'selected'}}@endif value=1>Bill Paid</option>
                <option @if($bill_paid_type==2){{'selected'}}@endif value=2>Bill Not Paid</option>
            </select>
        </div>
        <div class="col-md-3 col-xs-12">
            @if($access->create)
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif
            <?php $defaultPerPage=10; ?>
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
                        <th width="20%" data="1">Aviation Name</th>
                        <th width="15%" data="3">Bill No</th>
                        <th width="15%" data="4">Bill Date</th>
                        <th width="15%" data="5">Payment Amount</th>
                        <th width="7%">Bill Send</th>
                        <th width="8%">Bill Transfer</th>
                        <th width="7%">Paid Status</th>
                        @if($access->show)
                        <th width="5%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Aviation Name</th>
                        <th>Bill No</th>
                        <th>Bill Date</th>
                        <th>Payment Amount</th>
                        <th>Bill Send</th>
                        <th>Bill Transfer</th>
                        <th>Paid Status</th>
                        @if($access->show)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $aviation; ?>
                @if(count($aviation)>0)  
                @foreach($aviation as $aviation)
                    <tr class="for_status">
                        <td>{{$sn++}}</td>
                        <td>{{$aviation->company_name}} [{{$aviation->account_code}}]</td>
                        <td>{{$aviation->bill_no}}</td>
                        <td>{{$aviation->bill_date}}</td>
                        <td>{{$aviation->target_amount}}</td>
                        <td class="send_checkbox">
                            <div class="toggle-custom toggle-inline m0">
                                <label class="toggle h30" data-on=Yes data-off=No>
                                    <input class="checkbox_status" data="{{$aviation->id}}" type=checkbox name="send_status" value="1" @if($aviation->bill_send_status==1){{'checked disabled'}}@endif > <span class=button-checkbox></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <button url="ticket-bill-transfer-modal?billId={{$aviation->id}}" view-type="modal" data="{{$aviation->id}}" id="transfer" class="add-btn btn btn-info btn-xs bill-transfer @if($aviation->bill_send_status==1 && $aviation->bill_transfer_status==0)
                                {{"hand"}}     
                                @endif" type="button" 
                                @if($aviation->bill_send_status==1 && $aviation->bill_transfer_status==0)
                                {{"enabled"}}
                                @else  
                                {{"disabled"}}      
                                @endif>

                                @if($aviation->bill_send_status==1 && $aviation->bill_transfer_status==0) {{'Transfer'}}

                                @elseif($aviation->bill_send_status==0 && $aviation->bill_transfer_status==0)
                                {{'Transfer'}}

                                @else {{'Transferred'}}
                                @endif
                            </button>
                        </td>
                        <td class="send_checkbox">
                            <div class="toggle-custom toggle-inline m0">
                                <label class="toggle h30" data-on=Yes data-off=No>
                                    <input class="paid_status" data="{{$aviation->id}}" type=checkbox name="paid_status" value="1" 
                                    @if($aviation->bill_send_status==1 && $aviation->bill_transfer_status==1)
                                    {{"enabled"}}
                                    @else  
                                    {{"disabled"}}      
                                    @endif
                                    @if($aviation->paid_status==1){{'checked disabled'}}@endif > 
                                    <span class=button-checkbox></span>
                                </label>
                            </div>
                        </td>
                        @if($access->show)
                        <td>
                            <button value="{{$aviation->id}}" class="btn btn-info btn-xs preview_button">Preview</button>
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
<script type="text/javascript">
     $(document).ready(function() {
         $(".select2").select2({
            placeholder: "Select"
        });
    });
    $(document).ready(function() {

            $(".for_status").on('click','.checkbox_status', function(e) {
                var $selector = $(this);
                var send_status = $selector.val();
                var ew_ticket_bill_id = $selector.attr("data");
                
                if (send_status) {
                $.ajax({
                        url: '{{route("ew.ticketBillSend")}}',
                        data: {send_status:send_status,
                                bill_id:ew_ticket_bill_id,
                                _token: "{{ csrf_token() }}" 
                                 },
                        type: 'POST',
                        dataType: "json",
                        success: function(data) {
                            $selector.closest('tr').find('.bill-transfer').removeAttr('disabled');
                            $selector.attr('disabled','disabled');
                            $selector.addClass('disabled');
                        }
                    });
                }

            });

            $(".for_status").on('click','.paid_status', function(e) {
                var $selector = $(this);
                var paid_status = $selector.val();
                var ew_ticket_bill_id = $selector.attr("data");
                
                if (paid_status) {
                $.ajax({
                        url: '{{route("ew.ticketBillPaidStatus")}}',
                        data: {paid_status:paid_status,
                                bill_id:ew_ticket_bill_id,
                                _token: "{{ csrf_token() }}" 
                                 },
                        type: 'POST',
                        dataType: "json",
                        success: function(data) {
                            // $selector.closest('tr').find('.bill-paid-status').removeAttr('disabled');
                            $selector.attr('disabled','disabled');
                            $selector.addClass('disabled');
                        }
                    });
                }

            });

            $(".preview_button").on('click', function(e) {
                e.preventDefault();
                var bill_foreign_id = $(this).val();
                // alert(project);
                var width = $(document).width();
                var height = $(document).height();
                var previewType = 1;
                var url = "{{route('ew.ticketBillReport')}}"+'?bill_foreign_id='+bill_foreign_id;
                var myWindow = window.open(url, "", "width="+width+",height="+height);
                $('.preview_button').removeAttr('disabled').removeClass('disabled');
            });
    });
</script>