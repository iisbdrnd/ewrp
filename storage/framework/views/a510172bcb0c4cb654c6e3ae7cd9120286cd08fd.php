<?php $panelTitle = "Counter Update"; ?>
<?php echo $__env->make("panelStart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<form type="update" action="<?php echo e(route('provider.eastWest.provider.eastWest.counter.update', 0)); ?>" panelTitle="<?php echo e($panelTitle); ?>" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <?php $__currentLoopData = $counters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$counter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required"><?php echo e($counter->title); ?></label>
        <div class="col-lg-8 col-md-6">
            <input autofocus required name="counter[]" placeholder="<?php echo e($counter->title); ?>" value="<?php echo e($counter->counter); ?>" class="form-control">
            <input type="hidden" name="ids[]" value="<?php echo e($counter->id); ?>">
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
</script>

<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/counter/update.blade.php ENDPATH**/ ?>