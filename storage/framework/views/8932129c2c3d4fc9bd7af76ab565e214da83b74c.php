<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="<?php echo e(@$search); ?>" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            <?php echo $__env->make("perPageBox", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="25%" data="1">Menu Name</th>
                <th width="25%" data="2">Link Name</th>
                <th width="25%" data="3">Module Name</th>
                <th width="20%" data="4">Access Date</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Menu Name</th>
                <th>Link Name</th>
                <th>Module Name</th>
                <th>Access Date</th>
            </tr>
        </tfoot>
        <tbody>
        <?php $paginate = $projectAccess; ?>
        <?php $__currentLoopData = $projectAccess; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $projectAccess): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($sn++); ?></td>
                <td><?php echo e($projectAccess->menu_name); ?></td>
                <td><?php echo e($projectAccess->link_name); ?></td>
                <td><?php echo e($projectAccess->module_name); ?></td>
                <td><?php echo e($projectAccess->updated_at); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <?php echo $__env->make("pagination", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/project/projectAccessView.blade.php ENDPATH**/ ?>