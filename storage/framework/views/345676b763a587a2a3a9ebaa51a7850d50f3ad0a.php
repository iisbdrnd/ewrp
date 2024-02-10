<?php $__env->startSection('content'); ?>
<style>
    .section-title-area-1{
        margin: 0px;
    }
    .portfolio-item-inner{
        height: 164px;
    }
</style>
<!-- Hero Section -->
<div class="page-header" id="home">
    <div class="header-caption">
        <div class="header-caption-contant">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-caption-inner">
                            <h2>Facilities</h2>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
<div class="about-area inner-padding">
    <div class="container">
        
        <div class="row">
            <div class="col-sm-12 col-md-12 foo" data-sr='enter'>
                <?php echo $headOfficeFacility->description; ?>

                
            </div>
        </div>
    </div>
</div>

<div class="portfolio-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title-area-4 foo" data-sr='enter'>
                    <h2 class="section-title">Head Office</h2>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        
            <div class="portfolio-masonry portfolio-items" style="position: relative; height: 597.594px;">
                <?php if(count($headOfficePhotos)>0): ?>
                    <?php $__currentLoopData = $headOfficePhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="portfolio-item isotope <?php echo e($photo->slug); ?>" style="position: absolute; left: 395px; top: 0px;">
                        <div class="portfolio-item-inner">
                            <img src="<?php echo e(asset('public/uploads/facilityHeadOffice/thumb/'.$photo->image_thumb)); ?>" alt="responsive img">
                            <div class="portfolio-caption">
                                <a class="portfolio-action-btn" href="<?php echo e(asset('public/uploads/facilityHeadOffice/thumb/'.$photo->image_thumb)); ?>" data-popup="prettyPhoto[img]"><img src="<?php echo e(asset('public/web/img/zoom.png')); ?>" alt="responsive img"></a>
                                
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                
            </div>
        
    </div>
</div>
<div class="about-area inner-padding">
    <div class="container">
        <div class="about-area-inner">
            <div class="row">
                <div class="col-sm-12 col-md-6 foo" data-sr='enter'>
                    <div id="about-silder" class="owl-carousel owl-theme about-slider">
                        <div class="item">
                            <img src="<?php echo e(asset('public/web/img/trainingFacilities/image1.jpg')); ?>" alt="responsive img">
                        </div>
                        <div class="item">
                            <img src="<?php echo e(asset('public/web/img/homePage/facility1.jpg')); ?>" alt="responsive img">
                        </div>
                        <div class="item">
                            <img src="<?php echo e(asset('public/web/img/homePage/facility2.jpg')); ?>" alt="responsive img">
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-md-5 foo" data-sr='enter'>
                    <div class="section-title-area-1" style="padding-top: 10px;">
                        <h2 class="section-title">Training Facility</h2>
                    </div>
                    <div class="about-content">
                        <p>
                            <?php if(count($trainingFacilities)>0): ?>
                                <?php $__currentLoopData = $trainingFacilities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trainingFacility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e($trainingFacility->title); ?> : <?php echo e($trainingFacility->description); ?> <br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Portfolio Section -->
<div class="portfolio-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title-area-4 foo" data-sr='enter'>
                    <h2 class="section-title">Training Center</h2>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        
            <div class="portfolio-masonry portfolio-items" style="position: relative; height: 597.594px;">
                <?php if(count($trainingFacilityPhotos)>0): ?>
                    <?php $__currentLoopData = $trainingFacilityPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="portfolio-item isotope <?php echo e($photo->slug); ?>" style="position: absolute; left: 395px; top: 0px;">
                        <div class="portfolio-item-inner">
                            <img src="<?php echo e(asset('public/uploads/trainingFacilities/thumb/'.$photo->image_thumb)); ?>" alt="responsive img">
                            <div class="portfolio-caption">
                                <a class="portfolio-action-btn" href="<?php echo e(asset('public/uploads/trainingFacilities/thumb/'.$photo->image_thumb)); ?>" data-popup="prettyPhoto[img]"><img src="<?php echo e(asset('public/web/img/zoom.png')); ?>" alt="responsive img"></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                
            </div>
        
    </div>
</div>
<!-- End Portfolio Section -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('web.layouts.subDefault', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/web/facilities.blade.php ENDPATH**/ ?>