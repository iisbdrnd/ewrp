<?php $panelTitle = "Medical"; ?>
<style>
    .modal-footer{
        display: none;
    }

    #modalFooter{
        display: block;
    }
</style>
<form id="medicalForm" callback="agreementStatus" panelTitle="{{$panelTitle}}" class=" form-horizontal" method="post">
    {{csrf_field()}}
    <div class="panel panel-default chart">
        <div class="panel-body">
            <div class="form-group" style="display: none;">
                <label class="col-lg-4 col-md-5 control-label required" ></label>
                <div class="col-lg-7 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        <input type="text" name="" id="" placeholder="" class="form-control">
                        <input type="hidden" name="projectId" id="" value="{{ $projectId }}">
                        <input type="hidden" name="candidateId" id="" value="{{ $candidateId }}">
                        <input type="hidden" name="mobilizeId" id="" value="{{ $mobilizeId }}">
                    </div>
                </div>
            </div>
            <div class="form-group medical_name">
                <label class="col-lg-4 col-md-5 control-label required"  id="medicalNameInputElement">Medical Center</label>
                <div class="col-lg-7 col-md-6">
                    <input type="text" id="medical_name"  name="medical_name"  class="form-control" placeholder="Medical Name">
                </div>
            </div> 
            <div class="form-group medical_gone_date">
                <label class="col-lg-4 col-md-5 control-label required" id="medicalDateInputElement">Medical Date</label>
                <div class="col-lg-7 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        <input  id="medical_gone_date" name="medical_gone_date"  class="form-control dateTimeFormat keypressOff" autocomplete="off"  placeholder="e.g.yyyy-mm-dd">
                    </div>
                </div>
            </div>
            <div class="form-group medical_code">
                <label class="col-lg-4 col-md-5 control-label required" id="medicalCodeInputElement" >Medical Slip No.</label>
                <div class="col-lg-7 col-md-6">
                    <input type="text" id="medical_code" name="medical_code" class="form-control" placeholder="Medical Code">
                </div>
            </div>
            <div class="form-group medical_actual_status">
                <label class="col-lg-4 col-md-5 control-label required">Actual Medical Status</label>
                <div class="col-lg-7 col-md-6">
                    <select  name="medical_actual_status" id="medical_actual_status" class="form-control select2">
                        <option value="0">Actual Medical Status</option>
                        <option {{ @$medicalData->medical_actual_status==1?"selected=selected":'' }} value="1">Fit</option>
                        <option {{ @$medicalData->medical_actual_status==2?"selected=selected":'' }}  value="2">Unfit</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</form>
<br>
<div id="modalFooter" class="modal-footer" style="width:100%;border:none;">
    <button data-bb-handler="close" type="button" class="btn btn-default">Close</button>
    <button id="saveButton" type="button" class="btn btn-success">Save</button>
</div>
<script type="text/javascript">

 $("select.select2").select2({
    placeholder: "Select"
});

$('#saveButton').on('click', function(){
 // $('#tab-content').html("");   
var mobilizeForm = new FormData($('#medicalForm')[0])
let refreshUrl = '{{ 'recruitment/mobilization/medical-type/'.$projectId.'/'.$candidateId.'/'.$mobilizeId }}';
$.ajax({
      url: 'recruitment/medical-type',
      data: mobilizeForm,
      processData: false,
      contentType: false,
      type: 'POST',
      success:function(data){
        console.log(data.messege);
        $.gritter.add({
            title: "Done !!!",
            text: data.messege,
            time: "",
            close_icon: "entypo-icon-cancel s12",
            icon: "icomoon-icon-checkmark-3",
            class_name: "success-notice"
        });
        $('#tab-content').load('recruitment/mobilization/single-candidate/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}');
      }

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


</script>