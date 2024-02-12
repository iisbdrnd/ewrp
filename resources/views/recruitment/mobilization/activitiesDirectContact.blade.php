<div class="row">
    <div class="col-sm-12">
        <form id="directContactForm" callback="callFormRefresh" class="form-load form-horizontal mt0">
             {{csrf_field()}}
             <input type="hidden" name="activities_type" id="activities_type" value="2">
             <input type="hidden" id="projectId"  name="projectId" value="{{ $projectId }}" class="form-control projectId">
            <input type="hidden" id="candidateId"  name="candidateId" value="{{ $candidateId }}" class="form-control candidateId">
            <input type="hidden" id="mobilizeId"  name="mobilizeId" value="{{ $mobilizeId }}" class="form-control mobilizeId">
            <div class="form-group has-feedback">
                <label class="col-lg-2 col-md-3 control-label required">Appointment For</label>
                <div class="col-lg-10 col-md-9">
                    <select name="call_type" id="call_type" class="form-control select2">
                        <option value="0">---Select Call Type---</option>
                        @foreach($mobilizeLists as $mobilizeList)
                        <option value="{{ $mobilizeList->id }}">{{ $mobilizeList->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-3 control-label">Medical Gone Date</label>
                <div class="col-lg-10 col-md-9">
                  <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span> 
                        <input  id="medical_gone_date" name="medical_gone_date"  class="form-control dateTimeFormat keypressOff"  placeholder="Medical Gone Date" autocomplete="off">
                    </div>  
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-2 col-md-3 control-label">Remarks</label>
                <div class="col-lg-10 col-md-9">
                  <textarea name="remarks" id="remarks" data-provide="markdown" data-height="250" data-resize="vertical"  class="form-control" cols="30" rows="5" placeholder="Write remarks"></textarea> 
                </div>
            </div>
           
    </form> 
    <div class="form-group">
        <label class="col-lg-2 col-md-2 "></label>
        <div class="col-lg-10 col-md-10 mt10 mr3">
            <button type="submit" id="directContactButton" class="btn btn-md btn-default">Create Call</button>
        </div>
    </div>
    </div>
</div>

<script type="text/javascript">
$('#directContactButton').on('click', function(){
    var data =  new FormData($('#directContactForm')[0]);
    console.log(data);
    $.ajax({
      url: 'recruitment/activitiesDirectContactStore',
      data: data,
      processData: false,
      contentType: false,
      type: 'POST',
      success: function(data){
        console.log(data);
        $('#directContactForm').trigger("reset");
        // $('#panelId a.panel-refresh').trigger('click');
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

/*--------------------------------------
    DATE TIME FORMAT AND KEYPRESS OFF  
----------------------------------------*/
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