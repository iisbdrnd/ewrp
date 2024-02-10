<form type="update" id="softwareLinkForm" data-fv-excluded="" panelTitle="Internal Link Update" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Folder</label>
        <div class="col-lg-10 col-md-9">
            <select required id="folder_id" class="form-control">
                <option value="">None</option>
                <?php $__currentLoopData = $folder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($folder->id); ?>" <?php if($internalLinkmodule->folder_id==$folder->id): ?><?php echo e('selected'); ?><?php endif; ?>><?php echo e($folder->folder_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Module</label>
        <div class="col-lg-10 col-md-9">
            <div id="link_module_id">
                <select required id="module_id" name="module_name" class="form-control">
                    <option value="<?php echo e($internalLinkmodule->id); ?>"><?php echo e($internalLinkmodule->module_name); ?></option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Menu</label>
        <div class="col-lg-10 col-md-9">
            <div id="link_menu_id">
                <select required id="menu_id" name="menu_id" class="form-control">
                    <option value=""></option>
                    <?php $__currentLoopData = $softwareLinkMenus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $softwareLinkMenu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option <?php if($softwareLinkMenu->id==$softwareInternalLinks->menu_id): ?> <?php echo e('selected'); ?> <?php endif; ?> value="<?php echo e($softwareLinkMenu->id); ?>"><?php echo e($softwareLinkMenu->menu_name); ?></option>                
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
                    <input type=checkbox id="resource" name="resource" value="1" <?php if($softwareInternalLinks->resource==1): ?><?php echo e('checked'); ?><?php endif; ?>> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Link Name</label>
        <div class="col-lg-10 col-md-9" id="link_name_field">
            <input required name="link_name" id="link_name" placeholder="Link Name" class="form-control" value="<?php echo e($softwareInternalLinks->link_name); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label" id="route_level required">Route</label>
        <div class="col-lg-10 col-md-9">
            <input required name="route" id="route" placeholder="Route Name" class="form-control" value="<?php echo e($softwareInternalLinks->route); ?>">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Status</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox name="status" value="1" <?php if($softwareInternalLinks->status==1): ?><?php echo e('checked'); ?><?php endif; ?>> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update Link</button>
        </div>
    </div>
</form>
<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#folder_id").select2({placeholder:"Select Folder"});
        $("#module_id").select2({placeholder:"Select Module"});
        $("#menu_id").select2({placeholder:"Select Menu"});

        $("#folder_id").change(function(){
            var folder_id=$(this).val();
            if(folder_id){
                $('#softwareLinkForm').formValidation('removeField', $("#module_id"));
                $.ajax({
                    url: "<?php echo e(route('softAdmin.softwareInternalLinkModule')); ?>",
                    type: "GET",
                    data: {folder_id:folder_id},
                    success: function (data) {
                        dataFilter(data);
                        $("#link_module_id").html(data);
                        $("#module_id").select2({placeholder:"Select Module"});
                        $('#softwareLinkForm').formValidation('addField', $("#module_id"));
                    }
                });
            }
        });
        $("#link_module_id").on('change', '#module_id', function(){
            var module_name=$(this).val();
            if(module_name){
                $('#softwareLinkForm').formValidation('removeField', $("#menu_id"));
                $.ajax({
                    url: "<?php echo e(route('softAdmin.softwareLinkMenu')); ?>",
                    type: "GET",
                    data: {module_name:module_name},
                    success: function (data) {
                        dataFilter(data);
                        $("#link_menu_id").html(data);
                        $("#menu_id").select2({placeholder:"Select Menu"});
                        $('#softwareLinkForm').formValidation('addField', $("#menu_id"));
                    }
                });
            }
        });

    });

    function select2Format(state) {
        if (!state.id) return state.text;
        return "<i class='s16 "+state.id.toLowerCase()+"'></i> " + state.text;
    }
</script><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/softwareInternalLink/update.blade.php ENDPATH**/ ?>