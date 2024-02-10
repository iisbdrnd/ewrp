<div class="row mb15">
    <div class="col-lg-4 col-md-4">
        <select required id="folder_id" name="folder_id" class="form-control select2">
            <option value="">Select Folder</option>
            <?php $__currentLoopData = $folder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($folder->id); ?>"><?php echo e($folder->folder_name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>
<input id="project_id" type="hidden" value="<?php echo e($project_info->id); ?>">
<div id="project_access_module_view"></div>
<button class="back-btn btn btn-default" type="button">Back to List</button>

<script type="text/javascript">
    $(document).ready(function(){
        $("#projectList").find(".panel-title").html('<?php echo e($project_info->name); ?>');

        $("#folder_id").select2({placeholder:"Select Folder"});

        $("#folder_id").change(function(){
            var folder_id=$(this).val();
            if(folder_id){
                $.ajax({
                    url: "<?php echo e(route('softAdmin.projectAccessModuleViewByFolder')); ?>",
                    type: "GET",
                    data: {folder_id:folder_id},
                    success: function (data) {
                        $('#project_access_module_view').html(data);
                        $('#menu-view').html("");
                    }
                });
            } else {
                $('#project_access_module_view').html("");
                $('#menu-view').html("");
            }
        });
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/project/projectAccess.blade.php ENDPATH**/ ?>