<div class=row>
    <div class="col-lg-12">
        <!-- col-lg-12 start here -->
        <div id="userAccessPanel" class="panel panel-default panelMove showControls toggle panelClose panelRefresh">
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title">
                    <div class="checkbox-custom mt0">
                        <input id="checkboxAll" type="checkbox" <?php if($checkAll): ?><?php echo e('checked'); ?><?php endif; ?>>
                        <label for="checkboxAll">
                            <i class="s12 pull-left mr15 <?php echo e($software_module->module_icon); ?>" style="margin-top: 2px"></i>
                            <strong><?php echo e($software_module->module_name); ?></strong>
                        </label>
                    </div>
                </h4>
            </div>
            <div class="panel-body">
                <form id="user-access-form">
                    <div class="panel-group accordion accordion-checkBox">
                        <?php
                        $check_array = array();
                        $parent_ids = $software_menus->pluck('parent_id');
                        $menu_list_1 = $parent_ids->filter(function($item) { return $item==0; })->all();
                        ?>
                        <?php $__currentLoopData = $menu_list_1; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_1=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                            $menu_1 = $software_menus[$menu_key_1];
                            $menu_list_2 = $parent_ids->filter(function($item) use ($menu_1) { return $item==$menu_1->id; })->all();
                            ?>
                            <div id="<?php echo e('check-area'.$menu_1->id); ?>" class="panel panel-default">
                                <div class=panel-heading>
                                    <h4 class=panel-title>
                                        <div class="accordion-toggle checkbox-custom mt0">
                                            <input id="<?php echo e('checkbox'.$menu_1->id); ?>" name="menu[]" type="checkbox" value="<?php echo e($menu_1->id); ?>" <?php if(!empty($menu_1->menu_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-all'.$menu_1->id); ?> chk-box">
                                            <label for="<?php echo e('checkbox'.$menu_1->id); ?>">
                                                <i class="s12 pull-left <?php echo e(!empty($menu_1->menu_icon)?$menu_1->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                                                <strong><?php echo e($menu_1->menu_name); ?></strong>
                                            </label>
                                        </div>
                                        <?php if((!empty($menu_1->internal_links) && count($menu_1->internal_links)>0) || !empty($menu_list_2)): ?>
                                            <a class="accordion-toggle collapsed" href="<?php echo e('#collapse'.$menu_1->id); ?>" data-toggle="collapse">
                                                <i class="icomoon-icon-plus s12"></i>
                                                <i class="icomoon-icon-minus s12"></i>
                                            </a>
                                        <?php endif; ?>
                                    </h4>
                                </div>
                                <?php if((!empty($menu_1->internal_links) && count($menu_1->internal_links)>0) || !empty($menu_list_2)): ?>
                                    <?php $check_array['check-area'.$menu_1->id] = ['parent' => 'check-all'.$menu_1->id, 'child' => 'check'.$menu_1->id]; ?>
                                    <div id="<?php echo e('collapse'.$menu_1->id); ?>" class="panel-collapse collapse">
                                        <div class=panel-body>
                                            <?php if(!empty($menu_1->internal_links) && count($menu_1->internal_links)>0): ?>
                                                <?php $check_array['check-link-area'.$menu_1->id] = ['parent' => 'check-link-all'.$menu_1->id, 'child' => 'check-link'.$menu_1->id]; ?>
                                                <div id="<?php echo e('check-link-area'.$menu_1->id); ?>" class="row">
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-12">
                                                            <div class="page-header mt0 mb5 pb5">
                                                                <div class="checkbox-custom mt0">
                                                                    <input id="<?php echo e('checkbox-all'.$menu_1->id); ?>" type="checkbox" <?php if(!(collect($menu_1->internal_links->pluck('link_access'))->contains(''))): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link-all'.$menu_1->id); ?> <?php echo e('check'.$menu_1->id); ?> chk-box">
                                                                    <label for="<?php echo e('checkbox-all'.$menu_1->id); ?>">
                                                                        <strong>Select All</strong>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <?php $__currentLoopData = $menu_1->internal_links->chunk(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_links): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="row">
                                                                    <?php $__currentLoopData = $internal_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="col-lg-3 col-md-3">
                                                                            <div class="checkbox-custom">
                                                                                <input id="<?php echo e('link-checkbox'.$internal_link->id); ?>" name="internal_link[]" type="checkbox" value="<?php echo e($internal_link->id); ?>" <?php if(!empty($internal_link->link_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link'.$menu_1->id); ?> <?php echo e('check'.$menu_1->id); ?> chk-box">
                                                                                <label for="<?php echo e('link-checkbox'.$internal_link->id); ?>"><?php echo e($internal_link->link_name); ?></label>
                                                                            </div>
                                                                        </div>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <?php if((!empty($menu_1->internal_links) && count($menu_1->internal_links)>0) && !empty($menu_list_2)): ?><br><?php endif; ?>
                                            <?php if(!empty($menu_list_2)): ?>
                                                <?php $__currentLoopData = $menu_list_2; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_2=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                    $menu_2 = $software_menus[$menu_key_2];
                                                    $menu_list_3 = $parent_ids->filter(function($item) use ($menu_2) { return $item==$menu_2->id; })->all();
                                                    ?>
                                                    <div id="<?php echo e('check-area'.$menu_2->id); ?>" class="panel panel-default">
                                                        <div class=panel-heading>
                                                            <h4 class=panel-title>
                                                                <div class="accordion-toggle checkbox-custom mt0">
                                                                    <input id="<?php echo e('checkbox'.$menu_2->id); ?>" name="menu[]" type="checkbox" value="<?php echo e($menu_2->id); ?>" <?php if(!empty($menu_2->menu_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-all'.$menu_2->id); ?> <?php echo e('check'.$menu_1->id); ?> chk-box">
                                                                    <label for="<?php echo e('checkbox'.$menu_2->id); ?>">
                                                                        <i class="s12 pull-left <?php echo e(!empty($menu_2->menu_icon)?$menu_2->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                                                                        <strong><?php echo e($menu_2->menu_name); ?></strong>
                                                                    </label>
                                                                </div>
                                                                <?php if((!empty($menu_2->internal_links) && count($menu_2->internal_links)>0) || !empty($menu_list_3)): ?>
                                                                    <a class="accordion-toggle collapsed" href="<?php echo e('#collapse'.$menu_2->id); ?>" data-toggle="collapse">
                                                                        <i class="icomoon-icon-plus s12"></i>
                                                                        <i class="icomoon-icon-minus s12"></i>
                                                                    </a>
                                                                <?php endif; ?>
                                                            </h4>
                                                        </div>
                                                        <?php if((!empty($menu_2->internal_links) && count($menu_2->internal_links)>0) || !empty($menu_list_3)): ?>
                                                            <?php $check_array['check-area'.$menu_2->id] = ['parent' => 'check-all'.$menu_2->id, 'child' => 'check'.$menu_2->id]; ?>
                                                            <div id="<?php echo e('collapse'.$menu_2->id); ?>" class="panel-collapse collapse">
                                                                <div class=panel-body>
                                                                    <?php if(!empty($menu_2->internal_links) && count($menu_2->internal_links)>0): ?>
                                                                        <?php $check_array['check-link-area'.$menu_2->id] = ['parent' => 'check-link-all'.$menu_2->id, 'child' => 'check-link'.$menu_2->id]; ?>
                                                                        <div id="<?php echo e('check-link-area'.$menu_2->id); ?>" class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="col-lg-12">
                                                                                    <div class="page-header mt0 mb5 pb5">
                                                                                        <div class="checkbox-custom mt0">
                                                                                            <input id="<?php echo e('checkbox-all'.$menu_2->id); ?>" type="checkbox" <?php if(!(collect($menu_2->internal_links->pluck('link_access'))->contains(''))): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link-all'.$menu_2->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> chk-box">
                                                                                            <label for="<?php echo e('checkbox-all'.$menu_2->id); ?>">
                                                                                                <strong>Select All</strong>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    <?php $__currentLoopData = $menu_2->internal_links->chunk(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_links): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                        <div class="row">
                                                                                            <?php $__currentLoopData = $internal_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                <div class="col-lg-3 col-md-3">
                                                                                                    <div class="checkbox-custom">
                                                                                                        <input id="<?php echo e('link-checkbox'.$internal_link->id); ?>" name="internal_link[]" type="checkbox" value="<?php echo e($internal_link->id); ?>" <?php if(!empty($internal_link->link_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link'.$menu_2->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> chk-box">
                                                                                                        <label for="<?php echo e('link-checkbox'.$internal_link->id); ?>"><?php echo e($internal_link->link_name); ?></label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                        </div>
                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                    <?php if((!empty($menu_2->internal_links) && count($menu_2->internal_links)>0) && !empty($menu_list_3)): ?><br><?php endif; ?>
                                                                    <?php if(!empty($menu_list_3)): ?>
                                                                        <?php $__currentLoopData = $menu_list_3; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_3=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php
                                                                            $menu_3 = $software_menus[$menu_key_3];
                                                                            $menu_list_4 = $parent_ids->filter(function($item) use ($menu_3) { return $item==$menu_3->id; })->all();
                                                                            ?>
                                                                            <div id="<?php echo e('check-area'.$menu_3->id); ?>" class="panel panel-default">
                                                                                <div class=panel-heading>
                                                                                    <h4 class=panel-title>
                                                                                        <div class="accordion-toggle checkbox-custom mt0">
                                                                                            <input id="<?php echo e('checkbox'.$menu_3->id); ?>" name="menu[]" type="checkbox" value="<?php echo e($menu_3->id); ?>" <?php if(!empty($menu_3->menu_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-all'.$menu_3->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> chk-box">
                                                                                            <label for="<?php echo e('checkbox'.$menu_3->id); ?>">
                                                                                                <i class="s12 pull-left <?php echo e(!empty($menu_3->menu_icon)?$menu_3->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                                                                                                <strong><?php echo e($menu_3->menu_name); ?></strong>
                                                                                            </label>
                                                                                        </div>
                                                                                        <?php if((!empty($menu_3->internal_links) && count($menu_3->internal_links)>0) || !empty($menu_list_4)): ?>
                                                                                            <a class="accordion-toggle collapsed" href="<?php echo e('#collapse'.$menu_3->id); ?>" data-toggle="collapse">
                                                                                                <i class="icomoon-icon-plus s12"></i>
                                                                                                <i class="icomoon-icon-minus s12"></i>
                                                                                            </a>
                                                                                        <?php endif; ?>
                                                                                    </h4>
                                                                                </div>
                                                                                <?php if((!empty($menu_3->internal_links) && count($menu_3->internal_links)>0) || !empty($menu_list_4)): ?>
                                                                                    <?php $check_array['check-area'.$menu_3->id] = ['parent' => 'check-all'.$menu_3->id, 'child' => 'check'.$menu_3->id]; ?>
                                                                                    <div id="<?php echo e('collapse'.$menu_3->id); ?>" class="panel-collapse collapse">
                                                                                        <div class=panel-body>
                                                                                            <?php if(!empty($menu_3->internal_links) && count($menu_3->internal_links)>0): ?>
                                                                                                <?php $check_array['check-link-area'.$menu_3->id] = ['parent' => 'check-link-all'.$menu_3->id, 'child' => 'check-link'.$menu_3->id]; ?>
                                                                                                <div id="<?php echo e('check-link-area'.$menu_3->id); ?>" class="row">
                                                                                                    <div class="col-lg-12">
                                                                                                        <div class="col-lg-12">
                                                                                                            <div class="page-header mt0 mb5 pb5">
                                                                                                                <div class="checkbox-custom mt0">
                                                                                                                    <input id="<?php echo e('checkbox-all'.$menu_3->id); ?>" type="checkbox" <?php if(!(collect($menu_3->internal_links->pluck('link_access'))->contains(''))): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link-all'.$menu_3->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> <?php echo e('check'.$menu_3->id); ?> chk-box">
                                                                                                                    <label for="<?php echo e('checkbox-all'.$menu_3->id); ?>">
                                                                                                                        <strong>Select All</strong>
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <?php $__currentLoopData = $menu_3->internal_links->chunk(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_links): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                <div class="row">
                                                                                                                    <?php $__currentLoopData = $internal_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                        <div class="col-lg-3 col-md-3">
                                                                                                                            <div class="checkbox-custom">
                                                                                                                                <input id="<?php echo e('link-checkbox'.$internal_link->id); ?>" name="internal_link[]" type="checkbox" value="<?php echo e($internal_link->id); ?>" <?php if(!empty($internal_link->link_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link'.$menu_3->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> <?php echo e('check'.$menu_3->id); ?> chk-box">
                                                                                                                                <label for="<?php echo e('link-checkbox'.$internal_link->id); ?>"><?php echo e($internal_link->link_name); ?></label>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                </div>
                                                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            <?php endif; ?>
                                                                                            <?php if(!empty($menu_list_4)): ?>
                                                                                                <?php $__currentLoopData = $menu_list_4; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_4=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                    <?php
                                                                                                    $menu_4 = $software_menus[$menu_key_4];
                                                                                                    $menu_list_5 = $parent_ids->filter(function($item) use ($menu_4) { return $item==$menu_4->id; })->all();
                                                                                                    ?>
                                                                                                    <div id="<?php echo e('check-area'.$menu_4->id); ?>" class="panel panel-default">
                                                                                                        <div class=panel-heading>
                                                                                                            <h4 class=panel-title>
                                                                                                                <div class="accordion-toggle checkbox-custom mt0">
                                                                                                                    <input id="<?php echo e('checkbox'.$menu_4->id); ?>" name="menu[]" type="checkbox" value="<?php echo e($menu_4->id); ?>" <?php if(!empty($menu_4->menu_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-all'.$menu_4->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> <?php echo e('check'.$menu_3->id); ?> chk-box">
                                                                                                                    <label for="<?php echo e('checkbox'.$menu_4->id); ?>">
                                                                                                                        <i class="s12 pull-left <?php echo e(!empty($menu_4->menu_icon)?$menu_4->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                                                                                                                        <strong><?php echo e($menu_4->menu_name); ?></strong>
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                                <?php if((!empty($menu_4->internal_links) && count($menu_4->internal_links)>0) || !empty($menu_list_5)): ?>
                                                                                                                    <a class="accordion-toggle collapsed" href="<?php echo e('#collapse'.$menu_4->id); ?>" data-toggle="collapse">
                                                                                                                        <i class="icomoon-icon-plus s12"></i>
                                                                                                                        <i class="icomoon-icon-minus s12"></i>
                                                                                                                    </a>
                                                                                                                <?php endif; ?>
                                                                                                            </h4>
                                                                                                        </div>
                                                                                                        <?php if((!empty($menu_4->internal_links) && count($menu_4->internal_links)>0) || !empty($menu_list_5)): ?>
                                                                                                            <?php $check_array['check-area'.$menu_4->id] = ['parent' => 'check-all'.$menu_4->id, 'child' => 'check'.$menu_4->id]; ?>
                                                                                                            <div id="<?php echo e('collapse'.$menu_4->id); ?>" class="panel-collapse collapse">
                                                                                                                <div class=panel-body>
                                                                                                                    <?php if(!empty($menu_4->internal_links) && count($menu_4->internal_links)>0): ?>
                                                                                                                        <?php $check_array['check-link-area'.$menu_4->id] = ['parent' => 'check-link-all'.$menu_4->id, 'child' => 'check-link'.$menu_4->id]; ?>
                                                                                                                        <div id="<?php echo e('check-link-area'.$menu_4->id); ?>" class="row">
                                                                                                                            <div class="col-lg-12">
                                                                                                                                <div class="col-lg-12">
                                                                                                                                    <div class="page-header mt0 mb5 pb5">
                                                                                                                                        <div class="checkbox-custom mt0">
                                                                                                                                            <input id="<?php echo e('checkbox-all'.$menu_4->id); ?>" type="checkbox" <?php if(!(collect($menu_4->internal_links->pluck('link_access'))->contains(''))): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link-all'.$menu_4->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> <?php echo e('check'.$menu_3->id); ?> <?php echo e('check'.$menu_4->id); ?> chk-box">
                                                                                                                                            <label for="<?php echo e('checkbox-all'.$menu_4->id); ?>">
                                                                                                                                                <strong>Select All</strong>
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    <?php $__currentLoopData = $menu_4->internal_links->chunk(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_links): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                        <div class="row">
                                                                                                                                            <?php $__currentLoopData = $internal_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                <div class="col-lg-3 col-md-3">
                                                                                                                                                    <div class="checkbox-custom">
                                                                                                                                                        <input id="<?php echo e('link-checkbox'.$internal_link->id); ?>" name="internal_link[]" type="checkbox" value="<?php echo e($internal_link->id); ?>" <?php if(!empty($internal_link->link_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link'.$menu_4->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> <?php echo e('check'.$menu_3->id); ?> <?php echo e('check'.$menu_4->id); ?> chk-box">
                                                                                                                                                        <label for="<?php echo e('link-checkbox'.$internal_link->id); ?>"><?php echo e($internal_link->link_name); ?></label>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                        </div>
                                                                                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    <?php endif; ?>
                                                                                                                    <?php if((!empty($menu_4->internal_links) && count($menu_4->internal_links)>0) && !empty($menu_list_5)): ?><br><?php endif; ?>
                                                                                                                    <?php if(!empty($menu_list_5)): ?>
                                                                                                                        <?php $__currentLoopData = $menu_list_5; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menu_key_5=>$val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                            <?php
                                                                                                                            $menu_5 = $software_menus[$menu_key_5];
                                                                                                                            ?>
                                                                                                                            <div id="<?php echo e('check-area'.$menu_5->id); ?>" class="panel panel-default">
                                                                                                                                <div class=panel-heading>
                                                                                                                                    <h4 class=panel-title>
                                                                                                                                        <div class="accordion-toggle checkbox-custom mt0">
                                                                                                                                            <input id="<?php echo e('checkbox'.$menu_5->id); ?>" name="menu[]" type="checkbox" value="<?php echo e($menu_5->id); ?>" <?php if(!empty($menu_5->menu_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-all'.$menu_5->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> <?php echo e('check'.$menu_3->id); ?> <?php echo e('check'.$menu_4->id); ?> chk-box">
                                                                                                                                            <label for="<?php echo e('checkbox'.$menu_5->id); ?>">
                                                                                                                                                <i class="s12 pull-left <?php echo e(!empty($menu_5->menu_icon)?$menu_5->menu_icon:'icomoon-icon-arrow-right-3'); ?>"></i>
                                                                                                                                                <strong><?php echo e($menu_5->menu_name); ?></strong>
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                        <?php if(!empty($menu_5->internal_links) && count($menu_5->internal_links)>0): ?>
                                                                                                                                            <a class="accordion-toggle collapsed" href="<?php echo e('#collapse'.$menu_5->id); ?>" data-toggle="collapse">
                                                                                                                                                <i class="icomoon-icon-plus s12"></i>
                                                                                                                                                <i class="icomoon-icon-minus s12"></i>
                                                                                                                                            </a>
                                                                                                                                        <?php endif; ?>
                                                                                                                                    </h4>
                                                                                                                                </div>
                                                                                                                                <?php if(!empty($menu_5->internal_links) && count($menu_5->internal_links)>0): ?>
                                                                                                                                    <?php $check_array['check-area'.$menu_5->id] = ['parent' => 'check-all'.$menu_5->id, 'child' => 'check'.$menu_5->id]; ?>
                                                                                                                                    <?php $check_array['check-link-area'.$menu_5->id] = ['parent' => 'check-link-all'.$menu_5->id, 'child' => 'check-link'.$menu_5->id]; ?>
                                                                                                                                    <div id="<?php echo e('collapse'.$menu_5->id); ?>" class="panel-collapse collapse">
                                                                                                                                        <div class=panel-body>
                                                                                                                                            <div id="<?php echo e('check-link-area'.$menu_5->id); ?>" class="row">
                                                                                                                                                <div class="col-lg-12">
                                                                                                                                                    <div class="col-lg-12">
                                                                                                                                                        <div class="page-header mt0 mb5 pb5">
                                                                                                                                                            <div class="checkbox-custom mt0">
                                                                                                                                                                <input id="<?php echo e('checkbox-all'.$menu_5->id); ?>" type="checkbox" <?php if(!(collect($menu_5->internal_links->pluck('link_access'))->contains(''))): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link-all'.$menu_5->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> <?php echo e('check'.$menu_3->id); ?> <?php echo e('check'.$menu_4->id); ?> <?php echo e('check'.$menu_5->id); ?> chk-box">
                                                                                                                                                                <label for="<?php echo e('checkbox-all'.$menu_5->id); ?>">
                                                                                                                                                                    <strong>Select All</strong>
                                                                                                                                                                </label>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                        <?php $__currentLoopData = $menu_5->internal_links->chunk(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_links): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                            <div class="row">
                                                                                                                                                                <?php $__currentLoopData = $internal_links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $internal_link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                                    <div class="col-lg-3 col-md-3">
                                                                                                                                                                        <div class="checkbox-custom">
                                                                                                                                                                            <input id="<?php echo e('link-checkbox'.$internal_link->id); ?>" name="internal_link[]" type="checkbox" value="<?php echo e($internal_link->id); ?>" <?php if(!empty($internal_link->link_access)): ?><?php echo e('checked'); ?><?php endif; ?> class="<?php echo e('check-link'.$menu_5->id); ?> <?php echo e('check'.$menu_1->id); ?> <?php echo e('check'.$menu_2->id); ?> <?php echo e('check'.$menu_3->id); ?> <?php echo e('check'.$menu_4->id); ?> <?php echo e('check'.$menu_5->id); ?> chk-box">
                                                                                                                                                                            <label for="<?php echo e('link-checkbox'.$internal_link->id); ?>"><?php echo e($internal_link->link_name); ?></label>
                                                                                                                                                                        </div>
                                                                                                                                                                    </div>
                                                                                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                            </div>
                                                                                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                <?php endif; ?>
                                                                                                                            </div>
                                                                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                                                    <?php endif; ?>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        <?php endif; ?>
                                                                                                    </div>
                                                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                                            <?php endif; ?>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <div class="row ml0 mt20">
                        <?php echo e(csrf_field()); ?>

                        <button id="user-access-save" class="btn btn-default" type="button">Save Access</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#userAccessPanel").checkAll({
            masterCheckbox: "#checkboxAll",
            otherCheckboxes: ".chk-box"
        });

        <?php
        foreach($check_array as $check_area => $check) {
        ?>
            $("#<?php echo e($check_area); ?>").checkAll({
                masterCheckbox: ".<?php echo e($check['parent']); ?>",
                otherCheckboxes: ".<?php echo e($check['child']); ?>"
            });
        <?php
        }
        ?>

        $("#user-access-save").click(function(){
            preLoader($("#userAccessPanel"));
            var data = $("#user-access-form").serializeArray();
            var module_id = $(".stats-btn-selected").attr('data');
            data[data.length] = {name:'user', value:$("#user_id").val()};
            data[data.length] = {name:'module', value:module_id};
            $.ajax({
                url: "<?php echo e(route('softAdmin.userAccess')); ?>",
                type: "POST",
                data: data,
                success: function (data) {
                    dataFilter(data);
                    $.gritter.add({
                        title: "Done !!!",
                        text: "User Access has successfully done.",
                        time: "",
                        close_icon: "entypo-icon-cancel s12",
                        icon: "icomoon-icon-checkmark-3",
                        class_name: "success-notice"
                    });
                    preLoaderHide($("#userAccessPanel"));
                    userAccessView(module_id);
                }
            });
        });
    });
</script><?php /**PATH /home/eastymap/public_html/resources/views/softAdmin/user/userAccessMenuView.blade.php ENDPATH**/ ?>