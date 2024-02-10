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
@include("panelStart")
<div class="row mt15">
    <div class="col-lg-12 col-md-12 col-xs-12">
        
        {{csrf_field()}}
        <div class=form-group>
            <label class="col-lg-2 col-md-2 control-label"></label>
            <div class="col-lg-8 col-md-7 pl0" id="file_attachment">
                <div class="col-lg-10 col-md-9">
                
                    {{-- <button id="file_attachment_upload" input-prefix="image" file-attach-direction="last" callback="photoGalleryList" remove-callback="down_images" file-path="public/uploads/facilityHeadOffice" height="100" width="100" _token="{{ csrf_token() }}" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info" multiple="true" stroke-url="ashramFileUpload" id-parameter="{{$gallery->id}}"> Image Upload</button> --}}

                    <button id="head_office_file_attachment_upload" input-prefix="image" file-attach-direction="last" callback="photoGalleryList" remove-callback="down_images" file-path="public/uploads/facilityHeadOffice" height="100" width="100" _token="{{ csrf_token() }}" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info" multiple="true" stroke-url="facilitiesGalleryImageAction" typeOfImage="facility_head_office" id-parameter="1"> Image Upload</button>



                    <div id="status_head_office_file_attachment_upload" style="padding-top: 10px"> </div>
                    {{-- <div class="row">
                        <button type="button" class="back-btn btn btn-default ml15">Back to List</button>
                    </div> --}}
                </div>
                <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                    <div id="attachment_area_head_office_file_attachment_upload" class="attachment_area"></div>
                </div>
            </div>
        </div>
            
    </div>
</div>
@include("panelEnd")

<script type="text/javascript">

    $(document).ready(function() {
        //FOR MULTIPLE SELECTOR
        galleryImageUpload("head_office_file_attachment_upload", "fl");
        photoGalleryList();
        $('#backToList').click(function() {
            $('#data-list-view #galleryDiv').css("display","none");
        })
    });
    function photoGalleryList(data) {
        $('#inviters_email').tagsinput('removeAll');
        galleryImagesView(1);
    }

    function galleryImagesView(gallery_id) {
        var loadUrl = "facilityGalleryImageListData?gallery_id="+gallery_id;

        $('#data-list-view').html($('#data-list-view-clone').html());
        $('#data-list-view').find('.panel-title').html('Gallery Images');
        $('#data-list-view').find('#dataListView').attr("load-url", loadUrl).attr("delete-link", "facilitiesGalleryImageDestroy");
        $(".dataList-data-input").remove();
        loadDataTable($('#data-list-view').find('#dataListView'), "facilityGalleryImageListData?gallery_id="+gallery_id, false);

    }

    function galleryListRefresh(data) {
        if(data.msgType=='success') {
            $("#dataListView").find(".panel-refresh").trigger("click");
            $("#specialCourseForm").find(".attachment-item").remove();
        }
    }



</script>
