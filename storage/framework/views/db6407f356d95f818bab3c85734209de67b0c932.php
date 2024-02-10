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
                        <th width="10%">Banner</th>
                        <th width="20%" data="1">Title</th>
                        <th width="10%">Button</th>
                        <th width="15%">Button Link</th>
                        <th width="35%">Description</th>
                        <?php if($access->edit || $access->destroy): ?>
                        <th width="5%">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Banner</th>
                        <th>Title</th>
                        <th>Button</th>
                        <th>Button Link</th>
                        <th>Description</th>
                        <?php if($access->edit || $access->destroy): ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $banners; ?>
                <?php if(count($banners)>0): ?>  
                <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($sn++); ?></td>
                        <td><img src="<?php echo e(asset('public/uploads/banner/'.$banner->banner)); ?>" width="150" alt=""></td>
                        <td><?php echo e($banner->title); ?></td>
                        <td><?php echo e($banner->btn_text); ?></td>
                        <td><?php echo e($banner->btn_link); ?></td>
                        <td><?php echo $banner->description; ?></td>
                        <?php if($access->edit || $access->destroy): ?>
                        <td><?php if($access->edit): ?><i class="fa fa-edit" id="edit" data="<?php echo e($banner->id); ?>"></i><?php endif; ?> <?php if($access->destroy): ?><i class="fa fa-trash-o" id="delete" data="<?php echo e($banner->id); ?>"></i><?php endif; ?></td>
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
<?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/banner/listData.blade.php ENDPATH**/ ?>