<!-- Start .row -->
<div class=row>
    <div class="col-lg-12">
        <!-- col-lg-12 start here -->
        <div load-url="<?php echo e($loadUrl); ?>" <?php if(!empty($dataTableId)): ?>id="<?php echo e($dataTableId); ?>"<?php endif; ?> <?php if(!empty($dataPrefix)): ?>data-prefix="<?php echo e($dataPrefix); ?>"<?php endif; ?> <?php if(!empty($urlParameter)): ?>url-parameter="<?php echo e($urlParameter); ?>"<?php endif; ?> <?php if(!empty($updateLink)): ?>update-link="<?php echo e($updateLink); ?>"<?php endif; ?> <?php if(!empty($updateBack)): ?>update-back="<?php echo e($updateBack); ?>"<?php endif; ?> <?php if(!empty($deleteLink)): ?>delete-link="<?php echo e($deleteLink); ?>"<?php endif; ?> <?php if(!empty($refreshUrl)): ?>refresh-url="<?php echo e($refreshUrl); ?>"<?php endif; ?> <?php if(!empty($refreshCallBack)): ?>refresh-callback="<?php echo e($refreshCallBack); ?>"<?php endif; ?> class="data-table panel panel-default <?php if(@$panelMove===false): ?><?php echo e(''); ?><?php else: ?><?php echo e('panelMove'); ?><?php endif; ?> showControls toggle panelClose <?php if(@$panelRefresh===false): ?><?php echo e(''); ?><?php else: ?><?php echo e('panelRefresh'); ?><?php endif; ?> <?php if(!empty($class)): ?><?php echo e($class); ?><?php endif; ?>" <?php if(!empty($attr)): ?> <?php $__currentLoopData = $attr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atk=>$atv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($atk.'='.$atv.' '); ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> <?php endif; ?>>
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo e($tableTitle); ?></h4>
            </div>
            <?php echo e(csrf_field()); ?>

            <div class="panel-body data-list"></div>
        </div>
        <!-- End .panel -->
    </div>
</div>
<!-- End .row --><?php /**PATH /home/eastymap/public_html/resources/views/dataListFrame.blade.php ENDPATH**/ ?>