<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" placeholder="Search">
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
                    <th width="20%" data="1">Name</th>
                    <th width="15%" data="2">Company</th>
                    <th width="15%" data="3">Mobile</th>
                    <th width="15%" data="4">Email</th>
                    <th width="15%" data="5">Status</th>
                    <th width="15%" data="6">Owner</th>
                    @if($access->edit || $access->destroy)
                    <th width="2%">Action</th>
                    @endif
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th>Company</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Owner</th>
                    @if($access->edit || $access->destroy)
                    <th>Action</th>
                    @endif
                </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $crmLeadInfo; ?>    
                @if(count($crmLeadInfo)>0)
                    @foreach($crmLeadInfo as $crmLeadInfo)
                        <tr>
                            <td>{{$sn++}}</td>
                            <td>@if($access->show)<a menu-active="leads" @if($user_id==$crmLeadInfo->assign_to) href="leads/{{$crmLeadInfo->id}}" class="ajax-link hand" @else url="leads/{{$crmLeadInfo->id}}?section=2&sub_section=3" class="show-details hand" @endif>{{$crmLeadInfo->name}}</a>@else{{$crmLeadInfo->name}}@endif</td>
                            <td>{{$crmLeadInfo->company_name}}</td>
                            <td>{{$crmLeadInfo->mobile}}</td>
                            <td>{{$crmLeadInfo->email}}</td>
                            <td>{{$crmLeadInfo->stage_name}}</td>
                            <td>{{$crmLeadInfo->ownerName}}</td>
                            @if($access->edit || $access->destroy)
                            <td class="text-center">@if($user_id==$crmLeadInfo->assign_to) @if($access->edit)<i class="fa fa-edit" id="edit" data="{{$crmLeadInfo->id}}"></i>@endif @if($access->destroy)<i class="fa fa-trash-o" id="delete" data="{{$crmLeadInfo->id}}"></i>@endif @else @if($access->edit)<i class="fa fa-edit icon-disabled"></i>@endif @if($access->destroy)<i class="fa fa-trash-o icon-disabled"></i>@endif @endif</td>
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