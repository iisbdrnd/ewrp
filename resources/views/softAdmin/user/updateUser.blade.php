<form type="update" id="userForm" data-fv-excluded="" panelTitle="Update User" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Project ID</label>
        <div class="col-lg-10 col-md-9">
            <select  required data-fv-icon="false" name="project_id" id="project_id" class="form-control">
                <option value="">Select Project ID</option>
                @foreach($projects as $project)
                    <option value="{{$project->id}}" @if($project->id==$user->project_id)selected<?php $project_name=$project->name; ?>@endif>{{$project->project_id}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Project Name</label>
        <div class="col-lg-10 col-md-9">
            <input readonly id="project_name" placeholder="Project Name" class="form-control" value="{{$user->project_name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Employee ID</label>
        <div class="col-lg-10 col-md-9">
            <input readonly required name="emp_id" id="emp_id" placeholder="Employee ID" class="form-control" value="{{$user->emp_id}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="name" placeholder="Name" class="form-control" value="{{$user->name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Surname</label>
        <div class="col-lg-10 col-md-9">
            <input name="surname" placeholder="Surname" class="form-control" value="{{$user->surname}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Designation</label>
        <div class="col-lg-10 col-md-9" id="designation_view">
            <select required name="designation" id="designation" data-fv-icon="false" class="select2 form-control ml0">
                <option value=""></option>
                @foreach($employeeDesignation as $employeeDesignation)
                <option value="{{$employeeDesignation->id}}"  @if(@$employeeDesignation->id==$user->designation){{'selected'}}@endif>{{$employeeDesignation->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Job Area</label>
        <div class="col-lg-10 col-md-9">
            <select id="job_area" name="job_area" data-fv-icon="false" class="select2 form-control ml0">
                <option value=""></option>
                @foreach($crmJobArea as $area)
                <option value="{{$area->id}}" @if(@$area->id==@$user->job_area){{'selected'}}@endif>{{$area->area_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Area Details</label>
        <div class="col-lg-10 col-md-9">
            <textarea id="area_details" class="form-control" readonly rows=2></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Timezone</label>
        <div class="col-lg-10 col-md-9">
            <select name="timezone" data-fv-icon="false" class="timezone form-control ml0">
                @foreach($timezones as $timezone)
                    <option value="{{$timezone->id}}" @if($user->timezone_id==$timezone->id) {{'selected'}} @endif > {{$timezone->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Report To</label>
        <div class="col-lg-10 col-md-9">
            <select required name="report_to" id="report_to" data-fv-icon="false" class="select2 form-control ml0">
                <option value="0">N/A</option>
                @foreach($reportTo as $report)
                <option value="{{@$report->id}}" @if(@$report->id==$user->report_to){{'selected'}}@endif>{{$report->name}}</option>
                @endforeach
            </select>
            
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">E-mail</label>
        <div class="col-lg-10 col-md-9">
            <input required name="email" type="email" placeholder="E-mail" class="form-control" value="{{$user->email}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Status</label>
        <div class="col-lg-10 col-md-9">
            <select required name="status" id="status" data-fv-icon="false" class="select2 form-control ml0">
                <option value="Active" @if($user->status=="Active"){{'selected'}}@endif>Active</option>
                <option value="Inactive" @if($user->status=="Inactive"){{'selected'}}@endif>Inactive</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update User</button>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function () {
        
        var pre_project_id = parseInt("{{$user->project_id}}");
        var pre_project_name = "{{$user->project_name}}";
        var pre_emp_id = "{{$user->emp_id}}";

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


        $("#status").fancySelect();

        $("#project_id").on('change', function(){
            var project_id = parseInt($(this).val());
            if(project_id) {
                if(project_id==pre_project_id) {
                    $("#emp_id").val(pre_emp_id);
                    $("#project_name").val(pre_project_name);
                } else {
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
                }

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

        var job_areas = <?php echo json_encode($crmJobArea); ?>;
        if(job_areas[<?php echo $user->job_area; ?>]) {
            $("#area_details").val(job_areas[<?php echo $user->job_area; ?>].area_details);
        }

        $('#job_area').on('change', function() {
            var job_area_id = $(this).val();
            var areaDetails = job_areas[job_area_id];
            if(job_area_id){
                $("#area_details").val(areaDetails.area_details);
            }
        });

    });

    function formRefresh() {
        $("#project_name").val("");
    }
</script>