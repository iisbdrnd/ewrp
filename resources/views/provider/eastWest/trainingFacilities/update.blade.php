<?php $panelTitle = "Training Facility Update"; ?>

<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="title" placeholder="Title" class="form-control" value="{{$trainingFacility->title}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Value</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="description" placeholder="Value" class="form-control" value="{{$trainingFacility->description}}">
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
        $("#description").summernote({
            height: 150
        });
    });
</script>