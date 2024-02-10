<?php $panelTitle = "Notice Create"; ?>
@include("panelStart")

<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="title" placeholder="Title" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Category</label>
        <div class="col-lg-8 col-md-6">
            <select required name="notice_board_category_id" class="select2 form-control ml0">
                <option value="">Select</option>
                @foreach($noticeBoardCategories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Notice From</label>
        <div class="col-lg-8 col-md-6">
            <select required name="notice_type" id="notice_type" class="select2 form-control ml0">
                <option value="">Select</option>
                <option value="1">Internal</option>
                <option value="2">External</option>
            </select>
        </div>
    </div>
    
    <div class="form-group" id="external_link" style="display: none">
        <label class="col-lg-2 col-md-3 control-label required">External Link</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="external_link" placeholder="External Link" class="form-control">
        </div>
    </div>
    <div class=form-group id="uploadData" style="display: none">
        <label class="col-lg-2 col-md-3 control-label">File</label>
        <div class="col-lg-8 col-md-6 row" id="file_attachment">
            <div class="col-lg-10 col-md-9">
                <button id="file_attachment_upload" input-prefix="fau" file-path="public/uploads/notice_attachments" _token="{{ csrf_token() }}" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Attach Files </button>
                <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
            </div>
            <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                <div id="attachment_area_file_attachment_upload" class="attachment_area"></div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create</button>
        </div>
    </div>
</form>

<script>
    multipleFileUpload("file_attachment_upload", "fl");
     
    $(document).ready(function() { 
        $(".select2").select2({
            placeholder: "Select"
        });   
        $("#notice_type").on('change', function () {
            if ($("#notice_type").val() == 2) {
                $('#external_link').show();
                $('#uploadData').hide();
            } else {
                $('#external_link').hide();
                $('#uploadData').show();
            }
        });
    });
</script>

@include("panelEnd")