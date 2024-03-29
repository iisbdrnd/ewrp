<form type="update" id="softwareLinkForm" data-fv-excluded="" panelTitle="Internal Link Update" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Module</label>
        <div class="col-lg-10 col-md-9">
            <select id="module_name" name="module_name" class="form-control">
                <option value=""></option>
                @foreach($modules as $module)
                <option @if($module->id==$softwareMenu->module_id) {{'selected'}} @endif value="{{$module->id}}">{{$module->module_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Menu</label>
        <div class="col-lg-10 col-md-9">
            <div id="link_menu_id">
                <select required id="menu_id" name="menu_id" class="form-control">
                    <option value=""></option>
                    @foreach($softwareLinkMenus as $softwareLinkMenu)
                    <option @if($softwareLinkMenu->id==$softwareInternalLinks->menu_id) {{'selected'}} @endif value="{{$softwareLinkMenu->id}}">{{$softwareLinkMenu->menu_name}}</option>                
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
                    <input type=checkbox id="resource" name="resource" value="1" @if($softwareInternalLinks->resource==1){{'checked'}}@endif> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Link Name</label>
        <div class="col-lg-10 col-md-9" id="link_name_field">
            <input required name="link_name" id="link_name" placeholder="Link Name" class="form-control" value="{{$softwareInternalLinks->link_name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label" id="route_level required">Route</label>
        <div class="col-lg-10 col-md-9">
            <input required name="route" id="route" placeholder="Route Name" class="form-control" value="{{$softwareInternalLinks->route}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Status</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox name="status" value="1" @if($softwareInternalLinks->status==1){{'checked'}}@endif> <span class=button-checkbox></span>
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
        $("#module_name").select2({placeholder:"Select Module"});
        $("#menu_id").select2({placeholder:"Select Menu"});
        
        $("#module_name").change(function(){
            var module_name=$(this).val();
            if(module_name){
                $('#softwareLinkForm').formValidation('removeField', $("#menu_id"));
                $.ajax({
                    url: "{{route('softAdmin.softwareLinkMenu')}}",
                    type: "GET",
                    data: {module_name:module_name},
                    success: function (data) {
                        dataFilter(data);
                        $("#link_menu_id").html(data);
                        $("#menu_id").select2({placeholder:"Select Menu"});
                        $('#softwareLinkForm').formValidation('addField', $("#menu_id"));
                    }
                });
            }
        });

    });

    function select2Format(state) {
        if (!state.id) return state.text;
        return "<i class='s16 "+state.id.toLowerCase()+"'></i> " + state.text;
    }
</script>