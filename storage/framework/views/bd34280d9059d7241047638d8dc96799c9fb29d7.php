<?php $__env->startSection('content'); ?>
<style>
    iframe{
        display: none;
    }
    .portfolio-item .portfolio-caption{
        opacity: .5;
    }
    .portfolio-caption-content{
        opacity: .5;
    }
    .portfolio-item-inner{
        height: 250px;
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
                            <h2>Albums</h2>
                            
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
            <div class="portfolio-masonry portfolio-items" style="position: relative; height: 597.594px;">
                <?php if(count($albums)>0): ?>
                    <?php $__currentLoopData = $albums; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $album): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <div class="portfolio-item isotope" style="position: absolute; left: 395px; top: 0px;">
                        <div class="portfolio-item-inner">
                            <?php if(!empty($album->gallery_thumb)): ?>
                                <img src="<?php echo e(asset('public/uploads/gallery/thumb/'.$album->gallery_thumb)); ?>" alt="responsive img">
                            <?php else: ?>
                                <img src="<?php echo e(asset('public/web/img/work-1.png')); ?>" alt="responsive img">
                            <?php endif; ?>
                            <a href="<?php echo e(route('galleryPhotos', $album->id)); ?>">
                                <div class="portfolio-caption">
                                    <div class="portfolio-caption-content">
                                        <p style="color: white; font-size: 17px; margin: 0 0 3px; font-weight: 0;">
                                            <?php echo e($album->gallery_name); ?>

                                        </p>
                                        
                                        
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</div>



<?php $__env->stopSection(); ?>
<?php echo $__env->make('web.layouts.subDefault', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/web/galleryAlbum.blade.php ENDPATH**/ ?>