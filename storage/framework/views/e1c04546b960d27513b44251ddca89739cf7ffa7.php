<select required id="module_id" name="module_name" class="form-control">
    <option value=""></option>
    <?php $__currentLoopData = $softwareLinkModules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $softwareLinkModule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <option value="<?php echo e($softwareLinkModule->id); ?>"><?php echo e($softwareLinkModule->module_name); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
<?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/softwareInternalLink/softwareInternalLinkModule.blade.php ENDPATH**/ ?>