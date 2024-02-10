<?php $panelTitle = "Designation Create"; ?>
@include("panelStart")
<form type="create" data-fv-excluded="" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Project ID</label>
        <div class="col-lg-10 col-md-9">
            <select required data-fv-icon="false" name="project_id" id="project_id" class="form-control">
                <option value="">Select Project ID</option>
                @foreach($projects as $project)
                    <option value="{{$project->id}}">{{$project->project_id}}</option>
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
        <label class="col-lg-2 col-md-3 control-label required">Designation Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="name" placeholder="Designation Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Grade</label>
        <div class="col-lg-10 col-md-9">
            <input required name="grade" placeholder="Grade" class="form-control">
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create Designation</button>
        </div>
    </div>
</form>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function () {
        $("#project_id").select2({
            placeholder: "Select Project ID"
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
                        if(parseInt(data)===0) {
                            location.replace(appUrl.getSiteAction('/login'));
                        } else {
                            $("#project_name").val(data.project_name);
                        }
                    }
                });
            } else {
                $("#project_name").val('');
            }
        });
    });
</script>