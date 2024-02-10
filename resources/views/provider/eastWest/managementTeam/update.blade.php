<?php $panelTitle = "Update"; ?>
<style>
    .vew_scrl{
      width: 257px;
      margin-top: 10px;
    }
    .cropit-preview {
      background-color: #f8f8f8;
      background-size: cover;
      border: 1px solid #ccc;
      border-radius: 3px;
      margin-top: 7px;
      width: 257px;
      height: 293px;
    }
    .cropit-preview-image-container {
      cursor: move;
    }
    .image-size-label {
      margin-top: 10px;
    }
    .export {
      display: block;
      cursor: pointer;
    }
    .button_style {
        /*background-color: #f1f1f1;*/
        color: black;
        text-decoration: none;
        display: inline-block;
        padding: 0px 10px;
        font-size: 18px;
        border: 2px solid #A97C50;
        color: #A97C50;
        /*margin-left: 20px;*/
    }
    .button_style:hover {
        background-color: #ddd;
        color: black;
    }

</style>

<form type="update" id="managementTeamForm" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="name" placeholder="Name" value="{{$managementTeam->name}}" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Designation</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="designation" placeholder="Designation" value="{{$managementTeam->designation}}" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Address</label>
        <div class="col-lg-8 col-md-6">
            <textarea required id="address" name="address" rows="3" class="form-control">{{$managementTeam->address}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Phone</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="phone" placeholder="01xxxxxxxxx" value="{{$managementTeam->phone}}" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Email</label>
        <div class="col-lg-8 col-md-6">
            <input type="email" autofocus name="email" placeholder="Email" value="{{$managementTeam->email}}" class="form-control">
        </div>
    </div>

    <?php
        $path ='public/uploads/managementTeam/thumb/'.$managementTeam->thumbnail;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    ?>

    <div class="form-group col-lg-12 col-md-12 col-xs-12">
        <label class="col-lg-2 col-md-3 control-label required">Image</label>
        <div class="image-editor col-lg-3 col-md-3">
            <input type="file" class="cropit-image-input">
            <div class="cropit-preview"></div>
            <div class="image-size-label">
                Resize image (minimum size 257 x 293)
            </div>
            <div class="vew_scrl">
                <input type="range" class="cropit-image-zoom-input">
            </div>
            <span class="export button_style"><i class="fa fa-crop">Crop</i></span>
            <span id="ready_msg"></span>
            <input class="form-control"  type="hidden" id="thumbnail" name="thumbnail">
            <input class="form-control"  type="hidden" id="real_image" name="real_image" value="{{$base64}}">
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
        $('.image-editor').cropit({
            imageState: {
                src: "{{url('public/uploads/managementTeam/'.$managementTeam->image)}}",
            },
        });
        $(".image-editor").change(function(){
            $('#managementTeamForm #real_image').val('');
            $('#managementTeamForm #thumbnail').val('');
            $('#specialCourseForm #ready_msg').text('Please crop for save');
        });
        $('.rotate-cw').click(function() {
            $('.image-editor').cropit('rotateCW');
        });
        $('.rotate-ccw').click(function() {
            $('.image-editor').cropit('rotateCCW');
        });
        $('.export').click(function() {
            var imageData = $('.image-editor').cropit('export');
            var real_image=$(".cropit-preview-image").attr('src');
            
            $('#managementTeamForm #real_image').val(real_image);
            $('#managementTeamForm #thumbnail').val(imageData);
            console.log($("#thumbnail").val()!="");
            if($("#thumbnail").val()!=""){
                $('#managementTeamForm #ready_msg').text('Ready for save');
            }else{
                $('#managementTeamForm #ready_msg').text('Please crop for save');
            }
            // if (typeof imageData === "undefined") {
            //    $(':input[type="submit"]').prop('disabled', true);
            //  }else{
            //  $('#managementTeamForm #thumbnail').val(imageData);
            //  $(':input[type="submit"]').prop('disabled', false);
            //console.log(imageData);
            // }
            //  if($("#thumbnail").val()!=""){
            //   $('#specialCourseForm #ready_msg').text('Ready for save');
            // }else{
            //   $('#specialCourseForm #ready_msg').text('Please crop for save');
            // }
        });
        $("#managementTeamForm").submit(function( event ) {
            if($("#thumbnail").val()!=""){
                 $(".cropit-preview-image").attr('src', '');
            }
        });
    });
</script>