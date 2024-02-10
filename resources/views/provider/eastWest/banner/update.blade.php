<?php $panelTitle = "Carrier Update"; ?>
@include("panelStart")

<form type="update" panelTitle="{{$panelTitle}}" action="{{route('provider.eastWest.provider.eastWest.banner.update', [$banner->id])}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="title" value="{{$banner->title}}" placeholder="Title" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Mini Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="mini_title" value="{{$banner->mini_title}}" placeholder="Mini Title" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Button Text</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="btn_text" value="{{$banner->btn_text}}" placeholder="Button Text" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Button Link</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="btn_link" value="{{$banner->btn_link}}" placeholder="Button Link" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Description</label>
        <div class="col-lg-8 col-md-6">
            <textarea id="description" name="description" rows="5" class="form-control"> {{$banner->description}} </textarea>
        </div>
    </div>
    <div class=form-group>
        <label class="col-lg-2 col-md-3 control-label">Banner</label>
        <div class="col-lg-2 col-md-3">
            <div class="file-upload" input="banner" filepath="public/uploads/banner" prefile="{{$banner->banner}}" reqwidth="1600" reqheight="889" ext="jpg,jpeg,png,gif"></div>
            <div class="pt5 s10">[Size: 1600px * 889px]</div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
</form>

@include("panelEnd")

<script>
    $(document).ready(function() {    
        $("#description").summernote({
            height: 150
        });
        $("#contact_us").summernote({
            height: 100
        });
    });
</script>