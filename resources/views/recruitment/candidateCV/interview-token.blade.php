<?php $panelTitle = "Interview Token"; ?>
<form type="create" callback="agreementStatus" panelTitle="{{$panelTitle}}" class="form-load form-horizontal">
    {{csrf_field()}}
    <div class="panel panel-default chart">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-lg-3 col-md-4 control-label required">Status</label>
                <div class="col-lg-7 col-md-6">
                    <input type="hidden" name="ew_candidatescv_id" value="{{ $candidateId }}">
                    <select  required name="ew_project_id" id="ew_project_id" class="select2 form-control">
                        <option>Select Status Project</option>
                        @foreach($interviewCalls as $interviewCall)
                        <option value="{{ $interviewCall->ew_project_id }}"><strong>{{ Helper::projects($interviewCall->ew_project_id)->project_name }}</strong> <span>{{"Start From ".$interviewCall->start_date."  To  ".$interviewCall->end_date }}</span>
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
        $(".select2").select2({
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