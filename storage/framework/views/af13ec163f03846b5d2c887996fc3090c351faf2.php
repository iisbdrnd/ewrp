<?php $panelTitle = "Create Gallery"; ?>
<?php echo $__env->make("panelStart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

   /* input, .export {
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
        }




</style>
<form type="create" panelTitle="<?php echo e($panelTitle); ?>" class="form-load form-horizontal group-border stripped" id="specialCourseForm" data-fv-excluded="">
   <?php echo e(csrf_field()); ?>    
    <div class="form-group">
        <label for="gallery_name" class="col-lg-2 col-md-3 control-label required">Gallery Name</label>
        <div class="col-lg-10 col-md-9">
            <input id="gallery_name" name="gallery_name" class="form-control" required reqwidth="0"
            reqheight="0">
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-lg-2 col-md-3 control-label">Gallery Details</label>
        <div class="col-lg-10 col-md-9">
            <textarea name="description" id="description" class="form-control summernote" cols="3"></textarea>
        </div>
    </div>


     

    
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit"  class="btn btn-success ml15">Create</button>
        </div>
    </div>
</form>
<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    // $(".summernote").summernote({
    //         height: 200
    //     });

    $(function() {
        $('.image-editor').cropit({
            imageState: {
                src: 'http://lorempixel.com/500/400/',
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

        $("#specialCourseForm").submit(function( event ) {
            if($("#gallery_name").val()!="" && $("#gallery_name").val()!="" && $("#gallery_thumb").val()!=""){
                 $(".cropit-preview-image").attr('src', '');
            }
        });
        // 'cropit-preview-image'
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/gallery/create.blade.php ENDPATH**/ ?>