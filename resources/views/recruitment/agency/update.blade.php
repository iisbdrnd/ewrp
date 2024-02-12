<?php $panelTitle = "Agency Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Agency Name</label>
        <div class="col-lg-9 col-md-8">
            <input autofocus required name="agency_name" placeholder="Agency Name" class="form-control" value="{{$ewAgency->agency_name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Recruiting Licence No.</label>
        <div class="col-lg-9 col-md-8">
            <input required name="recruiting_licence_no" value="{{$ewAgency->recruiting_licence_no}}" placeholder="Licence No" class="form-control">
        </div>
    </div>
</form>