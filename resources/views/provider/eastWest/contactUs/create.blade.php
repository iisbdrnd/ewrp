<?php $panelTitle = "Contact Us Create"; ?>
@include("panelStart")
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Head Office Phone</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="head_office_phone" placeholder="Phone Number" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Head Office Email</label>
        <div class="col-lg-8 col-md-6">
            <input type="text" autofocus required name="head_office_email" placeholder="Email" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Head Office Address</label>
        <div class="col-lg-8 col-md-6">
            <textarea required id="head_office_address" name="head_office_address" rows="5" class="form-control"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Training Center Phone</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="training_center_phone" placeholder="Phone Number" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Training Center Email</label>
        <div class="col-lg-8 col-md-6">
            <input type="text" autofocus required name="training_center_email" placeholder="Email" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Training Center Address</label>
        <div class="col-lg-8 col-md-6">
            <textarea required id="training_center_address" name="training_center_address" rows="5" class="form-control"></textarea>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create</button>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {    
        $("#head_office_address").summernote({
            height: 150
        });
        $("#training_center_address").summernote({
            height: 150
        });
    });
</script>

@include("panelEnd")