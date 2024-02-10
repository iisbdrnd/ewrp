<?php $panelTitle = "Account Access Withdraw"; ?>
<form type="create" callback="accountAccessWithdraw" action="{{route('provider.admin.accountAccessWithdrawAction')}}" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}
    <div class=form-group>
        <input type="hidden" name="user_id" value="{{$userInfo->id}}">
        <label class="col-lg-12 col-md-12 control-label">This account is a corporate @if($userInfo->contact_person_status==1) master @else general @endif user ( {{$userInfo->email}} )</label>
    </div>
    @if($userInfo->contact_person_status==1)
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Email</label>
        <div class="col-lg-10 col-md-9">
            <input autofocus required name="email" placeholder="example@gmail.com" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-10 col-md-9">
            <input autofocus required name="name" placeholder="Mr.Jonson" class="form-control">
        </div>
    </div>
    @endif
</form>
<script type="text/javascript">
    function accountAccessWithdraw(data) {
        bootbox.hideAll();
    }
</script>
