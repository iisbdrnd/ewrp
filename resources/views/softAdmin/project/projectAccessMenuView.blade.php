<div class=row>
    <div class="col-lg-12">
        <!-- col-lg-12 start here -->
        <div id="projectAccessPanel" class="panel panel-default panelMove showControls toggle panelClose panelRefresh">
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title">
                    <div class="checkbox-custom mt0">
                        <input id="checkboxAll" type="checkbox" @if($checkAll){{'checked'}}@endif>
                        <label for="checkboxAll">
                            <i class="s12 pull-left mr15 {{$software_module->module_icon}}" style="margin-top: 2px"></i>
                            <strong>{{$software_module->module_name}}</strong>
                        </label>
                    </div>
                </h4>
            </div>
            <div class="panel-body">
                <form id="project-access-form">
                    <div class="panel-group accordion accordion-checkBox">
                        <?php
                        $check_array = array();
                        $parent_ids = $software_menus->pluck('parent_id');
                        $menu_list_1 = $parent_ids->filter(function($item) { return $item==0; })->all();
                        ?>
                        @foreach($menu_list_1 as $menu_key_1=>$val)
                            <?php
                            $menu_1 = $software_menus[$menu_key_1];
                            $menu_list_2 = $parent_ids->filter(function($item) use ($menu_1) { return $item==$menu_1->id; })->all();
                            ?>
                            <div id="{{'check-area'.$menu_1->id}}" class="panel panel-default">
                                <div class=panel-heading>
                                    <h4 class=panel-title>
                                        <div class="accordion-toggle checkbox-custom mt0">
                                            <input id="{{'checkbox'.$menu_1->id}}" name="menu[]" type="checkbox" value="{{$menu_1->id}}" @if(!empty($menu_1->menu_access)){{'checked'}}@endif class="{{'check-all'.$menu_1->id}} chk-box">
                                            <label for="{{'checkbox'.$menu_1->id}}">
                                                <i class="s12 pull-left {{!empty($menu_1->menu_icon)?$menu_1->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                                                <strong>{{$menu_1->menu_name}}</strong>
                                            </label>
                                        </div>
                                        @if((!empty($menu_1->internal_links) && count($menu_1->internal_links)>0) || !empty($menu_list_2))
                                            <a class="accordion-toggle collapsed" href="{{'#collapse'.$menu_1->id}}" data-toggle="collapse">
                                                <i class="icomoon-icon-plus s12"></i>
                                                <i class="icomoon-icon-minus s12"></i>
                                            </a>
                                        @endif
                                    </h4>
                                </div>
                                @if((!empty($menu_1->internal_links) && count($menu_1->internal_links)>0) || !empty($menu_list_2))
                                    <?php $check_array['check-area'.$menu_1->id] = ['parent' => 'check-all'.$menu_1->id, 'child' => 'check'.$menu_1->id]; ?>
                                    <div id="{{'collapse'.$menu_1->id}}" class="panel-collapse collapse">
                                        <div class=panel-body>
                                            @if(!empty($menu_1->internal_links) && count($menu_1->internal_links)>0)
                                                <?php $check_array['check-link-area'.$menu_1->id] = ['parent' => 'check-link-all'.$menu_1->id, 'child' => 'check-link'.$menu_1->id]; ?>
                                                <div id="{{'check-link-area'.$menu_1->id}}" class="row">
                                                    <div class="col-lg-12">
                                                        <div class="col-lg-12">
                                                            <div class="page-header mt0 mb5 pb5">
                                                                <div class="checkbox-custom mt0">
                                                                    <input id="{{'checkbox-all'.$menu_1->id}}" type="checkbox" @if(!(collect($menu_1->internal_links->pluck('link_access'))->contains(''))){{'checked'}}@endif class="{{'check-link-all'.$menu_1->id}} {{'check'.$menu_1->id}} chk-box">
                                                                    <label for="{{'checkbox-all'.$menu_1->id}}">
                                                                        <strong>Select All</strong>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            @foreach($menu_1->internal_links->chunk(4) as $internal_links)
                                                                <div class="row">
                                                                    @foreach($internal_links as $internal_link)
                                                                        <div class="col-lg-3 col-md-3">
                                                                            <div class="checkbox-custom">
                                                                                <input id="{{'link-checkbox'.$internal_link->id}}" name="internal_link[]" type="checkbox" value="{{$internal_link->id}}" @if(!empty($internal_link->link_access)){{'checked'}}@endif class="{{'check-link'.$menu_1->id}} {{'check'.$menu_1->id}} chk-box">
                                                                                <label for="{{'link-checkbox'.$internal_link->id}}">{{$internal_link->link_name}}</label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            @if((!empty($menu_1->internal_links) && count($menu_1->internal_links)>0) && !empty($menu_list_2))<br>@endif
                                            @if(!empty($menu_list_2))
                                                @foreach($menu_list_2 as $menu_key_2=>$val)
                                                    <?php
                                                    $menu_2 = $software_menus[$menu_key_2];
                                                    $menu_list_3 = $parent_ids->filter(function($item) use ($menu_2) { return $item==$menu_2->id; })->all();
                                                    ?>
                                                    <div id="{{'check-area'.$menu_2->id}}" class="panel panel-default">
                                                        <div class=panel-heading>
                                                            <h4 class=panel-title>
                                                                <div class="accordion-toggle checkbox-custom mt0">
                                                                    <input id="{{'checkbox'.$menu_2->id}}" name="menu[]" type="checkbox" value="{{$menu_2->id}}" @if(!empty($menu_2->menu_access)){{'checked'}}@endif class="{{'check-all'.$menu_2->id}} {{'check'.$menu_1->id}} chk-box">
                                                                    <label for="{{'checkbox'.$menu_2->id}}">
                                                                        <i class="s12 pull-left {{!empty($menu_2->menu_icon)?$menu_2->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                                                                        <strong>{{$menu_2->menu_name}}</strong>
                                                                    </label>
                                                                </div>
                                                                @if((!empty($menu_2->internal_links) && count($menu_2->internal_links)>0) || !empty($menu_list_3))
                                                                    <a class="accordion-toggle collapsed" href="{{'#collapse'.$menu_2->id}}" data-toggle="collapse">
                                                                        <i class="icomoon-icon-plus s12"></i>
                                                                        <i class="icomoon-icon-minus s12"></i>
                                                                    </a>
                                                                @endif
                                                            </h4>
                                                        </div>
                                                        @if((!empty($menu_2->internal_links) && count($menu_2->internal_links)>0) || !empty($menu_list_3))
                                                            <?php $check_array['check-area'.$menu_2->id] = ['parent' => 'check-all'.$menu_2->id, 'child' => 'check'.$menu_2->id]; ?>
                                                            <div id="{{'collapse'.$menu_2->id}}" class="panel-collapse collapse">
                                                                <div class=panel-body>
                                                                    @if(!empty($menu_2->internal_links) && count($menu_2->internal_links)>0)
                                                                        <?php $check_array['check-link-area'.$menu_2->id] = ['parent' => 'check-link-all'.$menu_2->id, 'child' => 'check-link'.$menu_2->id]; ?>
                                                                        <div id="{{'check-link-area'.$menu_2->id}}" class="row">
                                                                            <div class="col-lg-12">
                                                                                <div class="col-lg-12">
                                                                                    <div class="page-header mt0 mb5 pb5">
                                                                                        <div class="checkbox-custom mt0">
                                                                                            <input id="{{'checkbox-all'.$menu_2->id}}" type="checkbox" @if(!(collect($menu_2->internal_links->pluck('link_access'))->contains(''))){{'checked'}}@endif class="{{'check-link-all'.$menu_2->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} chk-box">
                                                                                            <label for="{{'checkbox-all'.$menu_2->id}}">
                                                                                                <strong>Select All</strong>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                    @foreach($menu_2->internal_links->chunk(4) as $internal_links)
                                                                                        <div class="row">
                                                                                            @foreach($internal_links as $internal_link)
                                                                                                <div class="col-lg-3 col-md-3">
                                                                                                    <div class="checkbox-custom">
                                                                                                        <input id="{{'link-checkbox'.$internal_link->id}}" name="internal_link[]" type="checkbox" value="{{$internal_link->id}}" @if(!empty($internal_link->link_access)){{'checked'}}@endif class="{{'check-link'.$menu_2->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} chk-box">
                                                                                                        <label for="{{'link-checkbox'.$internal_link->id}}">{{$internal_link->link_name}}</label>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endforeach
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                    @if((!empty($menu_2->internal_links) && count($menu_2->internal_links)>0) && !empty($menu_list_3))<br>@endif
                                                                    @if(!empty($menu_list_3))
                                                                        @foreach($menu_list_3 as $menu_key_3=>$val)
                                                                            <?php
                                                                            $menu_3 = $software_menus[$menu_key_3];
                                                                            $menu_list_4 = $parent_ids->filter(function($item) use ($menu_3) { return $item==$menu_3->id; })->all();
                                                                            ?>
                                                                            <div id="{{'check-area'.$menu_3->id}}" class="panel panel-default">
                                                                                <div class=panel-heading>
                                                                                    <h4 class=panel-title>
                                                                                        <div class="accordion-toggle checkbox-custom mt0">
                                                                                            <input id="{{'checkbox'.$menu_3->id}}" name="menu[]" type="checkbox" value="{{$menu_3->id}}" @if(!empty($menu_3->menu_access)){{'checked'}}@endif class="{{'check-all'.$menu_3->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} chk-box">
                                                                                            <label for="{{'checkbox'.$menu_3->id}}">
                                                                                                <i class="s12 pull-left {{!empty($menu_3->menu_icon)?$menu_3->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                                                                                                <strong>{{$menu_3->menu_name}}</strong>
                                                                                            </label>
                                                                                        </div>
                                                                                        @if((!empty($menu_3->internal_links) && count($menu_3->internal_links)>0) || !empty($menu_list_4))
                                                                                            <a class="accordion-toggle collapsed" href="{{'#collapse'.$menu_3->id}}" data-toggle="collapse">
                                                                                                <i class="icomoon-icon-plus s12"></i>
                                                                                                <i class="icomoon-icon-minus s12"></i>
                                                                                            </a>
                                                                                        @endif
                                                                                    </h4>
                                                                                </div>
                                                                                @if((!empty($menu_3->internal_links) && count($menu_3->internal_links)>0) || !empty($menu_list_4))
                                                                                    <?php $check_array['check-area'.$menu_3->id] = ['parent' => 'check-all'.$menu_3->id, 'child' => 'check'.$menu_3->id]; ?>
                                                                                    <div id="{{'collapse'.$menu_3->id}}" class="panel-collapse collapse">
                                                                                        <div class=panel-body>
                                                                                            @if(!empty($menu_3->internal_links) && count($menu_3->internal_links)>0)
                                                                                                <?php $check_array['check-link-area'.$menu_3->id] = ['parent' => 'check-link-all'.$menu_3->id, 'child' => 'check-link'.$menu_3->id]; ?>
                                                                                                <div id="{{'check-link-area'.$menu_3->id}}" class="row">
                                                                                                    <div class="col-lg-12">
                                                                                                        <div class="col-lg-12">
                                                                                                            <div class="page-header mt0 mb5 pb5">
                                                                                                                <div class="checkbox-custom mt0">
                                                                                                                    <input id="{{'checkbox-all'.$menu_3->id}}" type="checkbox" @if(!(collect($menu_3->internal_links->pluck('link_access'))->contains(''))){{'checked'}}@endif class="{{'check-link-all'.$menu_3->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} {{'check'.$menu_3->id}} chk-box">
                                                                                                                    <label for="{{'checkbox-all'.$menu_3->id}}">
                                                                                                                        <strong>Select All</strong>
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                            @foreach($menu_3->internal_links->chunk(4) as $internal_links)
                                                                                                                <div class="row">
                                                                                                                    @foreach($internal_links as $internal_link)
                                                                                                                        <div class="col-lg-3 col-md-3">
                                                                                                                            <div class="checkbox-custom">
																																<input id="{{'link-checkbox'.$internal_link->id}}" name="internal_link[]" type="checkbox" value="{{$internal_link->id}}" @if(!empty($internal_link->link_access)){{'checked'}}@endif class="{{'check-link'.$menu_3->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} {{'check'.$menu_3->id}} chk-box">
                                                                                                                                <label for="{{'link-checkbox'.$internal_link->id}}">{{$internal_link->link_name}}</label>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    @endforeach
                                                                                                                </div>
                                                                                                            @endforeach
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            @endif
                                                                                            @if(!empty($menu_list_4))
                                                                                                @foreach($menu_list_4 as $menu_key_4=>$val)
                                                                                                    <?php
                                                                                                    $menu_4 = $software_menus[$menu_key_4];
                                                                                                    $menu_list_5 = $parent_ids->filter(function($item) use ($menu_4) { return $item==$menu_4->id; })->all();
                                                                                                    ?>
                                                                                                    <div id="{{'check-area'.$menu_4->id}}" class="panel panel-default">
                                                                                                        <div class=panel-heading>
                                                                                                            <h4 class=panel-title>
                                                                                                                <div class="accordion-toggle checkbox-custom mt0">
                                                                                                                    <input id="{{'checkbox'.$menu_4->id}}" name="menu[]" type="checkbox" value="{{$menu_4->id}}" @if(!empty($menu_4->menu_access)){{'checked'}}@endif class="{{'check-all'.$menu_4->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} {{'check'.$menu_3->id}} chk-box">
                                                                                                                    <label for="{{'checkbox'.$menu_4->id}}">
                                                                                                                        <i class="s12 pull-left {{!empty($menu_4->menu_icon)?$menu_4->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                                                                                                                        <strong>{{$menu_4->menu_name}}</strong>
                                                                                                                    </label>
                                                                                                                </div>
                                                                                                                @if((!empty($menu_4->internal_links) && count($menu_4->internal_links)>0) || !empty($menu_list_5))
                                                                                                                    <a class="accordion-toggle collapsed" href="{{'#collapse'.$menu_4->id}}" data-toggle="collapse">
                                                                                                                        <i class="icomoon-icon-plus s12"></i>
                                                                                                                        <i class="icomoon-icon-minus s12"></i>
                                                                                                                    </a>
                                                                                                                @endif
                                                                                                            </h4>
                                                                                                        </div>
                                                                                                        @if((!empty($menu_4->internal_links) && count($menu_4->internal_links)>0) || !empty($menu_list_5))
                                                                                                            <?php $check_array['check-area'.$menu_4->id] = ['parent' => 'check-all'.$menu_4->id, 'child' => 'check'.$menu_4->id]; ?>
                                                                                                            <div id="{{'collapse'.$menu_4->id}}" class="panel-collapse collapse">
                                                                                                                <div class=panel-body>
                                                                                                                    @if(!empty($menu_4->internal_links) && count($menu_4->internal_links)>0)
                                                                                                                        <?php $check_array['check-link-area'.$menu_4->id] = ['parent' => 'check-link-all'.$menu_4->id, 'child' => 'check-link'.$menu_4->id]; ?>
                                                                                                                        <div id="{{'check-link-area'.$menu_4->id}}" class="row">
                                                                                                                            <div class="col-lg-12">
                                                                                                                                <div class="col-lg-12">
                                                                                                                                    <div class="page-header mt0 mb5 pb5">
                                                                                                                                        <div class="checkbox-custom mt0">
                                                                                                                                            <input id="{{'checkbox-all'.$menu_4->id}}" type="checkbox" @if(!(collect($menu_4->internal_links->pluck('link_access'))->contains(''))){{'checked'}}@endif class="{{'check-link-all'.$menu_4->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} {{'check'.$menu_3->id}} {{'check'.$menu_4->id}} chk-box">
                                                                                                                                            <label for="{{'checkbox-all'.$menu_4->id}}">
                                                                                                                                                <strong>Select All</strong>
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                    @foreach($menu_4->internal_links->chunk(4) as $internal_links)
                                                                                                                                        <div class="row">
                                                                                                                                            @foreach($internal_links as $internal_link)
                                                                                                                                                <div class="col-lg-3 col-md-3">
                                                                                                                                                    <div class="checkbox-custom">
                                                                                                                                                        <input id="{{'link-checkbox'.$internal_link->id}}" name="internal_link[]" type="checkbox" value="{{$internal_link->id}}" @if(!empty($internal_link->link_access)){{'checked'}}@endif class="{{'check-link'.$menu_4->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} {{'check'.$menu_3->id}} {{'check'.$menu_4->id}} chk-box">
                                                                                                                                                        <label for="{{'link-checkbox'.$internal_link->id}}">{{$internal_link->link_name}}</label>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            @endforeach
                                                                                                                                        </div>
                                                                                                                                    @endforeach
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    @endif
                                                                                                                    @if((!empty($menu_4->internal_links) && count($menu_4->internal_links)>0) && !empty($menu_list_5))<br>@endif
                                                                                                                    @if(!empty($menu_list_5))
                                                                                                                        @foreach($menu_list_5 as $menu_key_5=>$val)
                                                                                                                            <?php
                                                                                                                            $menu_5 = $software_menus[$menu_key_5];
                                                                                                                            ?>
                                                                                                                            <div id="{{'check-area'.$menu_5->id}}" class="panel panel-default">
                                                                                                                                <div class=panel-heading>
                                                                                                                                    <h4 class=panel-title>
                                                                                                                                        <div class="accordion-toggle checkbox-custom mt0">
                                                                                                                                            <input id="{{'checkbox'.$menu_5->id}}" name="menu[]" type="checkbox" value="{{$menu_5->id}}" @if(!empty($menu_5->menu_access)){{'checked'}}@endif class="{{'check-all'.$menu_5->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} {{'check'.$menu_3->id}} {{'check'.$menu_4->id}} chk-box">
                                                                                                                                            <label for="{{'checkbox'.$menu_5->id}}">
                                                                                                                                                <i class="s12 pull-left {{!empty($menu_5->menu_icon)?$menu_5->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                                                                                                                                                <strong>{{$menu_5->menu_name}}</strong>
                                                                                                                                            </label>
                                                                                                                                        </div>
                                                                                                                                        @if(!empty($menu_5->internal_links) && count($menu_5->internal_links)>0)
                                                                                                                                            <a class="accordion-toggle collapsed" href="{{'#collapse'.$menu_5->id}}" data-toggle="collapse">
                                                                                                                                                <i class="icomoon-icon-plus s12"></i>
                                                                                                                                                <i class="icomoon-icon-minus s12"></i>
                                                                                                                                            </a>
                                                                                                                                        @endif
                                                                                                                                    </h4>
                                                                                                                                </div>
                                                                                                                                @if(!empty($menu_5->internal_links) && count($menu_5->internal_links)>0)
                                                                                                                                    <?php $check_array['check-area'.$menu_5->id] = ['parent' => 'check-all'.$menu_5->id, 'child' => 'check'.$menu_5->id]; ?>
                                                                                                                                    <?php $check_array['check-link-area'.$menu_5->id] = ['parent' => 'check-link-all'.$menu_5->id, 'child' => 'check-link'.$menu_5->id]; ?>
                                                                                                                                    <div id="{{'collapse'.$menu_5->id}}" class="panel-collapse collapse">
                                                                                                                                        <div class=panel-body>
                                                                                                                                            <div id="{{'check-link-area'.$menu_5->id}}" class="row">
                                                                                                                                                <div class="col-lg-12">
                                                                                                                                                    <div class="col-lg-12">
                                                                                                                                                        <div class="page-header mt0 mb5 pb5">
                                                                                                                                                            <div class="checkbox-custom mt0">
                                                                                                                                                                <input id="{{'checkbox-all'.$menu_5->id}}" type="checkbox" @if(!(collect($menu_5->internal_links->pluck('link_access'))->contains(''))){{'checked'}}@endif class="{{'check-link-all'.$menu_5->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} {{'check'.$menu_3->id}} {{'check'.$menu_4->id}} {{'check'.$menu_5->id}} chk-box">
                                                                                                                                                                <label for="{{'checkbox-all'.$menu_5->id}}">
                                                                                                                                                                    <strong>Select All</strong>
                                                                                                                                                                </label>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                        @foreach($menu_5->internal_links->chunk(4) as $internal_links)
                                                                                                                                                            <div class="row">
                                                                                                                                                                @foreach($internal_links as $internal_link)
                                                                                                                                                                    <div class="col-lg-3 col-md-3">
                                                                                                                                                                        <div class="checkbox-custom">
																																											<input id="{{'link-checkbox'.$internal_link->id}}" name="internal_link[]" type="checkbox" value="{{$internal_link->id}}" @if(!empty($internal_link->link_access)){{'checked'}}@endif class="{{'check-link'.$menu_5->id}} {{'check'.$menu_1->id}} {{'check'.$menu_2->id}} {{'check'.$menu_3->id}} {{'check'.$menu_4->id}} {{'check'.$menu_5->id}} chk-box">
                                                                                                                                                                            <label for="{{'link-checkbox'.$internal_link->id}}">{{$internal_link->link_name}}</label>
                                                                                                                                                                        </div>
                                                                                                                                                                    </div>
                                                                                                                                                                @endforeach
                                                                                                                                                            </div>
                                                                                                                                                        @endforeach
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                @endif
                                                                                                                            </div>
                                                                                                                        @endforeach
                                                                                                                    @endif
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        @endif
                                                                                                    </div>
                                                                                                @endforeach
                                                                                            @endif
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="row ml0 mt20">
                        {{csrf_field()}}
                        <button id="project-access-save" class="btn btn-default" type="button">Save Access</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#projectAccessPanel").checkAll({
            masterCheckbox: "#checkboxAll",
            otherCheckboxes: ".chk-box"
        });

        <?php
        foreach($check_array as $check_area => $check) {
        ?>
            $("#{{$check_area}}").checkAll({
                masterCheckbox: ".{{$check['parent']}}",
                otherCheckboxes: ".{{$check['child']}}"
            });
        <?php
        }
        ?>

        $("#project-access-save").click(function(){
            preLoader($("#projectAccessPanel"));
            var data = $("#project-access-form").serializeArray();
            var module_id = $(".stats-btn-selected").attr('data');
            data[data.length] = {name:'project', value:$("#project_id").val()};
            data[data.length] = {name:'module', value:module_id};
            $.ajax({
                url: "{{route('softAdmin.projectAccess')}}",
                type: "POST",
                data: data,
                success: function (data) {
                    dataFilter(data);
                    $.gritter.add({
                        title: "Done !!!",
                        text: "Project Access has successfully done.",
                        time: "",
                        close_icon: "entypo-icon-cancel s12",
                        icon: "icomoon-icon-checkmark-3",
                        class_name: "success-notice"
                    });
                    preLoaderHide($("#projectAccessPanel"));
                    projectAccessView(module_id);
                }
            });
        });
    });
</script>