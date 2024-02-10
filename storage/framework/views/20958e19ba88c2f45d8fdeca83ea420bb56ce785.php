<form type="create" id="adminLinkForm" data-fv-excluded="" callBack="formRefresh" panelTitle="Internal Link Create" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Menu</label>
        <div class="col-lg-10 col-md-9">
            <div id="link_menu_id">
                <select required id="menu_id" name="menu_id" class="form-control">
                    <option value=""></option>
                    <?php
                    $parent_ids = $adminMenus->pluck('parent_id');
                    $menu_list_1 = $parent_ids->filter(function($item) { return $item==0; })->all();
                    ?>
                    <?php $__currentLoopData = $menu_list_1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_1=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $menu_1 = $adminMenus[$menu_key_1];
                        $menu_list_2 = $parent_ids->filter(function($item) use ($menu_1) { return $item==$menu_1->id; })->all();
                        ?>
                        <?php if(!empty($menu_list_2)): ?>
                            <optgroup label="<?php echo e($menu_1->menu_name); ?>">
                                <?php $__currentLoopData = $menu_list_2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_2=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                    $menu_2 = $adminMenus[$menu_key_2];
                                    $menu_list_3 = $parent_ids->filter(function($item) use ($menu_2) { return $item==$menu_2->id; })->all();
                                    ?>
                                    <?php if(!empty($menu_list_3)): ?>
                                        <optgroup label="<?php echo e($menu_2->menu_name); ?>">
                                            <?php $__currentLoopData = $menu_list_3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_3=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                $menu_3 = $adminMenus[$menu_key_3];
                                                $menu_list_4 = $parent_ids->filter(function($item) use ($menu_3) { return $item==$menu_3->id; })->all();
                                                ?>
                                                <?php if(!empty($menu_list_4)): ?>
                                                    <optgroup label="<?php echo e($menu_3->menu_name); ?>">
                                                        <?php $__currentLoopData = $menu_list_4; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_4=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                            $menu_4 = $adminMenus[$menu_key_4];
                                                            $menu_list_5 = $parent_ids->filter(function($item) use ($menu_4) { return $item==$menu_4->id; })->all();
                                                            ?>
                                                            <?php if(!empty($menu_list_5)): ?>
                                                                <optgroup label="<?php echo e($menu_4->menu_name); ?>">
                                                                    <?php $__currentLoopData = $menu_list_5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_5=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php
                                                                        $menu_5 = $adminMenus[$menu_key_5];
                                                                        ?>
                                                                        <option value="<?php echo e($menu_5->id); ?>"><?php echo e($menu_5->menu_name); ?></option>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </optgroup>
                                                            <?php else: ?>
                                                                <option value="<?php echo e($menu_4->id); ?>"><?php echo e($menu_4->menu_name); ?></option>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </optgroup>
                                                <?php else: ?>
                                                    <option value="<?php echo e($menu_3->id); ?>"><?php echo e($menu_3->menu_name); ?></option>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </optgroup>
                                    <?php else: ?>
                                        <option value="<?php echo e($menu_2->id); ?>"><?php echo e($menu_2->menu_name); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php else: ?>
                            <option value="<?php echo e($menu_1->id); ?>"><?php echo e($menu_1->menu_name); ?></option>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Using Resource</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox id="resource" name="resource" value="1"> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Link Name</label>
        <div class="col-lg-10 col-md-9" id="link_name_field">
            <input required name="link_name" id="link_name" placeholder="Link Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required" id="route_level">Route</label>
        <div class="col-lg-10 col-md-9">
            <input required name="route" id="route" placeholder="Route Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Status</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox name="status" value="1" checked> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create Link</button>
        </div>
    </div>
</form>
<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#menu_id").select2({placeholder:"Select Menu"});
        $("#resource").change(function(){
            if($(this).is(':checked')) {
                $('#adminLinkForm').formValidation('removeField', $("#link_name"));
                $("#link_name_field").html('<div class="row"><div class="col-md-3"><input required name="link_name_add" placeholder="Add Link" class="form-control"></div><div class="col-md-3"><input required name="link_name_edit" placeholder="Edit Link" class="form-control"></div><div class="col-md-3"><input required name="link_name_delete" placeholder="Delete Link" class="form-control"></div><div class="col-md-3"><input required name="link_name_show" placeholder="Show Link" class="form-control"></div></div>');
                $("#route_level").html('Main Link');
                $("#route").attr('placeholder', 'Main Link');
            } else {
                $("#link_name_field").html('<input required name="link_name" id="link_name" placeholder="Link Name" class="form-control" kl_virtual_keyboard_secure_input="on">');
                $("#route_level").html('Route');
                $("#route").attr('placeholder', 'Route Name');
                $('#adminLinkForm').formValidation('addField', $("#link_name"));
            }
        });
        
    });
    function formRefresh() {
        if($("#link_name").length==0) {
            $("#link_name_field").html('<input required name="link_name" id="link_name" placeholder="Link Name" class="form-control" kl_virtual_keyboard_secure_input="on">');
            $('#adminLinkForm').formValidation('addField', $("#link_name"));
        }
    }
</script><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/adminInternalLink/create.blade.php ENDPATH**/ ?>