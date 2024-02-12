<?php $panelTitle = "Mobilization Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Job Category Name</label>
        <div class="col-lg-9 col-md-8">
            <input autofocus required name="job_category_name" placeholder="e.g.Laborer" class="form-control" value="{{$jobCategory->job_category_name}}">
        </div>
    </div>
</form>