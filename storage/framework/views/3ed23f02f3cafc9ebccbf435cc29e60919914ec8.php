<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="<?php echo e(@$search); ?>" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            <?php if(Helper::adminAccess('projectRegistration.create')): ?>
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            <?php endif; ?>
            <?php echo $__env->make("perPageBox", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="23%" data="1">Project ID</th>
                <th width="34%" data="2">Name</th>
                <th width="23%" data="3">Last Update</th>
                <?php if(Helper::adminAccess('projectRegistration.edit') || Helper::adminAccess('projectRegistration.destroy') || Helper::adminAccess('projectAccess', 0)): ?>
                <th class="text-center" width="15%">Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Link</th>
                <th>Last Update</th>
                <?php if(Helper::adminAccess('projectRegistration.edit') || Helper::adminAccess('projectRegistration.destroy') || Helper::adminAccess('projectAccess', 0)): ?>
                <th class="text-center">Action</th>
                <?php endif; ?>
            </tr>
        </tfoot>
        <tbody>
		<?php $paginate = $projects; ?>
        <?php if(count($projects)>0): ?>
			<?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><?php echo e($sn++); ?></td>
					<td><?php echo e($project->project_id); ?></td>
					<td><?php echo e($project->name); ?></td>
					<?php
						$updatedAt = DateTime::createFromFormat('Y-m-d H:i:s', $project->updated_at);
						$updated_at = $updatedAt->format('d/m/Y g:i A');
					?>
					<td><?php echo e($updated_at); ?></td>
					<?php if(Helper::adminAccess('projectRegistration.edit') || Helper::adminAccess('projectRegistration.destroy') || Helper::adminAccess('projectAccess', 0)): ?>
					<td class="text-center">
                        <?php if(Helper::adminAccess('projectRegistration.edit')): ?>
                            <i class="fa fa-edit" id="edit" data="<?php echo e($project->id); ?>"></i>
                        <?php endif; ?> 
                        <?php if(Helper::adminAccess('projectRegistration.destroy')): ?>
                            <i class="fa fa-trash-o" id="delete" data="<?php echo e($project->id); ?>"></i><?php endif; ?>
                        <br>
                        <?php if(Helper::adminAccess('projectAccess', 0)): ?>
                            <button url="projectAccess" data="<?php echo e($project->id); ?>" callBack="projectAccessView" class="go-btn btn btn-default btn-xs" type="button">Access</button>
                        <?php endif; ?> 
                        <button url="projectMailConfiguration" data="<?php echo e($project->id); ?>" class="go-btn btn btn-default btn-xs" type="button">Mail Config.</button>
                        
                    </td>
					<?php endif; ?>
				</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<?php else: ?>    
			<tr>
				<td colspan="5" class="emptyMessage">Empty</td>
			</tr>
		<?php endif; ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <?php $paginate = $projects; ?>
            <?php echo $__env->make("pagination", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/project/projectList.blade.php ENDPATH**/ ?>