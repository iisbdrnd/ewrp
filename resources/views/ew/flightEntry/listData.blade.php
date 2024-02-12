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
                        <th width="35%" data="1">Candidate Name</th>
                        <th width="20%" data="2">Flight No</th>
                        <th width="20%" data="3">Flight Date</th>
                        <th width="15%" data="4">Status</th>
                        @if($access->edit)
                        <th width="5%">Action</th>
                        @endif
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Candidate Name</th>
                        <th>Flight No</th>
                        <th>Flight Date</th>
                        <th>Status</th>
                        @if($access->edit)
                        <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $ewFlights; ?>
                @if(count($ewFlights)>0)  
                @foreach($ewFlights as $ewFlights)
                    <tr>
                        <td>{{$sn++}}</td>
                        <td>{{$ewFlights->candidate_name}}</td>
                        <td>{{$ewFlights->flight_no}}</td>
                        <?php 
                            $flightDate = DateTime::createFromFormat('Y-m-d', $ewFlights->flight_date);
                            $flight_date = $flightDate->format('d/m/Y');
                        ?>
                        <td>{{$flight_date}}</td>
                        <td>@if($ewFlights->flight_status==1){{"Active"}}@else{{"Canceled"}}@endif</td>
                            <?php
                                $flightDate = DateTime::createFromFormat('Y-m-d', $ewFlights->flight_date);
                                $flight_date = $flightDate->format('d/m/Y');
                            ?>
                        @if($access->edit)
                        <td class="tac">
                            @if($access->edit)<i class="fa fa-edit" id="edit" data="{{$ewFlights->id}}"></i>@endif
                        </td>
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
