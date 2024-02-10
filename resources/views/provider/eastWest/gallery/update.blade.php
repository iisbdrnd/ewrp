<style>

 .vew_scrl{
      width: 300px;
      margin-top: 10px;
  }

    .cropit-preview {
      background-color: #f8f8f8;
      background-size: cover;
      border: 1px solid #ccc;
      border-radius: 3px;
      margin-top: 7px;
      width: 300px;
      height: 300px;
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

    /*button {
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
        }




</style>


<form type="update" panelTitle="Session Update" class="form-load form-horizontal group-border stripped" data-fv-excluded="" id="specialCourseForm">
    {{csrf_field()}}
    <div class="form-group">
        <input class="form-control" type="hidden" id="gallery_thumb_previous" name="gallery_thumb_previous" value="{{$gallery->gallery_thumb}}">
        
        
        <label for="gallery_name" class="col-lg-2 col-md-3 control-label required">Gallery Name</label>
        <div class="col-lg-10 col-md-9">
            <input id="gallery_name" required name="gallery_name" class="form-control" value="{{$gallery->gallery_name}}">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-lg-2 col-md-3 control-label required">Gallery Details</label>
        <div class="col-lg-10 col-md-9">
            <textarea name="description" id="description" class="form-control summernote" cols="3">{{$gallery->description}}</textarea>
        </div>
    </div>
    

      {{-- <div class="form-group">
     
        <label class="col-lg-2 col-md-2 control-label required">Gallery Thumb</label>
        <div class="image-editor col-lg-4 col-md-4">
            <input type="file" class="cropit-image-input">
            <div class="cropit-preview">

            </div>
            <div class="image-size-label">
              Resize image
            </div>
            <div class="vew_scrl">
              <input type="range" class="cropit-image-zoom-input">
            </div>

            <span class="export button_style"><i class="fa fa-crop">Crop</i></span>

            <?php
                $path ='public/uploads/ashram/'.$gallery->gallery_thumb;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            ?>
            <span id="ready_msg"></span>
           
            <input class="form-control" type="hidden" id="gallery_thumb" name="gallery_thumb">
        </div>
    </div>--}}



    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-success ml15">Update</button>
        </div>
    </div>
</form>
<script>
    // $(".summernote").summernote({
    //         height: 200
    //     });

    $(function() {

        $('.image-editor').cropit({
            imageState: {
                src: "{{url('public/uploads/ashram/'.$gallery->gallery_thumb)}}",
            },
        });
        
        $(".image-editor").change(function(){
            $('#specialCourseForm #gallery_thumb').val('');
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
             if (typeof imageData === "undefined") {
                $(':input[type="submit"]').prop('disabled', true);
            }else{
            // window.open(imageData);
            $('#specialCourseForm #gallery_thumb').val(imageData);
            $(':input[type="submit"]').prop('disabled', false);
            //console.log(imageData);
            }

             if($("#gallery_thumb").val()!=""){
                $('#specialCourseForm #ready_msg').text('Ready for save');
              }else{
                $('#specialCourseForm #ready_msg').text('Please crop for save');
              }
            
        });
    });
</script>