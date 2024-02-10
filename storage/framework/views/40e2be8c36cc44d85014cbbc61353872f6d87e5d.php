
<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<div class="page-header" id="home">
    <div class="header-caption">
        <div class="header-caption-contant">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="header-caption-inner">
                            <h2>Job Details</h2>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<!-- Post Details Section -->
<div class="blog-area inner-padding7">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-md-offset-1">
                <div class="single-post-row foo" data-sr='enter'>
                    
                    <div class="single-post-body">
                        <div class="single-post-caption">
                            <h2 class="single-post-heading"><a style="cursor: pointer;"><?php echo e($job->title); ?></a></h2>
                            <div class="single-post-meta"><?php echo e($job->principal); ?></div>
                            <div class="single-post-sticker"><small><?php echo e(date('d', strtotime($job->created_at))); ?></small>
                                <p class="month"><?php echo e(date('M', strtotime($job->created_at))); ?></p>
                            </div>
                        </div>
                        <div class="">
                            <h2 style="padding-bottom: 10px;">Job Detail</h2>
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <p>
                                        <b>Offerd Salary</b> <br> <?php echo e(!empty($job->salary) ? $job->salary : 'N/A'); ?>

                                    </p>
                                    <p>
                                        <b>Gender</b> <br> <?php echo e(!empty($job->gender) ? $job->gender : 'N/A'); ?>

                                    </p>
                                    <p>
                                        <b>Experience</b> <br> <?php echo e(!empty($job->experience) ? $job->experience : 'N/A'); ?>

                                    </p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <p>
                                        <b>Job Type</b> <br> <?php echo e(!empty($job->job_type) ? $job->job_type : 'N/A'); ?>

                                    </p>
                                    <p>
                                        <b>Country</b> <br> <?php echo e(!empty($job->country) ? $job->country : 'N/A'); ?>

                                    </p>
                                    <p>
                                        <b>Education</b> <br> <?php echo e(!empty($job->education) ? $job->education : 'N/A'); ?>

                                    </p>
                                </div>
                                <div class="col-md-4 text-center">
                                    <p>
                                        <b>Age</b> <br> <?php echo e(!empty($job->age_from && $job->age_to) ? $job->age_from.' - '.$job->age_to : 'N/A'); ?>

                                    </p>
                                    <p>
                                        <b>Religion</b> <br> <?php echo e(!empty($job->religion) ? $job->religion : 'N/A'); ?>

                                    </p>
                                    <p>
                                        <b>Duration</b> <br> <?php echo e(!empty($job->duration) ? $job->duration : 'N/A'); ?>

                                    </p>
                                </div>
                            </div>
                        </div>
                        <h2 style="padding: 10px 0;">Job Description</h2>
                        <p class="post-text">
                            <?php echo e(strip_tags($job->job_description)); ?>

                            <br>
                            <br>
                            <span style="font-weight: 600;">Deadline : <?php echo e(date('d M Y', strtotime($job->deadline))); ?></span>
                        </p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<!-- End Post Details Section -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('web.layouts.subDefault', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/eastymap/public_html/resources/views/web/jobDetails.blade.php ENDPATH**/ ?>