<?php $panelTitle = "Notice Category Update"; ?>

<form type="update" panelTitle="<?php echo e($panelTitle); ?>" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Category Name</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="name" placeholder="Category Name" value="<?php echo e($jobCategory->name); ?>" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
</form>
    

<script>
    $(document).ready(function() {    
        $("#description").summernote({
            height: 150
        });
        $("#contact_us").summernote({
            height: 100
        });
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/noticeCategory/update.blade.php ENDPATH**/ ?>