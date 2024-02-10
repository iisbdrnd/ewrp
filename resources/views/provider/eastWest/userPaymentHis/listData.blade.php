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
<div id="myTabContent2" class="tab-content">
    <div class="tab-pane fade active in" id="home2">
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered searchLoader">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="20%" data="1">Transection Id</th>
                        <th width="15%" data="2">Payment For</th>
                        <th width="15%" data="3">Amount</th>
                        <th width="15%" data="4">Payment Method</th>
                        <th width="15%" data="5">Payment Date</th>
						<th width="15" data="6">Payment By</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Transection Id</th>
                        <th>Payment For</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Payment Date</th>
						<th>Payment By</th>
                    </tr>
                </tfoot>
                <tbody>
                    <?php $paginate = $paymentHistory; ?>
                    @if(count($paymentHistory)>0)  
                        @foreach($paymentHistory as $paymentHistory)
                            <tr>
                                <td>{{$sn++}}</td>
                                <td>{{$paymentHistory->tran_id}}</td>
                                <td>Course selling <br/> Course: {{$paymentHistory->course_name}}</td>
                                <td>@if($paymentHistory->currency==1){{"&#2547;"}}@else{{"&#36;"}}@endif {{number_format($paymentHistory->store_amount, 2, '.', ',')}}</td>
                                <td>{{$paymentHistory->payment_method}}</td>
                                <?php
                                    $payment_date = date("d/m/Y",strtotime($paymentHistory->created_at));
                                ?>
                                <td>{{$payment_date}}</td>
                                <td>{{$paymentHistory->trainee_name}}</td>
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




