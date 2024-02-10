<?php $panelTitle = "Designation Create"; ?>
@include("panelStart")
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Department Name</label>
        <div class="col-lg-10 col-md-9">
            <input autofocus required name="name" placeholder="Designation Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Comment</label>
        <div class="col-lg-10 col-md-9">
            <input autofocus required name="comment" placeholder="Comment" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create Department</button>
        </div>
    </div>

</form>
@include("panelEnd")