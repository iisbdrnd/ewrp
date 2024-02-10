<form type="update" id="adminLinkForm" data-fv-excluded="" panelTitle="Internal Link Update" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
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
                    @foreach($menu_list_1 as $menu_key_1=>$val)
                        <?php
                        $menu_1 = $adminMenus[$menu_key_1];
                        $menu_list_2 = $parent_ids->filter(function($item) use ($menu_1) { return $item==$menu_1->id; })->all();
                        ?>
                        @if(!empty($menu_list_2))
                            <optgroup label="{{$menu_1->menu_name}}">
                                @foreach($menu_list_2 as $menu_key_2=>$val)
                                    <?php
                                    $menu_2 = $adminMenus[$menu_key_2];
                                    $menu_list_3 = $parent_ids->filter(function($item) use ($menu_2) { return $item==$menu_2->id; })->all();
                                    ?>
                                    @if(!empty($menu_list_3))
                                        <optgroup label="{{$menu_2->menu_name}}">
                                            @foreach($menu_list_3 as $menu_key_3=>$val)
                                                <?php
                                                $menu_3 = $adminMenus[$menu_key_3];
                                                $menu_list_4 = $parent_ids->filter(function($item) use ($menu_3) { return $item==$menu_3->id; })->all();
                                                ?>
                                                @if(!empty($menu_list_4))
                                                    <optgroup label="{{$menu_3->menu_name}}">
                                                        @foreach($menu_list_4 as $menu_key_4=>$val)
                                                            <?php
                                                            $menu_4 = $adminMenus[$menu_key_4];
                                                            $menu_list_5 = $parent_ids->filter(function($item) use ($menu_4) { return $item==$menu_4->id; })->all();
                                                            ?>
                                                            @if(!empty($menu_list_5))
                                                                <optgroup label="{{$menu_4->menu_name}}">
                                                                    @foreach($menu_list_5 as $menu_key_5=>$val)
                                                                        <?php
                                                                        $menu_5 = $adminMenus[$menu_key_5];
                                                                        ?>
                                                                        <option @if($menu_1->id==$adminInternalLink->menu_id){{'selected'}}@endif value="{{$menu_5->id}}">{{$menu_5->menu_name}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @else
                                                                <option @if($menu_4->id==$adminInternalLink->menu_id){{'selected'}}@endif value="{{$menu_4->id}}">{{$menu_4->menu_name}}</option>
                                                            @endif
                                                        @endforeach
                                                    </optgroup>
                                                @else
                                                    <option @if($menu_3->id==$adminInternalLink->menu_id){{'selected'}}@endif value="{{$menu_3->id}}">{{$menu_3->menu_name}}</option>
                                                @endif
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option @if($menu_2->id==$adminInternalLink->menu_id){{'selected'}}@endif value="{{$menu_2->id}}">{{$menu_2->menu_name}}</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @else
                            <option @if($menu_1->id==$adminInternalLink->menu_id){{'selected'}}@endif value="{{$menu_1->id}}">{{$menu_1->menu_name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Using Resource</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox id="resource" name="resource" value="1" @if($adminInternalLink->resource==1){{'checked'}}@endif> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Link Name</label>
        <div class="col-lg-10 col-md-9" id="link_name_field">
            <input required name="link_name" id="link_name" placeholder="Link Name" class="form-control" value="{{$adminInternalLink->link_name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label" id="route_level required">Route</label>
        <div class="col-lg-10 col-md-9">
            <input required name="route" id="route" placeholder="Route Name" class="form-control" value="{{$adminInternalLink->route}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Status</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox name="status" value="1" @if($adminInternalLink->status==1){{'checked'}}@endif> <span class=button-checkbox></span>
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
@include("panelEnd")

<script type="text/javascript">
    $(document).ready(function () {
        $("#menu_id").select2({placeholder:"Select Menu"});
    });
</script>