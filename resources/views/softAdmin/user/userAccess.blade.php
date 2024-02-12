<input id="user_id" type="hidden" value="{{$user_info->id}}">
@foreach($software_modules as $software_module_list)
    <div class=row>
        @foreach($software_module_list as $software_module)
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <a href=# data="{{$software_module->id}}" class="soft-module stats-btn pattern mb20"><i class="icon {{$software_module->module_icon}}"></i> <span class=txt>{{$software_module->module_name}}</span></a>
            </div>
        @endforeach
    </div>
@endforeach
<button class="back-btn btn btn-default" type="button">Back to List</button>

<script type="text/javascript">
    $(document).ready(function(){
        $("#userList").find(".panel-title").html('{{$user_info->name}}');
    });
</script>