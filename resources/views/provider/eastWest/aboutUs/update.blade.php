<?php $panelTitle = "About Us Update"; ?>
<form type="update" action="{{route('provider.eastWest.provider.eastWest.aboutUs.update', [$aboutUs->id])}}" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="title" placeholder="Title" value="{{$aboutUs->title}}" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Description</label>
        <div class="col-lg-8 col-md-6">
            <textarea required id="description" name="description" rows="5" class="form-control">{!! $aboutUs->description !!}</textarea>
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
        $("#description").summernote({
            height: 150
        });
    });
</script>