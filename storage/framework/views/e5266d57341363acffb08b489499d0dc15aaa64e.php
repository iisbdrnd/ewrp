<div class="blog-masonary">
                    
    
    <table id="example" class="table table-striped table-bordered" style="width:100% !important">
        <thead>
            <tr>
                <th>Company</th>
                <th>Country</th>
                <th>Trade</th>
                <th class="centerAlign">Qty.</th>
                <th class="centerAlign">Salary</th>
                <th class="centerAlign">Food</th>
                <th class="centerAlign">Room</th>
                <th class="centerAlign">Age</th>
                <th class="centerAlign">Interview</th>
                <th class="centerAlign" style="width: 55px !important;">Circular</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($jobs)>0): ?>
                <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($job->company_name); ?></td>
                    <td><?php echo e($job->country_name); ?></td>
                    <td><?php echo e($job->category_name); ?></td>
                    <td class="centerAlign"><?php echo e($job->quantity ? $job->quantity : 'N/A'); ?></td>
                    <td class="centerAlign"><?php echo e($job->salary ? $job->salary : 'Negotiable'); ?></td>
                    <td class="centerAlign"><?php echo e($job->food_status); ?></td>
                    <td class="centerAlign"><?php echo e($job->accommodation_status); ?></td>
                    <td class="centerAlign"><?php echo e($job->age); ?></td>
                    <?php if($job->interview_status == 1): ?>
                    <td style="display: flex; align-items: center;">
                        <div id="circle" class="offline"></div>
                        <div><?php echo e($job->interview_date); ?></div>
                    </td>
                    <?php else: ?>
                    <td></td>
                    <?php endif; ?>
                    <td class="centerAlign">
                        <?php if($job->attachment_name): ?>
                        <a href="<?php echo e(url('public/uploads/job_opening_attachments/'.$job->attachment_name)); ?>" target="_blank" class="btn btn-success btn-xs-outline viewJobs" style="background: red; padding: 2px; font-size:12px; text-transform: inherit; color: white; border: 1px solid red;" role="button">Circular</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <tr style="text-align: center; font-size: 20px;">
                    <td colspan="9">
                        No Job Found
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Company</th>
                <th>Country</th>
                <th>Trade</th>
                <th class="centerAlign">Qty.</th>
                <th class="centerAlign">Salary</th>
                <th class="centerAlign">Food</th>
                <th class="centerAlign">Room</th>
                <th class="centerAlign">Age</th>
                <th class="centerAlign">Interview</th>
                <th class="centerAlign">Circular</th>
            </tr>
        </tfoot>
    </table>
    

</div><?php /**PATH /home/eastymap/public_html/resources/views/web/viewAjaxJobList.blade.php ENDPATH**/ ?>