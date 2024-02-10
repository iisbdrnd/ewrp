<?php $__env->startSection('content'); ?>

<!-- Hero Section -->
<div class="page-header" id="home">
    <div class="header-caption">
        <div class="header-caption-contant">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-caption-inner">
                            <h2>Disclaimer & Privacy Policy</h2>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
<div class="about-area inner-padding" style="height: 500px;">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-12 foo" data-sr='enter'>
                <?php echo $termsAndCondition->terms_and_condition; ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('web.layouts.subDefault', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/web/termsAndConditions.blade.php ENDPATH**/ ?>