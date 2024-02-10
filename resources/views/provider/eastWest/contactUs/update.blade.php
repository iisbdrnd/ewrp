<?php $panelTitle = "Contact Us Update"; ?>
@include("panelStart")

<form type="update" action="{{route('provider.eastWest.provider.eastWest.contactUs.update', [$contactUs->id])}}" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Head Office Phone</label>
        <div class="col-lg-8 col-md-6">
            <input value="{{$contactUs->head_office_phone}}" type="text" required name="head_office_phone" placeholder="Phone Number" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Contact Email</label>
        <div class="col-lg-8 col-md-6">
            <input value="{{$contactUs->head_office_email}}" type="email"  required name="head_office_email" placeholder="Email" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Head Office Address</label>
        <div class="col-lg-8 col-md-6">
            <textarea required id="head_office_address" name="head_office_address" rows="5" class="form-control">{{$contactUs->head_office_address}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Training Center Phone</label>
        <div class="col-lg-8 col-md-6">
            <input value="{{$contactUs->training_center_phone}}" type="text" required name="training_center_phone" placeholder="Phone Number" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Training Center Email</label>
        <div class="col-lg-8 col-md-6">
            <input value="{{$contactUs->training_center_email}}" type="text" required name="training_center_email" placeholder="Email" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Training Center Address</label>
        <div class="col-lg-8 col-md-6">
            <textarea required id="training_center_address" name="training_center_address" rows="5" class="form-control">{{$contactUs->training_center_address}}</textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
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