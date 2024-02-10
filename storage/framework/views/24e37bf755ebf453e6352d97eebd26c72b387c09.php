<form type="create" id="softwareMenuForm" data-fv-excluded="" callBack="formRefresh" panelTitle="Menu Create" class="form-load form-horizontal group-border stripped">
    <?php echo e(csrf_field()); ?>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Folder</label>
        <div class="col-lg-10 col-md-9">
            <select required id="folder_id" class="form-control">
                <option value="">None</option>
                <?php $__currentLoopData = $folder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($folder->id); ?>"><?php echo e($folder->folder_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Module</label>
        <div class="col-lg-10 col-md-9">
            <div id="link_module_id">
                <select required id="module_id" name="module_id" class="form-control">
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Menu Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="menu_name" placeholder="Menu Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Icon</label>
        <div class="col-lg-10 col-md-9">
            <select id="menu_icon" name="menu_icon" class="form-control">
                <option value="">None</option>
                <?php $__currentLoopData = $icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $icon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($icon->class_name); ?>"><?php echo e($icon->class_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
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
    <div class="form-group" id="link_route">
        <div id="route_name_field">
            <label class="col-lg-2 col-md-3 control-label required">Route</label>
            <div class="col-lg-10 col-md-9">
                <input required data-fv-row="#route_name_field" name="route" id="route" placeholder="Route" class="form-control">
            </div>
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
            <button type="submit" class="btn btn-default ml15">Create Menu</button>
        </div>
    </div>
</form>
<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $('#folder_id').select2({placeholder: "Select Folder"});
        $('#module_id').select2({placeholder: "Select Module"});
        $("#menu_icon").select2({
            formatResult: select2Format,
            formatSelection: select2Format,
            escapeMarkup: function(m) { return m; }
        });

        $("#folder_id").change(function(){
            var folder_id=$(this).val();
            if(folder_id){
                $('#softwareMenuForm').formValidation('removeField', $("#module_id"));
                $.ajax({
                    url: "<?php echo e(route('softAdmin.softwareLinkModule')); ?>",
                    type: "GET",
                    data: {folder_id:folder_id},
                    success: function (data) {
                        dataFilter(data);
                        $("#link_module_id").html(data);
                        $("#module_id").select2({placeholder:"Select Module"});
                        $('#softwareMenuForm').formValidation('addField', $("#module_id"));
                    }
                });
            }
        });

        $("#resource").change(function(){
            if($(this).is(':checked')) {
                $('#softwareMenuForm').formValidation('removeField', $("#route"));
                $("#link_route").html('<div id="link_name_field"><label class="col-lg-2 col-md-3 control-label">Main Link</label><div class="col-lg-4 col-md-3"><input required data-fv-row="#link_name_field" name="link_name" id="link_name" placeholder="Main Link" class="form-control"></div></div><label class="col-lg-2 col-md-3 control-label">Resource Function</label><div class="col-lg-4 col-md-3"><select data-fv-icon="false" name="resource_function" class="form-control"><option value="index">index</option><option value="create">create</option><option value="show">show</option><option value="edit">edit</option></select></div>');
                $('#softwareMenuForm').formValidation('addField', $("#link_name"));
            } else {
                $('#softwareMenuForm').formValidation('removeField', $("#link_name"));
                $("#link_route").html('<div id="route_name_field"><label class="col-lg-2 col-md-3 control-label">Route</label><div class="col-lg-10 col-md-9"><input required data-fv-row="#route_name_field" name="route" id="route" placeholder="Route" class="form-control"></div></div>');
                $('#softwareMenuForm').formValidation('addField', $("#route"));
            }
        });
    });

    function formRefresh() {
        if($("#route").length==0) {
            $('#softwareMenuForm').formValidation('removeField', $("#link_name"));
            $("#link_route").html('<div id="route_name_field"><label class="col-lg-2 col-md-3 control-label">Route</label><div class="col-lg-10 col-md-9"><input required data-fv-row="#route_name_field" name="route" id="route" placeholder="Route" class="form-control"></div></div>');
            $('#softwareMenuForm').formValidation('addField', $("#route"));
        }
    }

    function select2Format(state) {
        if (!state.id) return state.text;
        return "<i class='s16 "+state.id.toLowerCase()+"'></i> " + state.text;
    }
</script><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/softwareMenu/create.blade.php ENDPATH**/ ?>