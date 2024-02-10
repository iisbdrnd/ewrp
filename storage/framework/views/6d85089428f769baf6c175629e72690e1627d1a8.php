<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="page-header" id="home">
    <div class="header-caption">
        <div class="header-caption-contant">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-caption-inner">
                            <h2>Organizational Chart</h2>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->
<!-- Team Section -->
<div class="team-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title-area-4 foo" data-sr='enter'>
                    <h2 class="section-title">Board of Directors</h2>
                    <p>
                        At EWPCI we gratefully acknowledge the dedication of our Board of Directors. EWPCI leaders are passionate advocates of the EWPCI vision and work on behalf of the global network to achieve SITE's strategic goals.
                        <br>
                        As of July 2021, the Board of Directors comprises the following members:
                    </p>
                </div>
            </div>
        </div>
        <?php if(count($managementTeams) > 0): ?>
        <div class="row">
            <?php $__currentLoopData = $managementTeams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$managementTeam): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($key == 0): ?>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-xs-12 col-sm-6 col-md-4 col-md-offset-4">
                        <div class="team-member foo" data-sr='enter'>
                            <div class="team-header">
                                <img src="<?php echo e(asset('public/uploads/managementTeam/'.$managementTeam->image)); ?>" alt="responsive img">
                            </div>
                            <div class="team-body">
                                <div class="short-info" style="padding: 50px 0px 78px;">
                                    <h3 class="member-name">
                                        <?php echo e($managementTeam->name); ?>

                                    </h3>
                                    <h5 class="designation" style="font-size: 11px;"><?php echo e($managementTeam->designation); ?></h5>
                                </div>
                                <p style="font-size: 10px;">
                                    Mobile: <?php echo e($managementTeam->phone); ?> <br>
                                    Email : <?php echo e($managementTeam->email); ?>

                                </p>
                                
                            </div>
                            <div class="team-footer">
                                <h4 class="short-designation">
                                    <?php echo e($managementTeam->name); ?> <br>
                                    <small><?php echo e($managementTeam->designation); ?></small>
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                
                    <div class="col-xs-12 col-sm-6 <?php echo e(count($managementTeams) > 4 ? 'col-md-3' : 'col-md-4'); ?>">
                        <div class="team-member foo" data-sr='enter'>
                            <div class="team-header">
                                <img src="<?php echo e(asset('public/uploads/managementTeam/'.$managementTeam->image)); ?>" alt="responsive img">
                            </div>
                            <div class="team-body">
                                <div class="short-info" style="padding: 50px 0px 78px;">
                                    <h3 class="member-name"><?php echo e($managementTeam->name); ?></h3>
                                    <h5 class="designation" style="font-size: 11px;"><?php echo e($managementTeam->designation); ?></h5>
                                </div>
                                <p style="font-size: 10px;">
                                    Mobile: <?php echo e($managementTeam->phone); ?> <br>
                                    Email : <?php echo e($managementTeam->email); ?>

                                </p>
                            </div>
                            <div class="team-footer">
                                <h4 class="short-designation">
                                    <?php echo e($managementTeam->name); ?><br>
                                    <small><?php echo e($managementTeam->designation); ?></small>
                                </h4>
                            </div>
                        </div>
                    </div>
                
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<div class="team-area inner-padding">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <div class="section-title-area-4 foo" data-sr='enter'>
                    <h2 class="section-title">Company Organogram</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <img src="<?php echo e(asset('public/web/img/organogram.jpg')); ?>" alt="">
            </div>
        </div>
    </div>
</div>
<!-- End Team Section -->
<?php $__env->stopSection(); ?>
<?php echo $__env->make('web.layouts.subDefault', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/web/organizationChart.blade.php ENDPATH**/ ?>