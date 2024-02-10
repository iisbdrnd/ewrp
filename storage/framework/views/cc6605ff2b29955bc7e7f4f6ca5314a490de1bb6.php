<select required id="menu_id" name="menu_id" class="form-control">
    <option value=""></option>
    <?php $__currentLoopData = $softwareLinkMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $softwareLinkMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <option value="<?php echo e($softwareLinkMenu->id); ?>"><?php echo e($softwareLinkMenu->menu_name); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
<?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/softwareInternalLink/softwareLinkMenu.blade.php ENDPATH**/ ?>