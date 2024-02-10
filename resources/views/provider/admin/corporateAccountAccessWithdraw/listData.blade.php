<style type="text/css">
@media only screen and (max-width: 768px) {
  #second_row {
    margin-bottom: 2%;
  }
}
@media only screen and (max-width: 414px) {
  #second_row {
    margin-bottom: 3%;
  }
}
</style>
<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-primary" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-3 col-xs-12" id="second_row">
            <select name="corporate_account_id" event="change" data-fv-icon="false" class="select2 form-control ml0 data-search">
                <option value=''>All Employee</option>
                @foreach($corporateAccounts as $corporateAccount)
                <option @if($corporate_account==$corporateAccount->id){{'selected'}}@endif value='{{$corporateAccount->id}}'>{{$corporateAccount->account_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 col-xs-12">
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
                        <th width="30%" data="1">Name</th>
                        <th width="15%" data="2">Mobile</th>
                        <th width="17%" data="3">Email</th>
                        <th width="10%" data="4">Email Verified</th>
                        <th width="10%" data="5">Contact Person</th>
                        <th width="15%" data="6">Last Update</th>
                        <th width="8%" class="tac">Action</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Email Verified</th>
                        <th>Contact Person</th>
                        <th>Last Update</th>
                        <th class="tac">Action</th>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $enCorporateAccounts; ?>
                @if(count($enCorporateAccounts)>0)
                    @foreach($enCorporateAccounts as $enCorporateAccount)
                        <tr>
                            <td>{{$sn++}}</td>
                            <td>{{$enCorporateAccount->account_name}}</td>
                            <td>{{$enCorporateAccount->mobile}}</td>
                            <?php
                                $updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $enCorporateAccount->updated_at);
                                $updated_at = $updatedAt->format('d/m/Y g:i A');
                            ?>
                            <td>{{$enCorporateAccount->email}}</td>
                            <td class="tac">@if($enCorporateAccount->email_verified==1){{'Yes'}}@else{{'No'}}@endif</td>
                            <td>@if($enCorporateAccount->contact_person_status==1){{'Yes'}}@else{{'No'}}@endif</td>
                            <td>{{$updated_at}}</td>
                            <td class="tac">
                            <button url="accountAccessWithdraw?user_id={{$enCorporateAccount->user_id}}" view-type="modal" modal-size="medium" data="{{$enCorporateAccount->id}}" class="add-btn btn btn-danger btn-xs mt5" type="button">Access Withdraw</button>
                            </td>
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
