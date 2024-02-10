<?php
    $panelTitle = $software_module->module_name;
    $panelId = 'sortingPanel';
?>

<?php echo $__env->make("panelStart", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row ml0">
        <div class=dd id=soft-menu-nestable>
        <ol class=dd-list>
        <?php
            $parent_ids = $software_menus->pluck('parent_id');
            $menu_list_1 = $parent_ids->filter(function($item) { return $item==0; })->all();
        ?>
        <?php $__currentLoopData = $menu_list_1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_1=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $menu_1 = $software_menus[$menu_key_1];
                $menu_list_2 = $parent_ids->filter(function($item) use ($menu_1) { return $item==$menu_1->id; })->all();
            ?>
            <li class="dd-item dd3-item" data-id=<?php echo e($menu_1->id); ?>>
                <i class="dd-handle dd3-handle <?php echo e(!empty($menu_1->menu_icon)?$menu_1->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                <div class=dd3-content><?php echo e($menu_1->menu_name); ?></div>
                <?php if(!empty($menu_list_2)): ?>
                    <ol class=dd-list>
                    <?php $__currentLoopData = $menu_list_2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_2=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                        $menu_2 = $software_menus[$menu_key_2];
                        $menu_list_3 = $parent_ids->filter(function($item) use ($menu_2) { return $item==$menu_2->id; })->all();
                        ?>
                        <li class="dd-item dd3-item" data-id=<?php echo e($menu_2->id); ?>>
                            <i class="dd-handle dd3-handle <?php echo e(!empty($menu_2->menu_icon)?$menu_2->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                            <div class=dd3-content><?php echo e($menu_2->menu_name); ?></div>
                            <?php if(!empty($menu_list_3)): ?>
                                <ol class=dd-list>
                                    <?php $__currentLoopData = $menu_list_3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_3=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $menu_3 = $software_menus[$menu_key_3];
                                        $menu_list_4 = $parent_ids->filter(function($item) use ($menu_3) { return $item==$menu_3->id; })->all();
                                        ?>
                                        <li class="dd-item dd3-item" data-id=<?php echo e($menu_3->id); ?>>
                                            <i class="dd-handle dd3-handle <?php echo e(!empty($menu_3->menu_icon)?$menu_3->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                                            <div class=dd3-content><?php echo e($menu_3->menu_name); ?></div>
                                            <?php if(!empty($menu_list_4)): ?>
                                                <ol class=dd-list>
                                                    <?php $__currentLoopData = $menu_list_4; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_4=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                        $menu_4 = $software_menus[$menu_key_4];
                                                        $menu_list_5 = $parent_ids->filter(function($item) use ($menu_4) { return $item==$menu_4->id; })->all();
                                                        ?>
                                                        <li class="dd-item dd3-item" data-id=<?php echo e($menu_4->id); ?>>
                                                            <i class="dd-handle dd3-handle <?php echo e(!empty($menu_4->menu_icon)?$menu_4->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                                                            <div class=dd3-content><?php echo e($menu_4->menu_name); ?></div>
                                                            <?php if(!empty($menu_list_5)): ?>
                                                                <ol class=dd-list>
                                                                    <?php $__currentLoopData = $menu_list_5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_5=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php
                                                                        $menu_5 = $software_menus[$menu_key_5];
                                                                        ?>
                                                                        <li class="dd-item dd3-item" data-id=<?php echo e($menu_5->id); ?>>
                                                                            <i class="dd-handle dd3-handle <?php echo e(!empty($menu_5->menu_icon)?$menu_5->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                                                                            <div class=dd3-content><?php echo e($menu_5->menu_name); ?></div>
                                                                        </li>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </ol>
                                                            <?php endif; ?>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ol>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ol>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ol>
                <?php endif; ?>
            </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
    </div>
    </div>
    <div class="row ml0 mt20">
        <form id="soft-menu-form">
            <?php echo e(csrf_field()); ?>

            <input id="soft-menu-nestable-output" name="soft_menus" type="hidden">
            <button id="menu-sorting-save" class="btn btn-default" type="button">Save Menu</button>
        </form>
    </div>
<?php echo $__env->make("panelEnd", \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<script type="text/javascript">
    $(document).ready(function() {
        var a = function(a) {
            var b = a.length ? a : $(a.target),
                    c = b.data("output");
            c.val(window.JSON ? window.JSON.stringify(b.nestable("serialize")) : "JSON browser support required for this demo.")
        };

        $("#soft-menu-nestable").nestable().on("change", a), a($("#soft-menu-nestable").data("output", $("#soft-menu-nestable-output")));

        $("#menu-sorting-save").click(function(){
            preLoader($("#sortingPanel"));
            var data = $("#soft-menu-form").serializeArray();
            $.ajax({
                url: "<?php echo e(route('softAdmin.softwareMenuSorting')); ?>",
                type: "POST",
                data: data,
                success: function (data) {
                    dataFilter(data);
                    $.gritter.add({
                        title: "Done !!!",
                        text: "Menu sorting has successfully done.",
                        time: "",
                        close_icon: "entypo-icon-cancel s12",
                        icon: "icomoon-icon-checkmark-3",
                        class_name: "success-notice"
                    });
                    preLoaderHide($("#sortingPanel"));
                }
            });
        });
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/softwareMenu/menuSortingMenuList.blade.php ENDPATH**/ ?>