<?php $panelTitle = "Notice Create"; ?>
<?php echo $__env->make("panelStart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<form type="create" panelTitle="<?php echo e($panelTitle); ?>" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

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
                <?php $__currentLoopData = $noticeBoardCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <button id="file_attachment_upload" input-prefix="fau" file-path="public/uploads/notice_attachments" _token="<?php echo e(csrf_token()); ?>" auto-remove="true" type="button" data-loading-text="Uploading..." class="btn btn-info"> Attach Files </button>
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

<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/noticeBoard/create.blade.php ENDPATH**/ ?>