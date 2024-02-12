<?php $panelTitle = "Collectable Account Head Create"; ?>
@include("panelStart")
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-4 col-md-5 control-label required">Account Head Name</label>
        <div class="col-lg-8 col-md-7">
            <input autofocus required name="account_head" placeholder="Account Head Name" class="form-control">
        </div>
    </div>
</form>
@include("panelEnd")