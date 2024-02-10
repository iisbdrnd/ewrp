<?php if(empty($perPageArray)) { $perPageArray = array(10, 25, 50, 100); } ?>
<div class="dataTables_length">
    <label>
        <span>
            <select name="basic-datatables_length" class="form-control input-sm" id="perPage">
                <?php $__currentLoopData = $perPageArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $perPageVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if(!empty($perPage) && ($perPage==$perPageVal)): ?> <?php echo e('selected'); ?> <?php endif; ?> value="<?php echo e($perPageVal); ?>"><?php echo e($perPageVal); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </span>
    </label>
</div><?php /**PATH /home/eastymap/public_html/resources/views/perPageBox.blade.php ENDPATH**/ ?>