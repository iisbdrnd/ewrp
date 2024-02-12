<?php $panelTitle = "Area Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Area Name</label>
        <div class="col-lg-10 col-md-9">
            <input autofocus required name="area_name" placeholder="Area Name" class="form-control" value="{{$jobArea->area_name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Area Details</label>
        <div class="col-lg-10 col-md-9">
            <textarea autofocus required name="area_details" class="form-control" rows=3>{{$jobArea->area_details}}</textarea>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update Area</button>
        </div>
    </div>
</form>