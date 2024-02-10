<?php $panelTitle = 'Menu Sorting'; ?>
<?php echo $__env->make("panelStart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row mb20">
        <div class="form-group">
            <label class="col-lg-2 col-md-2 control-label" style="text-align:right">Folder</label>
            <div class="col-lg-4 col-md-4">
                <select required id="folder_id" name="folder_id" class="form-control select2">
                    <option value="">None</option>
                    <?php $__currentLoopData = $folder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $folder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($folder->id); ?>"><?php echo e($folder->folder_name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>
    </div>
    <div id="software_module"></div>
    
<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php if(empty($inputData['takeContent'])): ?><div id="menu-view"></div><?php endif; ?>

<script type="text/javascript">
    $('document').ready(function(){
        $("#folder_id").select2({placeholder:"Select Folder"});

        $("#folder_id").change(function(){
            var folder_id=$(this).val();
            if(folder_id){
                $.ajax({
                    url: "<?php echo e(route('softAdmin.menuSortingModuleView')); ?>",
                    type: "GET",
                    data: {folder_id:folder_id},
                    success: function (data) {
                        $('#software_module').html(data);
                        $('#menu-view').html('');
                    }
                });
            }
        });

        $('#software_module').on('click', '.soft-module', function(){
            if(!($(this).hasClass('stats-btn-selected'))) {
                var data = $(this).attr('data');
                $(this).removeClass('pattern').addClass('stats-btn-selected');
                $('.soft-module').not(this).removeClass('stats-btn-selected').addClass('pattern');

                $.ajax({
                    url: "<?php echo e(route('softAdmin.softwareMenuSortingMenuList')); ?>",
                    data: {module_id: data},
                    success: function (data) {
                        dataFilter(data);
                        $('#menu-view').html(data);
                    }
                });
            }
        });
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/softwareMenu/menuSorting.blade.php ENDPATH**/ ?>