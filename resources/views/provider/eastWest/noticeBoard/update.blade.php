<?php $panelTitle = "Notice Update"; ?>

<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="title" placeholder="Title" value="{{$notice->title}}" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Category</label>
        <div class="col-lg-8 col-md-6">
            <select required name="notice_board_category_id" class="select2 form-control ml0">
                <option value="">Select</option>
                @foreach($noticeBoardCategories as $category)
                <option value="{{$category->id}}" {{$category->id == $notice->notice_board_category_id ? 'selected' : ''}}>{{$category->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Notice From</label>
        <div class="col-lg-8 col-md-6">
            <select required name="notice_type" id="notice_type" class="select2 form-control ml0">
                <option value="">Select</option>
                <option value="1" {{$notice->notice_type == 1 ? 'selected' : ''}} >Internal</option>
                <option value="2" {{$notice->notice_type == 2 ? 'selected' : ''}} >External</option>
            </select>
        </div>
    </div>
    <div class="form-group" id="external_link" style="display: {{$notice->notice_type == 2 ? 'block': 'none'}};">
        <label class="col-lg-2 col-md-3 control-label required">External Link</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="external_link" value="{{$notice->external_link}}" placeholder="External Link" class="form-control">
        </div>
    </div>
    <div class=form-group id="uploadData" style="display: {{$notice->notice_type == 1 ? 'block': 'none'}};">
        <label class="col-lg-2 col-md-3 control-label">File</label>
        <div class="col-lg-8 col-md-7 row" id="file_attachment">
            <div class="col-lg-10 col-md-9">
                <button id="file_attachment_upload" input-prefix="fau" file-path="public/uploads/notice_attachments" _token="{{ csrf_token() }}" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Attach Files </button>
                <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
            </div>
            <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                <div id="attachment_area_file_attachment_upload" class="attachment_area">
                    {{-- @foreach($jobAttFiles as $mdlFile) --}}
                    <div class="attachment-item clearfix image">
                        <input name="fau_attachment_id[]" value="{{$notice->id}}" type="hidden"/>
                        @if(!empty($notice->attachment_name))
                        <div class="attachment-img" style="background-image: url({{Helper::getFileThumb($notice->attachment_name, '')}})"></div>
                        @endif
                        <div class="attachment-content">
                            <div class="close_x"><span class="fa fa-close remove_files" file_name="{{$notice->attachment_name}}" filePath="public/uploads/notice_attachments" auto-remove="true"></span></div>
                            <div class="attachment-title">
                                <a class="igniterImg" href="{{url('public/uploads/notice_attachments/'.$notice->attachment_name)}}" target="_blank">{{$notice->attachment_real_name}}</a>
                            </div>
                            <?php
                                $d=strtotime($notice->created_at);
                                $uploaded_at = "Uploaded ".date("M d, Y", $d);
                            ?>
                            <div class="attachment-date">{{$uploaded_at}}</div>
                            <div class="attachment-size"></div>
                            <input name="fau_attachment[]" value="{{$notice->attachment_name}}" type="hidden">
                            <input name="fau_attachment_real_name[]" value="{{$notice->attachment_real_name}}" type="hidden">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
</form>

<script>
    multipleFileUpload("file_attachment_upload");
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