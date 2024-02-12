<?php $panelTitle = "CV moved to Interview"; ?>
<form type="create" callback="agreementStatus" panelTitle="{{$panelTitle}}" class="form-load form-horizontal">
    {{csrf_field()}}
    <div class="panel panel-default chart">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-lg-3 col-md-4 control-label required">Project Name</label>
                <div class="col-lg-7 col-md-6">
                    <select  required name="ew_project_id" id="ew_project_id" class="select2 form-control">
                        <option>Select project</option>
                        @foreach($projects as $project)
                        <option value="{{ $project->ew_project_id }}">
                            {{ @Helper::projects($project->ew_project_id)->project_name }}
                        </option>
                        @endforeach
                    </select>
                      
                </div>
            </div>
        </div>
    </div>
   
</form>

<script type="text/javascript">


    $(document).ready(function() {
        $("#ew_project_id").select2({
            placeholder: "Select"
        });
    });

    function agreementStatus(data) {
        bootbox.hideAll();
    }
/*--------------------------------------
    DATE TIME FORMAT AND KEYPRESS OFF  
----------------------------------------*/
    $('.keypressOff').keypress(function(e) {
        return false
    });


    $('.dateTimeFormat').datepicker({
        format: "yyyy-mm-dd"
    });
/*---------------------------------------
  END  DATE TIME FORMAT AND KEYPRESS OFF
-----------------------------------------*/

</script>