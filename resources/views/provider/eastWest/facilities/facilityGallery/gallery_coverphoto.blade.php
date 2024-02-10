<?php $panelTitle = "Gallery Coverphoto"; ?>
@include("panelStart")
<style>
.vew_scrl{
    width: 900px;
    margin-top: 10px;
}

    .cropit-preview {
      background-color: #f8f8f8;
      background-size: cover;
      border: 1px solid #ccc;
      border-radius: 3px;
      margin-top: 7px;
      width: 902px;
      height: 331px;
    }

    .cropit-preview-image-container {
      cursor: move;
    }

    .image-size-label {
      margin-top: 10px;
    }

    /*input, .export {
      display: block;
      cursor: pointer;
    }

    button {
      margin-top: 10px;
    }*/

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
          cursor: pointer;
        }

</style>

<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" id="specialCourseForm" data-fv-excluded="">
    {{csrf_field()}}
 
<input type="hidden" name="gallery_id" id="gallery_id" class="form-control" value="{{$gallery['id']}}">

    <div class="form-group">
   
        <div class="image-editor col-lg-4 col-md-4">
            <input type="file" class="cropit-image-input">
            <div class="cropit-preview"></div>

            <div class="image-size-label">
              Resize image
            </div>
            <div class="vew_scrl">
              <input type="range" class="cropit-image-zoom-input">
            </div>
         <!--    <button class="rotate-ccw">Rotate counterclockwise</button>
            <button class="rotate-cw">Rotate clockwise</button>
         -->

      
         @if($gallery['cover_photo']!="")


         <?php

                $path ='public/uploads/gallery_coverphoto/'.$gallery->cover_photo;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

               
          ?> 
            <span class="export button_style"><i class="fa fa-crop">Crop</i></span>
            <span id="ready_msg"></span>
            <input class="form-control"  type="hidden" id="thumbnail" name="thumbnail" value="{{$gallery->cover_photo}}">
            <input class="form-control"  type="hidden" id="cover_photo" name="cover_photo" value={{$base64}}>

            @else

            <span class="export button_style"><i class="fa fa-crop">Crop</i></span>
            <span id="ready_msg"></span>
            <input class="form-control"  type="hidden" id="thumbnail" name="thumbnail" value="">
            <input class="form-control"  type="hidden" id="cover_photo" name="cover_photo" value="">
            @endif
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-lg-offset-3">
            <button type="submit" class="btn btn-success ml15">Create</button>
            <button type="button" class="back-btn btn btn-default ml15">Back to List</button>
        </div>
    </div>
</form>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({placeholder: "Select"});
    });
</script>

<script>
    $(function() {


        $('.image-editor').cropit({
            imageState: {
                src: "{{url('public/uploads/gallery_coverphoto/'.$gallery['cover_photo'])}}",
            },
        });



        $(".image-editor").change(function(){
            $('#specialCourseForm #cover_photo').val('');
            $('#specialCourseForm #cover_photo_real').val('');
            $('#specialCourseForm #ready_msg').text('Please crop for save');
        });


        $('.rotate-cw').click(function() {
            $('.image-editor').cropit('rotateCW');
        });
        $('.rotate-ccw').click(function() {
            $('.image-editor').cropit('rotateCCW');
        });

        $('.export').click(function() {
    
            var imageDataReal = $('.cropit-preview-image').attr('src');
            var imageData = $('.image-editor').cropit('export');
            // var currentdate = new Date(); 
            // var img_name =  currentdate.getDate() + "_"
            //     + (currentdate.getMonth()+1)  + "_" 
            //     + currentdate.getFullYear() + "_"  
            //     + currentdate.getHours() + "_"  
            //     + currentdate.getMinutes() + "_" 
            //     + currentdate.getSeconds() +".jpeg";
              
            //  alert (img_name);   

             if (typeof imageData === "undefined") {
                $(':input[type="submit"]').prop('disabled', true);
            }else{
            // window.open(imageData);
            $('#specialCourseForm #cover_photo').val(imageData);
            $('#specialCourseForm #cover_photo_real').val(imageDataReal);
            $(':input[type="submit"]').prop('disabled', false);
            //console.log(imageData);
            }
            if($("#cover_photo").val()!="" || $("#cover_photo_real").val()!=""){
                $('#specialCourseForm #ready_msg').text('Ready for save');
              }else{
                $('#specialCourseForm #ready_msg').text('Please crop for save');
              }
            
        });
    });
</script>