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
                    <th width="30%" data="1">Account Name</th>
                    <th data="2">Phone</th>
                    <th data="3">Type</th>
                    <th data="4">Owner</th>
                    @if($access->edit || $access->destroy)
                        <th class="text-center" width="5%">Action</th>
                    @endif

                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Account Name</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Owner</th>
                    @if($access->edit || $access->destroy)
                        <th>Action</th>
                    @endif
                </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $crmAccounts; ?>
                @if(count($crmAccounts)>0)
                    @foreach($crmAccounts as $crmAccount)
                        <tr>
                            <td>{{$sn++}}</td>
                            <td>@if($access->show)<a href="accounts/{{$crmAccount->id}}" menu-active="accounts" class="ajax-link hand">{{$crmAccount->account_name}}</a>@else{{$crmAccount->account_name}}@endif</td>
                            <td>{{$crmAccount->phone}}</td>
                            <td>{{$crmAccount->type_name}}</td>
                            <td>{{$crmAccount->ownerName}}</td>
                            @if($access->edit || $access->destroy)
                                <td class="text-center">@if($user_id==$crmAccount->assign_to) @if($access->edit)<i class="fa fa-edit" id="edit" data="{{$crmAccount->id}}"></i>@endif @if($access->destroy)<i class="fa fa-trash-o" id="delete" data="{{$crmAccount->id}}"></i>@endif @else @if($access->edit)<i class="fa fa-edit icon-disabled"></i>@endif @if($access->destroy)<i class="fa fa-trash-o icon-disabled"></i>@endif @endif</td>
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
