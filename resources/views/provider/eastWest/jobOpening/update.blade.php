<?php $panelTitle = "Job Opening Update"; ?>

<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Company Name</label>
        <div class="col-lg-8 col-md-6">
            <input required name="company_name" placeholder="Company Name" class="form-control" value="{{$jobOpening->company_name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Country</label>
        <div class="col-lg-8 col-md-9">
            <select required name="country_id" class="select2 form-control ml0">
                <option value="">Select</option>
                @foreach($countries as $country)
                <option value="{{$country->id}}" {{$jobOpening->country_id == $country->id ? 'selected' : ''}}>{{$country->country}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Trade Name</label>
        <div class="col-lg-8 col-md-9">
            <select required name="job_category_id" class="select2 form-control ml0">
                <option value="">Select</option>
                @foreach($jobCategories as $category)
                <option value="{{$category->id}}" {{$jobOpening->job_category_id == $category->id ? 'selected' : ''}}>{{$category->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Quantity</label>
        <div class="col-lg-8 col-md-6">
            <input name="quantity" placeholder="Quantity" class="form-control" value="{{$jobOpening->quantity}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Salary</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="salary" placeholder="10000" class="form-control" value="{{$jobOpening->salary}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Food</label>
        <div class="col-lg-8 col-md-9">
            <input name="food_status" value="{{$jobOpening->food_status}}" placeholder="Food Status" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Accommodation</label>
        <div class="col-lg-8 col-md-9">
            <input name="accommodation_status" value="{{$jobOpening->accommodation_status}}" placeholder="Accommodation Status" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required ">Age</label>
        <div class="col-lg-8 col-md-6">
            <input required value="{{$jobOpening->age}}" name="age" placeholder="Age" class="form-control">
        </div>
    </div>

    {{-- <div class=form-group>
        <label class="col-lg-2 col-md-3 control-label">File</label>
        <div class="col-lg-8 col-md-7 row" id="file_attachment">
            <div class="col-lg-10 col-md-9">
                <button id="file_attachment_upload" input-prefix="fau" file-path="public/uploads/job_opening_attachments" _token="{{ csrf_token() }}" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Attach Files </button>
                <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
            </div>
            <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                <div id="attachment_area_file_attachment_upload" class="attachment_area">
                    <div class="attachment-item clearfix image">
                        <input name="fau_attachment_id[]" value="{{$jobOpening->id}}" type="hidden"/>
                        @if(!empty($jobOpening->attachment_name))
                        <div class="attachment-img" style="background-image: url({{Helper::getFileThumb($jobOpening->attachment_name, '')}})"></div>
                        @endif
                        <div class="attachment-content">
                            <div class="close_x"><span class="fa fa-close remove_files" file_name="{{$jobOpening->attachment_name}}" filePath="public/uploads/job_opening_attachments" auto-remove="true"></span></div>
                            <div class="attachment-title">
                                <a class="igniterImg" href="{{url('public/uploads/job_opening_attachments/'.$jobOpening->attachment_name)}}" target="_blank">{{$jobOpening->attachment_real_name}}</a>
                            </div>
                            <?php
                                $d=strtotime($jobOpening->created_at);
                                $uploaded_at = "Uploaded ".date("M d, Y", $d);
                            ?>
                            <div class="attachment-date">{{$uploaded_at}}</div>
                            <div class="attachment-size"></div>
                            <input name="fau_attachment[]" value="{{$jobOpening->attachment}}" type="hidden">
                            <input name="fau_attachment_real_name[]" value="{{$jobOpening->attachment_real_name}}" type="hidden">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">Publish Status</label>
        <div class="col-lg-8 col-md-9">
            <select required class="select2 form-control ml0" name="publish_status">
                <option value="1" {{$jobOpening->publish_status == 1 ? 'selected' : ''}}>Publish</option>
                <option value="0" {{$jobOpening->publish_status == 0 ? 'selected' : ''}}>Unpublish</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-2 control-label required">File Upload Type</label>
        <div class="col-lg-8 col-md-9">
            <select required id="archiveOrNot" class="select2 form-control ml0">
                <option value="1">Archive</option>
                <option value="0">Upload</option>
            </select>
        </div>
    </div>
    <div class="form-group" id="archiveData">
        <label class="col-lg-2 col-md-2 control-label required">Attachment Name</label>
        <div class="col-lg-8 col-md-9">
            <select required name="attachment_id" id="attachment_id" class="select2 form-control ml0">
                <option value="">Select</option>
                @foreach($attachments as $attachment)
                <option value="{{$attachment->id}}"{{$attachment->attachment_name == $jobOpening->attachment_name ? 'selected' : ''}}>{{$attachment->attachment_real_name}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class=form-group id="uploadData" style="display: none;">
        <label class="col-lg-2 col-md-3 control-label">File</label>
        <div class="col-lg-6 col-md-5 row" id="file_attachment">
            <div class="col-lg-10 col-md-9">
                <button id="file_attachment_upload" input-prefix="fau" file-path="public/uploads/job_opening_attachments" _token="{{ csrf_token() }}" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Attach Files </button>
                <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
            </div>
            <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                <div id="attachment_area_file_attachment_upload" class="attachment_area"></div>
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
        $("#job_description").summernote({
            height: 100
        });
        $("#archiveOrNot").on('change', function () {
            if ($("#archiveOrNot").val() == 1) {
                $('#archiveData').show();
                $('#uploadData').hide();
            } else {
                $('#archiveData').hide();
                $('#attachment_id').val('');
                $('#uploadData').show();
            }
        });
    });
</script>