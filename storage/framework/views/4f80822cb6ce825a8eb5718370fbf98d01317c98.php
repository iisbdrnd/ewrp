<?php $urlSeparatorDataTable = "~"; ?>
<div class="url-pMeter">
    <?php $__currentLoopData = $inputData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $input=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
        $inputArray = explode($urlSeparatorDataTable, $input);
        $inputClass = (count($inputArray)>1) ? $inputArray[0]."-data-input" : "data-input";
        ?>
        <input id="<?php echo e($input); ?>" class="<?php echo e($inputClass); ?>" type="hidden" value="<?php echo e($data); ?>">
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div><?php /**PATH /home/eastymap/public_html/resources/views/urlParaMeter.blade.php ENDPATH**/ ?>