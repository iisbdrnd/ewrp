<?php $panelTitle = "Create Social Link"; ?>
@include("panelStart")
<form id="socialLinkForm" type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    @csrf
    <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-xs-12">
            <label class="col-lg-3 col-md-3 control-label required">Social Link</label>
            <div class="col-lg-6 col-md-6">
                <input required name="social_link" class="form-control">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-xs-12">
            <label class="col-lg-3 col-md-3 control-label required">Social Icon</label>
            <div class="col-lg-6 col-md-6">
                <select required id="fa_icon" name="fa_icon" class="select2 form-control ml0">
                    <option value=""></option>
                    <option value="fa fa-facebook-square">facebook</option>
                    <option value="fa fa-twitter-square">twitter</option>
                    <option value="fa fa-flickr">flickr</option>
                    <option value="fa fa-vimeo">vimeo</option>
                    <option value="fa fa-imdb">imdb</option>
                    <option value="fa fa-instagram-square">instagram</i></option>
                    <option value="fa fa-linkedin">linkedin</i></option>
                    <option value="fa fa-youtube">youtube</i></option>
                    
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-xs-12">
            <label class="col-lg-3 col-md-3 control-label required">Social Logo</label>
            <div class="col-lg-3 col-md-3">
                <div class="file-upload" input="social_logo" filepath="public/uploads/social_logo" ext="jpg,jpeg,png,gif" resizwidth="80" resizheight="80"></div>
                {{-- reqwidth="300" reqheight="200" --}}
                <div class="pt5 s10">[Size: 300x200]</div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-3">
            <button type="submit" class="btn btn-success ml15">Create</button>
        </div>
    </div>
</form>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2({placeholder: "Select"});
    })
</script>

