<form type="update" id="softwareMenuForm" data-fv-excluded="" panelTitle="Update Menu" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Folder</label>
        <div class="col-lg-10 col-md-9">
            <select required id="folder_id" class="form-control">
                <option value="">None</option>
                @foreach($folder as $folder)
                    <option value="{{$folder->id}}" @if($software_module->folder_id==$folder->id){{'selected'}}@endif>{{$folder->folder_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Module</label>
        <div class="col-lg-10 col-md-9" id="link_module_id">
            <select required data-fv-icon="false" id="module_id" name="module_id" class="form-control">
                <option value="{{$software_module->id}}">{{$software_module->module_name}}</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Menu Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="menu_name" placeholder="Menu Name" class="form-control" value="{{$software_menu->menu_name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Icon</label>
        <div class="col-lg-10 col-md-9">
            <select id="menu_icon" name="menu_icon" class="form-control">
                <option value="">None</option>
                @foreach($icons as $icon)
                    <option @if($software_menu->menu_icon==$icon->class_name){{'selected'}}@endif value="{{$icon->class_name}}">{{$icon->class_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Using Resource</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox id="resource" name="resource" value="1" @if($software_menu->resource==1){{'checked'}}@endif> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group" id="link_route">
        <?php
        if($software_menu->resource==1) {
            $route = explode(".", $software_menu->route);
            $route_func = $route[count($route)-1];
            array_pop($route);
            $route_link = implode(".", $route);
            $route = '';
        ?>
            <div id="link_name_field">
                <label class="col-lg-2 col-md-3 control-label required">Main Link</label>
                <div class="col-lg-4 col-md-3">
                    <input required data-fv-row="#link_name_field" name="link_name" id="link_name" placeholder="Main Link" class="form-control" value="{{str_replace(".", "/", $route_link)}}">
                </div>
            </div>
            <label class="col-lg-2 col-md-3 control-label">Resource Function</label>
            <div class="col-lg-4 col-md-3">
                <select data-fv-icon="false" name="resource_function" class="form-control">
                    <option @if($route_func=='index'){{'selected'}}@endif value="index">index</option>
                    <option @if($route_func=='create'){{'selected'}}@endif value="create">create</option>
                    <option @if($route_func=='show'){{'selected'}}@endif value="show">show</option>
                    <option @if($route_func=='edit'){{'selected'}}@endif value="edit">edit</option>
                </select>
            </div>
        <?php
        } else {
            $route = $software_menu->route;
            $route_func = '';
            $route_link = '';
            ?>
            <div id="route_name_field">
                <label class="col-lg-2 col-md-3 control-label required">Route</label>
                <div class="col-lg-10 col-md-9">
                    <input required data-fv-row="#route_name_field" name="route" id="route" placeholder="Route" class="form-control" value="{{$route}}">
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Status</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox name="status" value="1" @if($software_menu->status==1){{'checked'}}@endif> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update Menu</button>
        </div>
    </div>
</form>
@include("panelEnd")

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
                    url: "{{route('softAdmin.softwareLinkModule')}}",
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
                $("#link_route").html('<div id="link_name_field"><label class="col-lg-2 col-md-3 control-label">Main Link</label><div class="col-lg-4 col-md-3"><input required data-fv-row="#link_name_field" name="link_name" id="link_name" placeholder="Main Link" class="form-control" value="{{$route_link}}"></div></div><label class="col-lg-2 col-md-3 control-label">Resource Function</label><div class="col-lg-4 col-md-3"><select data-fv-icon="false" name="resource_function" class="form-control"><option @if($route_func=='index'){{'selected'}}@endif value="index">index</option><option @if($route_func=='create'){{'selected'}}@endif value="create">create</option><option @if($route_func=='show'){{'selected'}}@endif value="show">show</option><option @if($route_func=='edit'){{'selected'}}@endif value="edit">edit</option></select></div>');
                $('#softwareMenuForm').formValidation('addField', $("#link_name"));
            } else {
                $('#softwareMenuForm').formValidation('removeField', $("#link_name"));
                $("#link_route").html('<div id="route_name_field"><label class="col-lg-2 col-md-3 control-label">Route</label><div class="col-lg-10 col-md-9"><input required data-fv-row="#route_name_field" name="route" id="route" placeholder="Route" class="form-control" value="{{$route}}"></div></div>');
                $('#softwareMenuForm').formValidation('addField', $("#route"));
            }
        });
    });

    function select2Format(state) {
        if (!state.id) return state.text;
        return "<i class='s16 "+state.id.toLowerCase()+"'></i> " + state.text;
    }
</script>