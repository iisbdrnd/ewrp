<?php $panelTitle = "Collectable Account Head Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-4 col-md-5 control-label required">Account Head Name</label>
        <div class="col-lg-8 col-md-7">
            <input autofocus required name="account_head" placeholder="Account Head Name" class="form-control" value="{{$ewCollectableAccountHeads->account_head}}">
        </div>
    </div>
</form>