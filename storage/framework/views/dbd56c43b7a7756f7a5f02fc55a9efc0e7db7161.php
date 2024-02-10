<?php $panelTitle = "Compliance Update"; ?>

<form type="update" panelTitle="<?php echo e($panelTitle); ?>" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="title" placeholder="Title" value="<?php echo e($license->title); ?>" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Description</label>
        <div class="col-lg-8 col-md-6">
            <textarea id="description" name="description" rows="5" class="form-control"> <?php echo e($license->description); ?> </textarea>
        </div>
    </div>
    <div class=form-group>
        <label class="col-lg-2 col-md-3 control-label">File</label>
        <div class="col-lg-8 col-md-7 row" id="file_attachment">
            <div class="col-lg-10 col-md-9">
                <button id="file_attachment_upload" input-prefix="fau" file-path="public/uploads/license_attachments" _token="<?php echo e(csrf_token()); ?>" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Attach Files </button>
                <div id="status_file_attachment_upload" style="padding-top: 10px"> </div>
            </div>
            <div class="col-lg-10 col-md-9" style="margin-bottom: -10px;">
                <div id="attachment_area_file_attachment_upload" class="attachment_area">
                    
                    <div class="attachment-item clearfix image">
                        <input name="fau_attachment_id[]" value="<?php echo e($license->id); ?>" type="hidden"/>
                        <?php if(!empty($license->attachment_name)): ?>
                        <div class="attachment-img" style="background-image: url(<?php echo e(Helper::getFileThumb($license->attachment_name, '')); ?>)"></div>
                        <?php endif; ?>
                        <div class="attachment-content">
                            <div class="close_x"><span class="fa fa-close remove_files" file_name="<?php echo e($license->attachment_name); ?>" filePath="public/uploads/license_attachments" auto-remove="true"></span></div>
                            <div class="attachment-title">
                                <a class="igniterImg" href="<?php echo e(url('public/uploads/license_attachments/'.$license->attachment_name)); ?>" target="_blank"><?php echo e($license->attachment_real_name); ?></a>
                            </div>
                            <?php
                                $d=strtotime($license->created_at);
                                $uploaded_at = "Uploaded ".date("M d, Y", $d);
                            ?>
                            <div class="attachment-date"><?php echo e($uploaded_at); ?></div>
                            <div class="attachment-size"></div>
                            <input name="fau_attachment[]" value="<?php echo e($license->attachment); ?>" type="hidden">
                            <input name="fau_attachment_real_name[]" value="<?php echo e($license->attachment_real_name); ?>" type="hidden">
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
</script><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/license/update.blade.php ENDPATH**/ ?>