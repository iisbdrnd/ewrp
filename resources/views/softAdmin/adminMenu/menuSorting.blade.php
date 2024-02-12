<?php $panelTitle = 'Menu Sorting'; ?>
@include("panelStart")
    <div class="row ml0">
        <div class=dd id=admin-menu-nestable>
        <ol class=dd-list>
        <?php
            $parent_ids = $adminMenu->pluck('parent_id');
            $menu_list_1 = $parent_ids->filter(function($item) { return $item==0; })->all();
        ?>
        @foreach($menu_list_1 as $menu_key_1=>$val)
            <?php
                $menu_1 = $adminMenu[$menu_key_1];
                $menu_list_2 = $parent_ids->filter(function($item) use ($menu_1) { return $item==$menu_1->id; })->all();
            ?>
            <li class="dd-item dd3-item" data-id={{$menu_1->id}}>
                <i class="dd-handle dd3-handle {{!empty($menu_1->menu_icon)?$menu_1->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                <div class=dd3-content>{{$menu_1->menu_name.' ('.$menu_1->route.')'}}</div>
                @if(!empty($menu_list_2))
                    <ol class=dd-list>
                    @foreach($menu_list_2 as $menu_key_2=>$val)
                        <?php
                        $menu_2 = $adminMenu[$menu_key_2];
                        $menu_list_3 = $parent_ids->filter(function($item) use ($menu_2) { return $item==$menu_2->id; })->all();
                        ?>
                        <li class="dd-item dd3-item" data-id={{$menu_2->id}}>
                            <i class="dd-handle dd3-handle {{!empty($menu_2->menu_icon)?$menu_2->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                            <div class=dd3-content>{{$menu_2->menu_name.' ('.$menu_2->route.')'}}</div>
                            @if(!empty($menu_list_3))
                                <ol class=dd-list>
                                    @foreach($menu_list_3 as $menu_key_3=>$val)
                                        <?php
                                        $menu_3 = $adminMenu[$menu_key_3];
                                        $menu_list_4 = $parent_ids->filter(function($item) use ($menu_3) { return $item==$menu_3->id; })->all();
                                        ?>
                                        <li class="dd-item dd3-item" data-id={{$menu_3->id}}>
                                            <i class="dd-handle dd3-handle {{!empty($menu_3->menu_icon)?$menu_3->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                                            <div class=dd3-content>{{$menu_3->menu_name.' ('.$menu_3->route.')'}}</div>
                                            @if(!empty($menu_list_4))
                                                <ol class=dd-list>
                                                    @foreach($menu_list_4 as $menu_key_4=>$val)
                                                        <?php
                                                        $menu_4 = $adminMenu[$menu_key_4];
                                                        $menu_list_5 = $parent_ids->filter(function($item) use ($menu_4) { return $item==$menu_4->id; })->all();
                                                        ?>
                                                        <li class="dd-item dd3-item" data-id={{$menu_4->id}}>
                                                            <i class="dd-handle dd3-handle {{!empty($menu_4->menu_icon)?$menu_4->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                                                            <div class=dd3-content>{{$menu_4->menu_name.' ('.$menu_4->route.')'}}</div>
                                                            @if(!empty($menu_list_5))
                                                                <ol class=dd-list>
                                                                    @foreach($menu_list_5 as $menu_key_5=>$val)
                                                                        <?php
                                                                        $menu_5 = $adminMenu[$menu_key_5];
                                                                        ?>
                                                                        <li class="dd-item dd3-item" data-id={{$menu_5->id}}>
                                                                            <i class="dd-handle dd3-handle {{!empty($menu_5->menu_icon)?$menu_5->menu_icon:'icomoon-icon-arrow-right-3'}}"></i>
                                                                            <div class=dd3-content>{{$menu_5->menu_name.' ('.$menu_5->route.')'}}</div>
                                                                        </li>
                                                                    @endforeach
                                                                </ol>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            @endif
                        </li>
                    @endforeach
                    </ol>
                @endif
            </li>
        @endforeach
        </ol>
    </div>
    </div>
    <div class="row ml0 mt20">
        <form id="admin-menu-form">
            {{csrf_field()}}
            <input id="admin-menu-nestable-output" name="admin_menu" type="hidden">
            <button id="menu-sorting-save" class="btn btn-default" type="button">Save Menu</button>
        </form>
    </div>
@include("panelEnd")

<script type="text/javascript">
    $(document).ready(function() {
        var a = function(a) {
            var b = a.length ? a : $(a.target),
                    c = b.data("output");
            c.val(window.JSON ? window.JSON.stringify(b.nestable("serialize")) : "JSON browser support required for this demo.")
        };

        $("#admin-menu-nestable").nestable().on("change", a), a($("#admin-menu-nestable").data("output", $("#admin-menu-nestable-output")));

        $("#menu-sorting-save").click(function(){
            preLoader($("#sortingPanel"));
            var data = $("#admin-menu-form").serializeArray();
            $.ajax({
                url: "{{route('softAdmin.adminMenuSorting')}}",
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
</script>