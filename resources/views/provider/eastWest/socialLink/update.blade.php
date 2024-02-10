<?php $panelTitle = "Social Link Update"; ?>
@include("panelStart")
<form id="socialLinkForm" type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    @csrf
    <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-xs-12">
            <label class="col-lg-3 col-md-3 control-label required">Social Link</label>
            <div class="col-lg-6 col-md-6">
                <input required name="social_link" class="form-control" value="{{$link->social_link}}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-xs-12">
            <label class="col-lg-3 col-md-3 control-label required">Social Icon</label>
            <div class="col-lg-6 col-md-6">
                <select required id="fa_icon" name="fa_icon" class="select2 form-control ml0">
                    <option value=""></option>
                    <option value="fa fa-facebook-square" @if($link->fa_icon == 'fa fa-facebook-square') selected @endif>facebook</option>
                    <option value="fa fa-twitter-square" @if($link->fa_icon == 'fa fa-twitter-square') selected @endif>twitter</option>
                    <option value="fa fa-flickr" @if($link->fa_icon == 'fa fa-flickr') selected @endif>flickr</option>
                    <option value="fa fa-vimeo" @if($link->fa_icon == 'fa fa-vimeo') selected @endif>vimeo</option>
                    <option value="fa fa-imdb" @if($link->fa_icon == 'fa fa-imdb') selected @endif>imdb</option>
                    
                    <option value="fa fa-instagram" @if($link->fa_icon == 'fa fa-instagram') selected @endif>instagram</option>
                    <option value="fa fa-linkedin" @if($link->fa_icon == 'fa fa-linkedin') selected @endif>linkedin</option>

                     <option value="fa fa-youtube" @if($link->fa_icon == 'fa fa-youtube') selected @endif>youtube</option>

                    

                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-lg-12 col-md-12 col-xs-12">
            <label class="col-lg-3 col-md-3 control-label required">Social Logo</label>
            <div class="col-lg-3 col-md-3">
                <div class="file-upload" input="social_logo" filepath="public/uploads/social_logo" ext="jpg,jpeg,png,gif" resizeheight="80" resizeheight="80" prefile="{{$link->social_logo}}"></div>
                {{-- reqwidth="300" reqheight="200" --}}
                <div class="pt5 s10">[Size: 300x200]</div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-success ml15">Update</button>
        </div>
    </div>
</form>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2").select2({placeholder: "Select"});
    })
</script>

