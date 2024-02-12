<?php $panelTitle = "Medical"; ?>
<style>
    .modal-footer{
        display: none;
    }

    #modalFooter{
        display: block;
    }
</style>
<form id="mobilizeForm" callback="agreementStatus"  panelTitle="{{$panelTitle}}"  class=" form-horizontal" method="post">
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
             <div class="form-group mobilizeDate">
                <label class="col-lg-4 col-md-5 control-label required" id="mobilizeDate">Mofa Date</label>
                <div class="col-lg-7 col-md-6">
                   <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        <input type="text"  id="mobilize_date" name="mobilize_date"  class="form-control dateTimeFormat keypressOff"  placeholder="e.g. yyyy-mm-dd">
                    </div>
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
var mobilizeForm = new FormData($('#mobilizeForm')[0])
let refreshUrl = '{{ 'recruitment/mobilization/general-page/'.$projectId.'/'.$candidateId.'/'.$mobilizeId }}';
$.ajax({
      url: 'recruitment/general-page',
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
/*---------------------------------------
  END  DATE TIME FORMAT AND KEYPRESS OFF
-----------------------------------------*/

</script>