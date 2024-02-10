<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-12">
            <div class="input-group" style="width: 100%;">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="<?php echo e(@$search); ?>" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>

        <div class="col-md-6 col-xs-12 pull-right">
            <?php if($access->create): ?>
            <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            <button url="gallerySorting" main-selector="mainDiv" clone-selector="cloneDiv" data-prefix="cl" panel-title="Ashram Sorting " class="go-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="s14 icomoon-icon-sort"></i>Sorting</button>
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
                        <th width="30%">Gallery Name</th>
                        <th width="37%">Gallery Details</th>
                        <th width="10%">Gallery Thumb</th>
                        <th width="10%">Upload</th>
                        <?php if($access->edit || $access->destroy): ?>
                        <th width="8%">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Gallery Name</th>
                        <th>Gallery Details</th>
                        <th>Gallery Thumb</th>
                        <th>Upload</th>
                        <?php if($access->edit || $access->destroy): ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $photoGalleries; ?>
                <?php if(count($photoGalleries)>0): ?>
                    <?php $__currentLoopData = $photoGalleries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gallery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($sn++); ?></td>
                            <td><?php echo e($gallery->gallery_name); ?></td>
                            <td><?php echo $gallery->description; ?></td>
                            <td class="text-center">
                                <img class="img-thumbnail" src="<?php echo e(url('public/uploads/gallery/'.$gallery->gallery_thumb)); ?>">
                            </td>
                            <td class="text-center">
                                <button url="galleryImage?photo_gallery_id=<?php echo e($gallery->id); ?>" main-selector="mainDiv" clone-selector="cloneDiv" data-prefix="cl" panel-title="Invitations of <?php echo e($gallery->gallery_name); ?> class" class="go-btn hand btn btn-info btn-xs" stroke-url="galleryImageStore" type="button" style="margin-bottom:6px;">Upload Photo</button> 
                            </td>
                            <?php if($access->edit || $access->destroy): ?>
                            <td>
                            <?php if($access->edit): ?><i class="fa fa-edit" id="edit" data="<?php echo e($gallery->id); ?>"></i><?php endif; ?> <?php if($access->destroy): ?><i class="fa fa-trash-o" id="delete" data="<?php echo e($gallery->id); ?>"></i><?php endif; ?>
                            </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="emptyMessage">Empty</td>
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
<script type="text/javascript">
    $(document).ready(function() {
         $(".select2").select2({
            placeholder: "Select"
        });
    });
</script>
<?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/gallery/listData.blade.php ENDPATH**/ ?>