<form type="create" id="softwareLinkForm" data-fv-excluded="" callBack="formRefresh" panelTitle="Internal Link Create" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Folder</label>
        <div class="col-lg-10 col-md-9">
            <select required id="folder_id" class="form-control">
                <option value="">None</option>
                @foreach($folder as $folder)
                    <option value="{{$folder->id}}">{{$folder->folder_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Module</label>
        <div class="col-lg-10 col-md-9">
            <div id="link_module_id">
                <select required id="module_id" name="module_name" class="form-control">
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Menu</label>
        <div class="col-lg-10 col-md-9">
            <div id="link_menu_id">
                <select required id="menu_id" name="menu_id" class="form-control">
                    <option value=""></option>
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Using Resource</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox id="resource" name="resource" value="1"> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Link Name</label>
        <div class="col-lg-10 col-md-9" id="link_name_field">
            <input required name="link_name" id="link_name" placeholder="Link Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required" id="route_level">Route</label>
        <div class="col-lg-10 col-md-9">
            <input required name="route" id="route" placeholder="Route Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Status</label>
        <div class="col-lg-10 col-md-9">
            <div class="toggle-custom toggle-inline">
                <label class=toggle data-on=Yes data-off=No>
                    <input type=checkbox name="status" value="1" checked> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create Link</button>
        </div>
    </div>
</form>
@include("panelEnd")

<script type="text/javascript">
    $(document).ready(function () {
        $("#folder_id").select2({placeholder:"Select Folder"});
        $("#module_id").select2({placeholder:"Select Module"});
        $("#menu_id").select2({placeholder:"Select Menu"});

        $("#folder_id").change(function(){
            var folder_id=$(this).val();
            if(folder_id){
                $('#softwareLinkForm').formValidation('removeField', $("#module_id"));
                $.ajax({
                    url: "{{route('softAdmin.softwareInternalLinkModule')}}",
                    type: "GET",
                    data: {folder_id:folder_id},
                    success: function (data) {
                        dataFilter(data);
                        $("#link_module_id").html(data);
                        $("#module_id").select2({placeholder:"Select Module"});
                        $('#softwareLinkForm').formValidation('addField', $("#module_id"));
                    }
                });
            }
        });
        
        $("#link_module_id").on('change', '#module_id', function(){
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

        $("#resource").change(function(){
            if($(this).is(':checked')) {
                $('#softwareLinkForm').formValidation('removeField', $("#link_name"));
                $("#link_name_field").html('<div class="row"><div class="col-md-3"><input required name="link_name_add" placeholder="Add Link" class="form-control"></div><div class="col-md-3"><input required name="link_name_edit" placeholder="Edit Link" class="form-control"></div><div class="col-md-3"><input required name="link_name_delete" placeholder="Delete Link" class="form-control"></div><div class="col-md-3"><input required name="link_name_show" placeholder="Show Link" class="form-control"></div></div>');
                $("#route_level").html('Main Link');
                $("#route").attr('placeholder', 'Main Link');
            } else {
                $("#link_name_field").html('<input required name="link_name" id="link_name" placeholder="Link Name" class="form-control" kl_virtual_keyboard_secure_input="on">');
                $("#route_level").html('Route');
                $("#route").attr('placeholder', 'Route Name');
                $('#softwareLinkForm').formValidation('addField', $("#link_name"));
            }
        });
        
    });
    function formRefresh() {
        $('#softwareLinkForm').formValidation('removeField', $("#menu_id"));
        $("#link_menu_id").html('<select required id="menu_id" name="menu_id" class="form-control"><option value=""></option></select>');
        $("#menu_id").select2({placeholder:"Select Menu"});
        $('#softwareLinkForm').formValidation('addField', $("#menu_id"));
        if($("#link_name").length==0) {
            $("#link_name_field").html('<input required name="link_name" id="link_name" placeholder="Link Name" class="form-control" kl_virtual_keyboard_secure_input="on">');
            $('#softwareLinkForm').formValidation('addField', $("#link_name"));
        }
    }
</script>