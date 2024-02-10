<input id="user_id" type="hidden" value="<?php echo e($user_info->id); ?>">
<?php $__currentLoopData = $software_modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $software_module_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class=row>
        <?php $__currentLoopData = $software_module_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $software_module): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <a href=# data="<?php echo e($software_module->id); ?>" class="soft-module stats-btn pattern mb20"><i class="icon <?php echo e($software_module->module_icon); ?>"></i> <span class=txt><?php echo e($software_module->module_name); ?></span></a>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<button class="back-btn btn btn-default" type="button">Back to List</button>

<script type="text/javascript">
    $(document).ready(function(){
        $("#userList").find(".panel-title").html('<?php echo e($user_info->name); ?>');
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/user/userAccess.blade.php ENDPATH**/ ?>