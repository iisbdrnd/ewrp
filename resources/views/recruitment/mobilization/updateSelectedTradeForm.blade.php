<?php $panelTitle = "Selected Trade" ?>
<form type="create" callback="agreementStatus" panelTitle="{{$panelTitle}}" class="form-load form-horizontal">
    {{csrf_field()}}
    <div class="panel panel-default chart">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-lg-3 col-md-4 control-label required">Trade Selection</label>
                <div class="col-lg-7 col-md-6">
                    <select name="selected_trade" id="selected_trade" class="form-control select2">
                        <option></option>
                        <option>--Select Trade--</option>
                        @foreach($trades as $trade)
                            <option value="{{ $trade->id }}">{{ $trade->trade_name }}</option>
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