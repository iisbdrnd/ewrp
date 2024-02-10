<?php $__env->startSection('content'); ?>
<style>
    iframe{
        display: none;
    }
    .img-view{
        /* z-index: -1!important; */
        height: 100%!important;
        /* min-width: 100%!important; */
        /* min-height: 100%; */
        /* transform: translate(-50%, -50%); */
        object-fit: cover!important;
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
                            <h2>Gallery</h2>
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="portfolio-area inner-padding">
    <div class="container-fluid">
        <div class="row foo" data-sr="enter" data-sr-id="30" style="; visibility: visible;  -webkit-transform: translateY(0) scale(1); opacity: 1;transform: translateY(0) scale(1); opacity: 1;-webkit-transition: -webkit-transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; transition: transform 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s, opacity 0.8s cubic-bezier(0.6, 0.2, 0.1, 1) 0s; ">
            <p class="text-center">
                <span style="font-size: 25px; font-weight: 600;"><?php echo e($album->gallery_name); ?></span> <br>
                <?php echo e($album->description); ?>

            </p>
            
            <a href="<?php echo e(route('galleryAlbum')); ?>" style="text-align: right; padding-left: 10px; font-size: 14px">Back To Gallery</a>
            <div class="portfolio-masonry portfolio-items" style="position: relative; height: 597.594px; margin-top: 16px;">
                <?php if(count($galleryPhotos)>0): ?>
                    <?php $__currentLoopData = $galleryPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="portfolio-item isotope <?php echo e($photo->slug); ?>" style="position: absolute; left: 395px; top: 0px; height: 240px;">
                        <div class="portfolio-item-inner">
                            <img src="<?php echo e(asset('public/uploads/gallery/thumb/'.$photo->image_thumb)); ?>" alt="responsive img" class="img-view">
                            <div class="portfolio-caption">
                                <a class="portfolio-action-btn" href="<?php echo e(asset('public/uploads/gallery/thumb/'.$photo->image_thumb)); ?>" data-popup="prettyPhoto[img]"><img src="<?php echo e(asset('public/web/img/zoom.png')); ?>" alt="responsive img"></a>
                                
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <p style="font-size: 25px; text-align: center;">
                        No Image Found
                    </p>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('web.layouts.subDefault', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/web/gallery.blade.php ENDPATH**/ ?>