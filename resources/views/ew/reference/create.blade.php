<?php $panelTitle = "Reference Create"; ?>
@include("panelStart")
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label required">Reference Name</label>
        <div class="col-lg-8 col-md-8">
            <input autofocus required name="reference_name" placeholder="Reference Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label required">Reference Phone</label>
        <div class="col-lg-8 col-md-8">
            <input required id="reference_phone" name="reference_phone"  placeholder="e.g.016XXXXXXXX" class="form-control" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s]+$"  data-fv-regexp-message="Reference phone can consist of number only">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label">Reference Email</label>
        <div class="col-lg-8 col-md-8">
            <input type="email" autofocus name="reference_email" placeholder="Reference Email" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 col-md-3 control-label">Reference Address</label>
        <div class="col-lg-8 col-md-8">
            <textarea autofocus name="reference_address" placeholder="Reference Address" class="form-control"></textarea>
        </div>
    </div>
</form>
@include("panelEnd")