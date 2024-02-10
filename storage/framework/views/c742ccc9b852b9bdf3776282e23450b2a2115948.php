<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="<?php echo e(@$search); ?>" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
            <?php if($access->create): ?>
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            <button url="teamSorting" main-selector="mainDiv" clone-selector="cloneDiv" data-prefix="cl" panel-title="Commercials Sorting Sorting" class="go-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="s14 icomoon-icon-sort"></i>Sorting</button>
            <?php endif; ?>
            <?php echo $__env->make("perPageBox", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</div>
<div id=myTabContent2 class=tab-content>
    <div class="tab-pane fade active in" id=home2>
        <div class="form-inline">
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="10%" data="1">Image</th>
                        <th width="30%" data="1">Name</th>
                        <th width="10%" data="2">Designation</th>
                        <th width="30%">Address</th>
                        <th width="15%">Email</th>
                        <th width="10%">Phone</th>
                        <?php if($access->edit || $access->destroy): ?>
                        <th width="13%">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <?php if($access->edit || $access->destroy): ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $departments; ?>
                <?php if(count($departments)>0): ?>  
                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($sn++); ?></td>
                        <td><img src="<?php echo e(asset('public/uploads/managementTeam/thumb')); ?>/<?php echo e($department->image); ?>" width="50"/></td>
                        <td><?php echo e($department->name); ?></td>
                        <td><?php echo $department->designation; ?></td>
                        <td><?php echo e($department->address); ?></td>
                        <td><?php echo e($department->email); ?></td>
                        <td><?php echo e($department->phone); ?></td>
                        <?php if($access->edit || $access->destroy): ?>
                        <td><?php if($access->edit): ?><i class="fa fa-edit" id="edit" data="<?php echo e($department->id); ?>"></i><?php endif; ?> <?php if($access->destroy): ?><i class="fa fa-trash-o" id="delete" data="<?php echo e($department->id); ?>"></i><?php endif; ?></td>
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
                    <?php echo $__env->make("pagination", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/managementTeam/listData.blade.php ENDPATH**/ ?>