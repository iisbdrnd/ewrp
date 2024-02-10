<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="<?php echo e(@$search); ?>" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            <?php if(Helper::adminAccess('softwareMenu.create')): ?>
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            <?php endif; ?>
            <?php echo $__env->make("perPageBox", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
    <table cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th width="10%" data="1">Folder</th>
                <th width="15%" data="2">Menu Name</th>
                <th width="18%" data="3">Route</th>
                <th width="15%" data="4">Parent Menu</th>
                <th width="15%" data="5">Module</th>
                <th width="15%" data="6">Status</th>
                <?php if(Helper::adminAccess('softwareMenu.edit') || Helper::adminAccess('softwareMenu.destroy')): ?>
                <th width="7%">Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>No.</th>
                <th>Folder</th>
                <th>Menu Name</th>
                <th>Route</th>
                <th>Parent Menu</th>
                <th>Module</th>
                <th>Status</th>
                <?php if(Helper::adminAccess('softwareMenu.edit') || Helper::adminAccess('softwareMenu.destroy')): ?>
                <th>Action</th>
                <?php endif; ?>
            </tr>
        </tfoot>
        <tbody>
		<?php $paginate = $softwareMenus; ?>
		<?php if(count($softwareMenus)>0): ?>
			<?php $__currentLoopData = $softwareMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $softwareMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
				<tr>
					<td><?php echo e($sn++); ?></td>
                    <td><?php echo e($softwareMenu->folder_name); ?></td>
					<td><?php echo e($softwareMenu->menu_name); ?></td>
					<td><?php echo e($softwareMenu->route); ?></td>
					<td><?php echo e($softwareMenu->parent_menu_name); ?></td>
					<td><?php echo e($softwareMenu->module_name); ?></td>
					<td><?php if($softwareMenu->status==1): ?><?php echo e('Active'); ?><?php else: ?><?php echo e('Inactive'); ?><?php endif; ?></td>
					<?php if(Helper::adminAccess('softwareMenu.edit') || Helper::adminAccess('softwareMenu.destroy')): ?>
					<td><?php if(Helper::adminAccess('softwareMenu.edit')): ?><i class="fa fa-edit" id="edit" data="<?php echo e($softwareMenu->id); ?>"></i><?php endif; ?> <?php if(Helper::adminAccess('softwareMenu.destroy')): ?><i class="fa fa-trash-o" id="delete" data="<?php echo e($softwareMenu->id); ?>"></i><?php endif; ?></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		<?php else: ?>    
			<tr>
				<td colspan="7" class="emptyMessage">Empty</td>
			</tr>
		<?php endif; ?>
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12 col-xs-12">
            <?php $paginate = $softwareMenus; ?>
            <?php echo $__env->make("pagination", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/softwareMenu/list.blade.php ENDPATH**/ ?>