<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-3 col-xs-12">
            <div class="input-group" style="width: 100%;">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="<?php echo e(@$search); ?>" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-primary" type="button">Go</button></span>
            </div>
        </div>

        <div class="col-md-6 col-xs-12 pull-right">
            <?php if($access->create): ?>
            <button class="add-btn btn btn-primary pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
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
                        <th width="15%">Social Logo</th>
                        <th width="60%" data="1">Social Link</th>
                        <th width="15%">Short Link Selection</th>
                        <?php if($access->edit || $access->destroy): ?>
                        <th width="5%">Action</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>No.</th>
                        <th>Social Logo</th>
                        <th>Social Link</th>
                        <th>Short Link Selection</th>
                        <?php if($access->edit || $access->destroy): ?>
                        <th>Action</th>
                        <?php endif; ?>
                    </tr>
                </tfoot>
                <tbody>
                <?php $paginate = $socialLinks; ?>
                <?php if(count($socialLinks)>0): ?>
                    <?php $__currentLoopData = $socialLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($sn++); ?></td>
                            <td class="text-center">
                                <img class="img-thumbnail" src="<?php echo e(url('public/uploads/social_logo/thumb/'.$link->social_logo)); ?>">
                            </td>
                            <td><?php echo e($link->social_link); ?></td>
                            <td>
                                <div class="toggle-custom toggle-inline m0">
                                    <label class="toggle h30" data-on=Yes data-off=No>
                                        <input class="status" id="status" type=checkbox name="status" value="1" <?php if($link->short_link==1): ?>checked='checked' <?php endif; ?> rowId="<?php echo e($link->id); ?>"> <span class=button-checkbox></span>
                                    </label>
                                </div>
                            </td>            
                         

                            <?php if($access->edit || $access->destroy): ?>
                            <td>
                            <?php if($access->edit): ?><i class="fa fa-edit" id="edit" data="<?php echo e($link->id); ?>"></i><?php endif; ?> 
                            
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
        $('.status').on('change', function() {
            let socialId = $(this).attr('rowId');
            if (socialId) {
                $.ajax({
                    url: "<?php echo e(route('provider.eastWest.updateSortLinkSelect')); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {_token: "<?php echo e(csrf_token()); ?>", socialId:socialId},
                    success: function (response) {
                        $.gritter.add({
                            title: response.msg_title,
                            text: response.messege,
                            time: "",
                            close_icon: "entypo-icon-cancel s12",
                            icon: response.messege_icon,
                            class_name: response.msgType
                        }); 
                    }
                });
            }
        })
    });
</script>
<?php /**PATH /home/eastymap/public_html/resources/views/provider/eastWest/socialLink/listData.blade.php ENDPATH**/ ?>