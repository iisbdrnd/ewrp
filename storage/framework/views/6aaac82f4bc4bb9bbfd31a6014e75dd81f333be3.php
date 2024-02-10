<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Details</h4>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12 col-md-10 col-md-offset-1">
                    <div class="section-title-area-1" style="margin: 20px 0px 25px;">
                        <h2 class="section-title">All Categories</h2>
                    </div>
                    <div class="about-content">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col" width="10%">#</th>
                                    <th scope="col" width="70%">Title</th>
                                    <th scope="col" width="20%">Total Job</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $jobCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($key+1); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('jobOpening', ['category_id'=>$category->id])); ?>"><?php echo e($category->name); ?></a>
                                    </td>
                                    <td><?php echo e($category->total_job); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                            
                        
                    </div>
                </div>
            </div>
            <!-- End About Section -->
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
    </div>
</div><?php /**PATH /home/eastymap/public_html/resources/views/web/allJobCategory.blade.php ENDPATH**/ ?>