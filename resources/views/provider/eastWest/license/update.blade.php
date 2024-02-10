<?php $panelTitle = "Compliance Update"; ?>

<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="title" placeholder="Title" value="{{$license->title}}" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Description</label>
        <div class="col-lg-8 col-md-6">
            <textarea id="description" name="description" rows="5" class="form-control"> {{$license->description}} </textarea>
        </div>
    </div>
    <div class=form-group>
        <label class="col-lg-2 col-md-3 control-label">File</label>
        <div class="col-lg-8 col-md-7 row" id="file_attachment">
            <div class="col-lg-10 col-md-9">
                <button id="file_attachment_upload" input-prefix="fau" file-path="public/uploads/license_attachments" _token="{{ csrf_token() }}" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Attach Files </button>
                <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
            </div>
            <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                <div id="attachment_area_file_attachment_upload" class="attachment_area">
                    {{-- @foreach($jobAttFiles as $mdlFile) --}}
                    <div class="attachment-item clearfix image">
                        <input name="fau_attachment_id[]" value="{{$license->id}}" type="hidden"/>
                        @if(!empty($license->attachment_name))
                        <div class="attachment-img" style="background-image: url({{Helper::getFileThumb($license->attachment_name, '')}})"></div>
                        @endif
                        <div class="attachment-content">
                            <div class="close_x"><span class="fa fa-close remove_files" file_name="{{$license->attachment_name}}" filePath="public/uploads/license_attachments" auto-remove="true"></span></div>
                            <div class="attachment-title">
                                <a class="igniterImg" href="{{url('public/uploads/license_attachments/'.$license->attachment_name)}}" target="_blank">{{$license->attachment_real_name}}</a>
                            </div>
                            <?php
                                $d=strtotime($license->created_at);
                                $uploaded_at = "Uploaded ".date("M d, Y", $d);
                            ?>
                            <div class="attachment-date">{{$uploaded_at}}</div>
                            <div class="attachment-size"></div>
                            <input name="fau_attachment[]" value="{{$license->attachment}}" type="hidden">
                            <input name="fau_attachment_real_name[]" value="{{$license->attachment_real_name}}" type="hidden">
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
        $("#description").summernote({
            height: 150
        });
        $("#contact_us").summernote({
            height: 100
        });
    });
</script>