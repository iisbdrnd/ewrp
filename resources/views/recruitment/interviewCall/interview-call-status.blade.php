<?php $panelTitle = "Interview Call"; ?>
<form type="create" callback="agreementStatus" panelTitle="{{$panelTitle}}" class="form-load form-horizontal">
    {{csrf_field()}}
    <div class="panel panel-default chart">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-lg-3 col-md-4 control-label required">Status</label>
                <div class="col-lg-7 col-md-6">
                    <select  required name="status" id="status" class="select2 form-control">
                        <option>---Select Status---</option>
                        <option {{ $interviewCalls->status == 1? 'selected=selected':'' }} value="1">Running</option>
                        <option {{ $interviewCalls->status == 2? 'selected=selected':'' }} value="2">Close</option>
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