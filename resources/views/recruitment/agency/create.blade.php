<?php $panelTitle = "Agency Create"; ?>
@include("panelStart")
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Agency Name</label>
        <div class="col-lg-9 col-md-8">
            <input required name="agency_name" placeholder="Agency Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Recruiting Licence No.</label>
        <div class="col-lg-9 col-md-8">
            <input required name="recruiting_licence_no" placeholder="Licence No" class="form-control">
        </div>
    </div>
</form>
@include("panelEnd")