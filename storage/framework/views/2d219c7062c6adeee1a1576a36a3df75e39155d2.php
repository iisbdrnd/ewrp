<?php $panelTitle = "Carrier Update"; ?>
<?php echo $__env->make("panelStart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<form type="update" panelTitle="<?php echo e($panelTitle); ?>" action="<?php echo e(route('provider.eastWest.provider.eastWest.banner.update', [$banner->id])); ?>" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="title" value="<?php echo e($banner->title); ?>" placeholder="Title" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Mini Title</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="mini_title" value="<?php echo e($banner->mini_title); ?>" placeholder="Mini Title" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Button Text</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="btn_text" value="<?php echo e($banner->btn_text); ?>" placeholder="Button Text" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Button Link</label>
        <div class="col-lg-8 col-md-6">
            <input autofocus name="btn_link" value="<?php echo e($banner->btn_link); ?>" placeholder="Button Link" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Description</label>
        <div class="col-lg-8 col-md-6">
            <textarea id="description" name="description" rows="5" class="form-control"> <?php echo e($banner->description); ?> </textarea>
        </div>
    </div>
    <div class=form-group>
        <label class="col-lg-2 col-md-3 control-label">Banner</label>
        <div class="col-lg-2 col-md-3">
            <div class="file-upload" input="banner" filepath="public/uploads/banner" prefile="<?php echo e($banner->banner); ?>" reqwidth="1600" reqheight="889" ext="jpg,jpeg,png,gif"></div>
            <div class="pt5 s10">[Size: 1600px * 889px]</div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
</form>

<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script>
    $(document).ready(function() {    
        $("#description").summernote({
            height: 150
        });
        $("#contact_us").summernote({
            height: 100
        });
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/banner/update.blade.php ENDPATH**/ ?>