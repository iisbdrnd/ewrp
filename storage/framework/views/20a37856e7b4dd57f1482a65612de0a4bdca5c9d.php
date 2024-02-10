<div class="blog-masonary">
                    
    <table id="example" class="table table-striped table-bordered" style="width:100% !important">
        <thead>
            <tr>
                <th class="centerAlign" width="10%">SL. </th>
                <th class="centerAlign" width="50%">Title</th>
                <th class="centerAlign" width="20%">Publish Date</th>
                <th class="centerAlign" width="20%">View</th>
            </tr>
        </thead>
        <tbody>
            <?php if(count($notices)>0): ?>
                <?php $__currentLoopData = $notices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$notice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="centerAlign">
                        <?php echo e(++$key); ?>

                    </td>
                    <td>
                        <?php if($notice->notice_type == 1): ?>
                            <a href="<?php echo e(url('public/uploads/notice_attachments/'.$notice->attachment_name)); ?>" target="_blank"><?php echo e($notice->title); ?></a>
                            <br>
                        <?php else: ?>
                            <a href="<?php echo e($notice->external_link); ?>" target="_blank"><?php echo e($notice->title); ?></a>
                            <br>
                        <?php endif; ?>    
                    </td>
                    <td><?php echo e(date('d M Y', strtotime($notice->created_at))); ?></td>
                    <td style="text-align: center;">
                        <?php if($notice->notice_type == 1): ?>
                            <a href="<?php echo e(url('public/uploads/notice_attachments/'.$notice->attachment_name)); ?>" target="_blank" class="btn btn-success btn-xs-outline viewJobs" style="background: red; padding: 5px; font-size:12px; text-transform: inherit; color: white; border: 1px solid red;" role="button">View</a>
                        <?php else: ?>
                            <a href="<?php echo e($notice->external_link); ?>" target="_blank" class="btn btn-success btn-xs-outline viewJobs" style="background: red; padding: 5px; font-size:12px; text-transform: inherit; color: white; border: 1px solid red;" role="button">View</a>

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
                <th class="centerAlign">SL</th>
                <th class="centerAlign">Title</th>
                <th class="centerAlign">Publish Date</th>
                <th class="centerAlign">View</th>
                
            </tr>
        </tfoot>
    </table>
    
</div><?php /**PATH /home/eastymap/public_html/resources/views/web/viewAjaxNoticeList.blade.php ENDPATH**/ ?>