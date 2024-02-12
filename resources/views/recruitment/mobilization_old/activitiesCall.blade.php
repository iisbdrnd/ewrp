<?php $panelTitle = "Medical Activities";?>
<div class="row">
    <div class="col-sm-12">
        <form type="create"  id="callForm"  panelTitle="{{$panelTitle}}" <?php $refreshUrl=""; $panelId="callId";?> class="form-load form-horizontal mt0">
            {{csrf_field()}}
            <input type="hidden" name="activities_type" id="activities_type" value="1">
            <input type="hidden" id="projectId" name="projectId" value="{{ $projectId }}" class="form-control">
            <input type="hidden" id="candidateId" name="candidateId" value="{{ $candidateId }}" class="form-control">
            <input type="hidden" id="mobilizeId" name="mobilizeId" value="{{ $mobilizeId }}" class="form-control">
            <div class="form-group">
                <label class="col-lg-2 col-md-3 control-label">Call Type</label>
                <div class="col-lg-10 col-md-9">
                   <select name="call_type" id="call_type" class="form-control select2">
                        <option value="0">---Select Call Type---</option>
                        <option value="1">E-Wakala</option>
                        <option value="2">GMCA</option>
                        <option value="3">Online</option>
                        <option value="4">SELF</option>
                        <option value="5">MOFA</option>
                        <option value="6">Flip Card Received</option>
                        <option value="7">PCC</option>
                        <option value="1">Embassy Submission(VISA)</option>
                        <option value="8">Document Sent Onlne</option>
                        <option value="9">Document Sent Print</option>
                        <option value="10">Document Sent Attached</option>
                        <option value="11">GTTC</option>
                        <option value="12">Finger Print</option>
                        <option value="13">BMET</option>
                   </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-3 control-label">Call Date</label>
                <div class="col-lg-10 col-md-9">
                  <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        <input  id="call_date" name="call_date" required  class="form-control dateTimeFormat keypressOff"  placeholder="e.g. 26/11/2018" required>
                    </div>  
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-3 control-label">Invite Date</label>
                <div class="col-lg-10 col-md-9">
                  <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        <input  id="invite_date" name="invite_date"  class="form-control dateTimeFormat keypressOff"  placeholder="e.g. 26/11/2018">
                    </div>  
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-3 control-label">Remarks</label>
                <div class="col-lg-10 col-md-9">
                    <textarea name="remarks" placeholder="remarks" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-2 "></label>
                <div class="col-lg-10 col-md-10 mt3">
                    <button type="button" id="submitButton" class="btn btn-md btn-default">Create Call</button>
                </div>
            </div>
        </form>
        
    </div>
</div>
<script type="text/javascript">
$('#submitButton').on('click', function(){
    var data =  new FormData($('#callForm')[0]);
    let loadUrl = '{{ 'recruitment/mobilization/mobilization-activities/'.$projectId.'/'.$candidateId.'/'.$mobilizeId }}';
    let refreshUrl = '{{ 'mobilization/mobilization-activities/'.$projectId.'/'.$candidateId.'/'.$mobilizeId }}';
    $.ajax({
      url: 'recruitment/call-activities',
      data: data,
      processData: false,
      contentType: false,
      type: 'POST',
      success: function(data){
        $('#panelId').attr('refresh-url', refreshUrl);
        // $('#call-activity').load(location.href+" #callActivity");  
        
        $('#callForm').trigger("reset");
        $.gritter.add({
            title: "Done !!!",
            text: data.messege,
            time: "",
            close_icon: "entypo-icon-cancel s12",
            icon: "icomoon-icon-checkmark-3",
            class_name: "success-notice"
        });
      }
    });
});

 $("select.select2").select2({
            placeholder: "Select"
    });

$('.keypressOff').keypress(function(e) {
        return false
    });


    $('.dateTimeFormat').datepicker({
        format: "yyyy-mm-dd"
    });
</script>
