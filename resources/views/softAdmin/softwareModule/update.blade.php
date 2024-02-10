<form type="update" data-fv-excluded="" panelTitle="Update Module" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Folder</label>
        <div class="col-lg-10 col-md-9">
            <select required id="folder_id" name="folder_id" class="form-control">
                <option value="">None</option>
                @foreach($folder as $folder)
                    <option @if($software_modules->folder_id==$folder->id){{'selected'}}@endif value="{{$folder->id}}">{{$folder->folder_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Module Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="module_name" value="{{$software_modules->module_name}}" placeholder="Module Name" class="form-control" kl_virtual_keyboard_secure_input="on">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Icon</label>
        <div class="col-lg-10 col-md-9">
            <select id="module_icon" name="module_icon" class="form-control">
                <option value="">None</option>
                @foreach($icons as $icon)
                    <option @if($software_modules->module_icon==$icon->class_name){{'selected'}}@endif value="{{$icon->class_name}}">{{$icon->class_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">URL Prefix</label>
        <div class="col-lg-10 col-md-9">
            <input required name="url_prefix" value="{{$software_modules->url_prefix}}" placeholder="URL Prefix" class="form-control" kl_virtual_keyboard_secure_input="on">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Route Prefix</label>
        <div class="col-lg-10 col-md-9">
            <input required name="route_prefix" value="{{$software_modules->route_prefix}}" placeholder="Route Prefix" class="form-control" kl_virtual_keyboard_secure_input="on">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Status</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input @if($software_modules->status==1){{'checked'}}@endif type=checkbox name="status" value="1" checked> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update Module</button>
        </div>
    </div>
</form>
@include("panelEnd")

<script type="text/javascript">
    $(document).ready(function () {
        $("#module_icon").select2({
            formatResult: select2Format,
            formatSelection: select2Format,
            escapeMarkup: function(m) { return m; }
        });
        $("#folder_id").select2();
    });

    function select2Format(state) {
        if (!state.id) return state.text;
        return "<i class='s16 "+state.id.toLowerCase()+"'></i> " + state.text;
    }
</script>