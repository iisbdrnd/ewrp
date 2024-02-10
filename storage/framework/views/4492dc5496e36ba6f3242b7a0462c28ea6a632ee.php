<?php $panelTitle = "Corporate Certificate Configuration"; ?>
<style type="text/css">
    .caption {
        position: absolute;
        top: 0;
        left: 5px;                            /*  changed to match image_grid padding  */
        height: 92%;
        width: calc(100% - 5px);              /*  changed to match image_grid padding  */
        padding: 0 10px;
        box-sizing: border-box;
        pointer-events: none;
    }

    .imageandtext {
        position: relative;
    }
    .image_grid {
        display: inline-block;
        padding-left: 5px;
    }
    .image_grid img {                       /*  added rule  */
        display: block;
    }

    .image_grid input {
        display: none;
    }
    .image_grid input:checked + .caption {
        background: rgba(0,0,0,0.5);
    }
    .image_grid input:checked + .caption::after {
        content: '✔';
        position: absolute;
        top: 50%; left: 50%;
        width: 30px; height: 30px;
        transform: translate(-50%,-50%);
        color: white;
        font-size: 20px;
        text-align: center;
        border: 1px solid white;
        border-radius: 50%;
    }

    #certificateConfigurationForm .temp{
        border:1px solid #ccc;
        padding:8px;
    }

    .single-item{
        width: 60px!important;
    }
    .single-item .img img {
        width: 50px!important;
        height: 50px!important;
    }
    .upload-img-overly h1, .upload-img-overly h2, .upload-img-overly h3.h4{
        margin-right: -17px!important;
        margin-top: -19px!important;
    }
    .file-remove{
        font-size: 35px!important;
    }
    .instructions li{
        text-transform: uppercase;
    }
    .instructions_default li{
        text-transform: uppercase;
    }

</style>
<?php echo $__env->make("panelStart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="row mt15">
    <div class="col-lg-12 col-md-12 col-xs-12">
       
            <?php echo e(csrf_field()); ?>

            <div class=form-group>
                <label class="col-lg-2 col-md-2 control-label"></label>
                <div class="col-lg-8 col-md-7 pl0" id="file_attachment">
                    <div class="col-lg-10 col-md-9">
                    
                        <button id="file_attachment_upload" input-prefix="image" file-attach-direction="last" callback="photoGalleryList" remove-callback="down_images" file-path="public/uploads/gallery" height="100" width="100" _token="<?php echo e(csrf_token()); ?>" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info" multiple="true" stroke-url="galleryImageStore" id-parameter="<?php echo e($gallery->id); ?>"> Image Upload</button>

                        <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
                        
                    </div>
                    <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                        <div id="attachment_area_file_attachment_upload" class="attachment_area"></div>
                    </div>

                    
                </div>
            </div>
      
    </div>
</div>
<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        //FOR MULTIPLE SELECTOR
        galleryImageUpload("file_attachment_upload", "fl");
        photoGalleryList();
        $('#backToList').click(function() {
            $('#data-list-view #galleryDiv').css("display","none");
        })
    });
    function photoGalleryList(data) {
        $('#inviters_email').tagsinput('removeAll');
        galleryImagesView('<?php echo e($gallery->id); ?>');
    }

    function galleryImagesView(gallery_id) {
        var loadUrl = "galleryImageListData?gallery_id="+gallery_id;


        $('#data-list-view').html($('#data-list-view-clone').html());
        $('#data-list-view').find('.panel-title').html('Gallery Images');
        $('#data-list-view').find('#dataListView').attr("load-url", loadUrl).attr("delete-link", "galleryImage");
        $(".dataList-data-input").remove();
        loadDataTable($('#data-list-view').find('#dataListView'), "galleryImageListData?gallery_id="+gallery_id, false);
    }

    function galleryListRefresh(data) {
        if(data.msgType=='success') {
            $("#dataListView").find(".panel-refresh").trigger("click");
            $("#specialCourseForm").find(".attachment-item").remove();
        }
    }



</script>
<?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/galleryimage/list.blade.php ENDPATH**/ ?>