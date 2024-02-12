<?php $panelTitle = "Medical"; ?>
<style>
    .modal-footer{
        display: none;
    }

    #modalFooter{
        display: block;
    }
</style>
<form id="visaForm" callback="agreementStatus" panelTitle="{{ $panelTitle }}" class=" form-horizontal">
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
            <div class="form-group visa_online_date">
                <label class="col-lg-4 col-md-5 control-label required" >Visa Online Date</label>
                <div class="col-lg-7 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        <input  id="visa_online_date" name="visa_online_date" class="form-control dateTimeFormat keypressOff"  placeholder="e.g. yyyy-mm-dd">
                    </div>
                </div>
            </div>
             <div class="form-group visa_status_code">
                <label class="col-lg-4 col-md-5 control-label required" id="visa_status_code_label">Visa Status Code</label>
                <div class="col-lg-7 col-md-6">
                    <input type="text" name="visa_status_code" id="visa_status_code" placeholder="e.g.3112748" class="form-control">
                </div>
            </div>
            <div class="form-group job_category_id">
                <label class="col-lg-4 col-md-5 control-label required" id="job_category_id_label">Visa Job Category</label>
                <div class="col-lg-7 col-md-6">
                    <select name="job_category_id" id="job_category_id" class="form-control select2" placeholder="Select Job Category">
                        <option value=""></option>
                        <option>--Select Job Category--</option>
                        @foreach($jobCategories as $category)
                        <option  value="{{$category->id}}">{{$category->job_category_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
             <div class="form-group visa_issued_date">
                <label class="col-lg-4 col-md-5 control-label required" >Visa Issued Date</label>
                <div class="col-lg-7 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        <input  id="visa_issued_date" name="visa_issued_date" class="form-control dateTimeFormat keypressOff"  placeholder="e.g. yyyy-mm-dd">
                    </div>
                </div>
            </div> 
            <div class="form-group visa_expiry_date">
                <label class="col-lg-4 col-md-5 control-label required" >Visa Expiry Date</label>
                <div class="col-lg-7 col-md-6">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        <input  id="visa_expiry_date" name="visa_expiry_date" class="form-control dateTimeFormat keypressOff"  placeholder="e.g. yyyy-mm-dd">
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
var mobilizeForm = new FormData($('#visaForm')[0])
let refreshUrl = '{{ 'recruitment/mobilization/visa-type/'.$projectId.'/'.$candidateId.'/'.$mobilizeId }}';
$.ajax({
      url: 'recruitment/visa-type',
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