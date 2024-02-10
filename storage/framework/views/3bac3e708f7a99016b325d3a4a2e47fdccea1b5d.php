<?php $__currentLoopData = $software_modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $software_module_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class=row>
        <?php $__currentLoopData = $software_module_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $software_module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <a href=# data="<?php echo e($software_module->id); ?>" class="soft-module stats-btn pattern mb20"><i class="icon <?php echo e($software_module->module_icon); ?>"></i> <span class=txt><?php echo e($software_module->module_name); ?></span></a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/project/projectAccessModuleViewByFolder.blade.php ENDPATH**/ ?>