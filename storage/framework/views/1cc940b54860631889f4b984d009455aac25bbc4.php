<?php $panelTitle = "News Event Update"; ?>

<form type="update" panelTitle="<?php echo e($panelTitle); ?>" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="title" placeholder="Title" value="<?php echo e($newsEvent->title); ?>" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Notice From</label>
        <div class="col-lg-8 col-md-6">
            <select required name="news_event_type" id="news_event_type" class="select2 form-control ml0">
                <option value="">Select</option>
                <option value="1" <?php echo e($newsEvent->news_event_type == 1 ? 'selected' : ''); ?> >Internal</option>
                <option value="2" <?php echo e($newsEvent->news_event_type == 2 ? 'selected' : ''); ?> >External</option>
            </select>
        </div>
    </div>
    <div class="form-group" id="external_link" style="display: <?php echo e($newsEvent->news_event_type == 2 ? 'block': 'none'); ?>;">
        <label class="col-lg-2 col-md-3 control-label required">External Link</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="external_link" value="<?php echo e($newsEvent->external_link); ?>" placeholder="External Link" class="form-control">
        </div>
    </div>
    <div class=form-group id="uploadData" style="display: <?php echo e($newsEvent->news_event_type == 1 ? 'block': 'none'); ?>;">
        <label class="col-lg-2 col-md-3 control-label">File</label>
        <div class="col-lg-8 col-md-7 row" id="file_attachment">
            <div class="col-lg-10 col-md-9">
                <button id="file_attachment_upload" input-prefix="fau" file-path="public/uploads/news_event_attachments" _token="<?php echo e(csrf_token()); ?>" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Attach Files </button>
                <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
            </div>
            <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                <div id="attachment_area_file_attachment_upload" class="attachment_area">
                    
                    <div class="attachment-item clearfix image">
                        <input name="fau_attachment_id[]" value="<?php echo e($newsEvent->id); ?>" type="hidden"/>
                        <?php if(!empty($newsEvent->attachment_name)): ?>
                        <div class="attachment-img" style="background-image: url(<?php echo e(Helper::getFileThumb($newsEvent->attachment_name, '')); ?>)"></div>
                        <?php endif; ?>
                        <div class="attachment-content">
                            <div class="close_x"><span class="fa fa-close remove_files" file_name="<?php echo e($newsEvent->attachment_name); ?>" filePath="public/uploads/news_event_attachments" auto-remove="true"></span></div>
                            <div class="attachment-title">
                                <a class="igniterImg" href="<?php echo e(url('public/uploads/news_event_attachments/'.$newsEvent->attachment_name)); ?>" target="_blank"><?php echo e($newsEvent->attachment_real_name); ?></a>
                            </div>
                            <?php
                                $d=strtotime($newsEvent->created_at);
                                $uploaded_at = "Uploaded ".date("M d, Y", $d);
                            ?>
                            <div class="attachment-date"><?php echo e($uploaded_at); ?></div>
                            <div class="attachment-size"></div>
                            <input name="fau_attachment[]" value="<?php echo e($newsEvent->attachment); ?>" type="hidden">
                            <input name="fau_attachment_real_name[]" value="<?php echo e($newsEvent->attachment_real_name); ?>" type="hidden">
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
        $("#news_event_type").on('change', function () {
            if ($("#news_event_type").val() == 2) {
                $('#external_link').show();
                $('#uploadData').hide();
            } else {
                $('#external_link').hide();
                $('#uploadData').show();
            }
        });
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/newsEvent/update.blade.php ENDPATH**/ ?>