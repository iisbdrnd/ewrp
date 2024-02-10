<form type="create" id="userForm" data-fv-excluded="" callBack="formRefresh" panelTitle="Create User" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Project ID</label>
        <div class="col-lg-10 col-md-9">
            <select required data-fv-icon="false" name="project_id" id="project_id" class="form-control">
                <option value="">Select Project ID</option>
                @foreach($projects as $project)
                    <option value="{{$project->id}}">{{$project->project_id}} [{{ $project->name }}]</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Project Name</label>
        <div class="col-lg-10 col-md-9">
            <input readonly id="project_name" placeholder="Project Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Employee ID</label>
        <div class="col-lg-10 col-md-9">
            <input readonly id="emp_id" placeholder="Employee ID" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="name" placeholder="Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Surname</label>
        <div class="col-lg-10 col-md-9">
            <input name="surname" placeholder="Surname" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Designation</label>
        <div class="col-lg-10 col-md-9" id="designation_view">
            <select required data-fv-icon="false" name="designation" id="designation" class="form-control">
            @foreach($employeeDesignation as $designation)
                <option value="">Select Designation</option>
                <option value="{{$designation->id}}">{{$designation->name}}</option>
            @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Job Area</label>
        <div class="col-lg-10 col-md-9" id="area_view">
            <select name="job_area" id="job_area" data-fv-icon="false" class="select2 form-control ml0">
                <option value="">Select Job Area</option>
            </select>
        </div>
    </div>
    <div class="form-group" id="crm_area_details" style="display:none;">
        <label class="col-lg-2 col-md-3 control-label">Area Details</label>
        <div class="col-lg-10 col-md-9">
            <textarea readonly id="areaDe" class="form-control" rows=2></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Timezone</label>
        <div class="col-lg-10 col-md-9">
            <select required name="timezone" data-fv-icon="false" class="timezone form-control ml0">
                <option value=""></option>
                @foreach($timezones as $timezone)
                    <option value="{{$timezone->id}}"> {{$timezone->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Report To</label>
        <div class="col-lg-10 col-md-9" id="report_to_view">
            <select required data-fv-icon="false" name="report_to" id="report_to" class="form-control">
                <option value="0">N/A</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">E-mail</label>
        <div class="col-lg-10 col-md-9">
            <input required name="email" type="email" placeholder="E-mail" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create User</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        $("#project_id").select2({
            placeholder: "Select Project ID"
        });
        $("#designation").select2({
            placeholder: "Select Designation"
        });
        $("#job_area").select2({
            placeholder: "Select Area"
        });
        $("#report_to").select2({
            placeholder: "Select Report To"
        });
        $(".timezone").select2({
            placeholder: "Select Timezone"
        });


        $("#project_id").on('change', function(){
            var project_id = $(this).val();
            if(project_id) {
                $.ajax({
                    url: "{{route('softAdmin.projectNameById')}}",
                    data: {project_id: project_id},
                    type: 'GET',
                    dataType: "json",
                    success: function(data) {
                        if(parseInt(data.auth)===0) {
                            location.replace(appUrl.getSiteAction('/login'));
                        } else {
                            $("#emp_id").val(data.emp_id);
                            $("#project_name").val(data.project_name);
                        }
                    }
                });
                $.ajax({
                    url: "{{route('softAdmin.projectUserById')}}",
                    data: {project_id: project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        if(parseInt(data)===0) {
                            location.replace(appUrl.getSiteAction('/login'));
                        } else {
                            $('#userForm').formValidation('removeField', $("#report_to"));
                            $("#report_to_view").html(data);
                            $("#report_to").select2({
                                placeholder: "Select Report To"
                            });
                            $('#userForm').formValidation('addField', $("#report_to"));
                        }
                    }
                });
                $.ajax({
                    url: "{{route('softAdmin.projectDesignationById')}}",
                    data: {project_id: project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        if(parseInt(data)===0) {
                            location.replace(appUrl.getSiteAction('/login'));
                        } else {
                            $('#userForm').formValidation('removeField', $("#designation"));
                            $("#designation_view").html(data);
                            $("#designation").select2({
                                placeholder: "Select Designation"
                            });
                            $('#userForm').formValidation('addField', $("#designation"));
                        }
                    }
                });
            } else {
                $("#project_name").val('');
            }
        });
        
        

    });

    function formRefresh() {
        $("#project_name").val("");
    }
</script>