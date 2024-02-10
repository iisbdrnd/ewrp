<?php $panelTitle = "Company Policy Update"; ?>

<form type="update" panelTitle="<?php echo e($panelTitle); ?>" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Description</label>
        <div class="col-lg-8 col-md-6">
            <textarea required id="description" name="description" rows="5" class="form-control"> <?php echo e($companyHistory->description); ?> </textarea>
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
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/companyHistory/update.blade.php ENDPATH**/ ?>