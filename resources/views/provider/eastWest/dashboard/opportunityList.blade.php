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
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
        <tr>
            <th width="5%">No.</th>
            <th width="20%" data="1">Opportunity</th>
            <th width="18%" data="2">Account</th>
            <th width="10%" data="3">Stage</th>
            <th width="10%" data="4">Progress</th>
            <th width="20%" data="5">Amount</th>
            <th width="10%" data="6">Owner</th>
            @if($access->edit || $access->destroy)
                <th width="7%">Action</th>
            @endif
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>No.</th>
            <th>Opportunity</th>
            <th>Account</th>
            <th>Stage</th>
            <th>Progress</th>
            <th>Amount</th>
            <th>Owner</th>
            @if($access->edit || $access->destroy)
                <th>Action</th>
            @endif
        </tr>
        </tfoot>
        <tbody>
        <?php $paginate = $crmOpportunities; ?>
        @if(count($crmOpportunities)>0)
            @foreach($crmOpportunities as $crmOpportunity)
                <tr>
                    <td>{{$sn++}}</td>
                    <td>@if($access->show)<a menu-active="opportunities" @if($user_id==$crmOpportunity->assign_to) href="opportunities/{{$crmOpportunity->id}}" class="ajax-link hand" @else url="opportunities/{{$crmOpportunity->id}}?section=2&sub_section=3" class="show-details hand" @endif >{{$crmOpportunity->opportunity_name}}</a>@else{{$crmOpportunity->opportunity_name}}@endif</td>
                    <td>{{$crmOpportunity->account_name}}</td>
                    <td>{{$crmOpportunity->stage_name}}</td>
                    <td>{{$crmOpportunity->percentage}}%</td>
                    <td>{{$crmOpportunity->html_code}} {{number_format($crmOpportunity->opportunity_amount, 2, '.', ',')}}</td>
                    <td>{{$crmOpportunity->ownerName}}</td>
                    @if($access->edit || $access->destroy)
                        <td class="text-center">@if($user_id==$crmOpportunity->assign_to) @if($access->edit)<i class="fa fa-edit" id="edit" data="{{$crmOpportunity->id}}"></i>@endif @if($access->destroy)<i class="fa fa-trash-o" id="delete" data="{{$crmOpportunity->id}}"></i>@endif @else @if($access->edit)<i class="fa fa-edit icon-disabled"></i>@endif @if($access->destroy)<i class="fa fa-trash-o icon-disabled"></i>@endif @endif</td>
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