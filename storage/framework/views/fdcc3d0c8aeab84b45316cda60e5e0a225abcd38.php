<?php if(empty($inputData['takeContent'])): ?>
<?php if(!isset($onlyPanel) || (!$onlyPanel)): ?>
<!-- Start .row -->
<div class=row>
    <div class="col-lg-12">
<?php endif; ?>
        <!-- col-lg-12 start here -->
        <div <?php if(!empty($panelId)): ?>id="<?php echo e($panelId); ?>"<?php endif; ?> <?php if(!empty($refreshUrl)): ?>refresh-url="<?php echo e($refreshUrl); ?>"<?php endif; ?> <?php if(!empty($refreshCallBack)): ?>refresh-callback="<?php echo e($refreshCallBack); ?>"<?php endif; ?> <?php if(!empty($dataPrefix)): ?>data-prefix="<?php echo e($dataPrefix); ?>"<?php endif; ?> <?php if(!empty($urlParameter)): ?>url-parameter="<?php echo e($urlParameter); ?>"<?php endif; ?> header-load="<?php if(@$headerLoad===false): ?><?php echo e('false'); ?><?php else: ?><?php echo e('true'); ?><?php endif; ?>" class="panel panel-default <?php if(@$panelMove===false): ?><?php echo e(''); ?><?php else: ?><?php echo e('panelMove'); ?><?php endif; ?> showControls toggle panelClose <?php if(@$panelRefresh===false): ?><?php echo e(''); ?><?php else: ?><?php echo e('panelRefresh'); ?><?php endif; ?> <?php if(!empty($class)): ?><?php echo e($class); ?><?php endif; ?>" <?php if(!empty($attr)): ?> <?php $__currentLoopData = $attr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atk=>$atv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($atk.'='.$atv.' '); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>>
            <!-- Start .panel -->

            
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $panelTitle; ?></h4>
            </div>
            <div class="panel-body <?php if(!empty($panelBodyClass)): ?><?php echo e($panelBodyClass); ?><?php endif; ?>">
<?php endif; ?><?php /**PATH /home/eastymap/public_html/resources/views/panelStart.blade.php ENDPATH**/ ?>