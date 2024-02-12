<div class="form-inline">
    <div class="row datatables_header">
        <div class="col-md-5 col-xs-12">
            <div class="input-group">
                <input name="search" event="enter" class="data-search form-control" id="search-input" value="{{@$search}}" kl_virtual_keyboard_secure_input="on" placeholder="Search">
                <span class="input-group-btn"><button name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Go</button></span>
            </div>
        </div>
        <div class="col-md-7 col-xs-12">
           {{--  @if($access->create)
                <button class="add-btn btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-plus mr5"></i>Add New</button>
            @endif --}}
            @include("perPageBox")
        </div>
    </div>
    <table id="myTable" cellspacing="0" class="responsive table table-striped table-bordered">
        <thead>
          <tr>
            <th width="1%">No.</th>
            <th data="30%">Candidate Name</th>
            <th width="20%" width="" data="1">Father's Name</th>
            <th width="20%" width="" data="1">Passport Number</th>
            <th width="20%" width="" data="1">Reference</th>
            <th width="9%" width="" data="1">Mobilize Activity</th>
            <th width="9%" width="" data="1">Pipeline</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>No.</th>
            <th>Candidate Name</th>
            <th>Father's Name</th>
            <th>Passport Number</th>
            <th>Reference</th>
            <th>Mobilize Activity</th>
            <th>Pipeline</th>
          </tr>
        </tfoot>
        <tbody>

          <?php $paginate = $candidateLists; $i=0; ?>
          @forelse($candidateLists as $candidateDetail)
          <?php $i++;?>
          <tr>
            <td>{{ $i }}</td>
            <td>
              <a href="mobilization/single-candidate/{{$candidateDetail->ew_project_id}}/{{ $candidateDetail->id }}"
                menu-active="mobilization" class="ajax-link">{{ $candidateDetail->full_name }}
              </a>
            </td>
            <td>{{ $candidateDetail->father_name }}</td>
            <td>{{ $candidateDetail->passport_no }}</td>
            <td>{{ Helper::reference($candidateDetail->reference_id)->reference_name }}</td>
            <td>
                <button type="button" id="viewActivityBtn{{ $candidateDetail->id }}" onclick="mobilizeActivityModalView('{{ $candidateDetail->id }}')" data="{{ $candidateDetail->id }}" btnType="activity"
                    class="btn btn-default btn-sm mobilizeActivity"> Mobilize Activity
                  </button>
            </td>
            <td>
              <a href="mobilization/single-candidate/{{$candidateDetail->ew_project_id}}/{{ $candidateDetail->id }}"
                menu-active="mobilization" class="ajax-link hand btn btn-sm btn-default ">Pipeline
              </a>
            </td>
          </tr>
          @empty
          <tr>
            <td style="border:0px;"></td>
            <td style="border:0px;"></td>
            <td style="border:0px;"></td>
            <td style="border:0px;"></td>
            <td style="border:0px;">Empty</td>
            <td style="border:0px;"></td>
            <td style="border:0px;"></td>
            <td style="border:0px;"></td>
          </tr>
          @endforelse
        </tbody>
      </table>
      <div class="row">
        <div class="col-md-12 col-xs-12">
          @include("pagination")
        </div>
      </div>
</div>
<button style="display:none;"  type="button" id="mobilizeModal" class="btn btn-default btn-sm" data-toggle="modal"
  data-target="#myModal">
  modal
</button>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Mobilize Activity</h4>
      </div>
      <div class="modal-body">
        @include('recruitment.mobilization.onlyActivityForm')
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="mobilizeActivityBtn" class="btn btn-success">Save activity</button>
      </div>
    </div>
  </div>
</div>
<input type="hidden" id="candidateId" value="">
<script type="text/javascript">
$('#mobilizeActivityBtn').on('click', function(){
  var templateType = $('#templateType').val();;
  console.log(templateType);
  if(templateType == 1){
    $('#submitButton').trigger('click'); 
   
  }else if(templateType ==2){
    $('#directContactButton').trigger('click'); 
   
  }else{
     $('#activitiesNoteFormButton').trigger('click'); 
  }
  
});

$('#viewMobilizeActivityData').on('change', function(){
  var activityProjectId   = $('#activityProjectId').val();
  var activityCandidateId = $('#activityCandidateId').val();
  var activityMobilizeId  = $(this).val();
  var templateType        = $('#templateType').val();

  var url ="";
  if(templateType == 1){
     url         = '{{ url('recruitment/mobilization/selected-mobilize-details') }}/'+activityProjectId+'/'+activityCandidateId+'/'+activityMobilizeId; 
  }else if(templateType == 2){
     url   = '{{ url('recruitment/mobilization/direct-contact-details') }}/'+activityProjectId+'/'+activityCandidateId+'/'+activityMobilizeId; 
  }else{

     url = '{{ url('recruitment/mobilization/note-activity-details') }}/'+activityProjectId+'/'+activityCandidateId+'/'+activityMobilizeId; 
  }
  
  $.ajax({
      mimeType: 'text/html; charset=utf-8',
      type: 'GET',
      url:url,
      data: {},
      processData: false,
      contentType: false,
      // dataType:'html',
      success: function(data){
      if(templateType == 1){
        $('#callActivityDetails').load(url); 
      }else if(templateType == 2){
        $('#direct-contact-details').load(url);
      }else{
        $('#noteActivityDetails').load(url);
      }

    }
  });
});

/*----------------------------------------------------------------
**Blow both param id comes from input element which defined
*in mobilizationRoom.blade.php page before starting <scsript> 
*@param projectId 
*@param mobilizeId 
------------------------------------------------------------------*/
var projectId = $('#projectId').val();
var mobilizeId = $('#mobilizeId').val();
/*---------------------------------------------------------------
*modalInfo() using in 
*mobilizationModalView()
*mobilizeActivityModalView()
-----------------------------------------------------------------*/
function modalInfo(candidateId){
  let modalbtn = $('#mobilizeModal').attr('data-target');
  let modal    = $('#myModal').attr('id');
  $('#activityProjectId').val('{{ $projectId }}');
  $('#activityCandidateId').val(candidateId);
  $('#callForm input#projectId').val('{{ $projectId }}');
  $('#callForm input#candidateId').val(candidateId);
  let modalLabel = $('#myModalLabel').addClass('modalTitle');
  if(modalbtn != "" && modal != ""){
    $('#mobilizeModal').attr('data-target','');
    $('#mobilizeModal').attr('data-target', '#myModal'+candidateId);
    $('.modal').attr('id','');
    $('.modal').attr('id','myModal'+candidateId);
    $('.modalTitle').attr('id','modalTitle'+candidateId);
  }  
  $('#mobilizeModal').trigger('click');
  $('#submitButton').hide(); 
}


/*---------------------------------------------------------------
*mobilizeActivityModalView() for showing mobilize activity form
-----------------------------------------------------------------*/
  function mobilizeActivityModalView(candidateId){
    
    $('#pId').val(projectId);
    $('#cId').val(candidateId);
    $('#mId').val(mobilizeId);
    $('#candidateId').val(candidateId);
    let modalViewActivityType =  $('#viewActivityBtn'+candidateId).attr('btnType');
    $("#activities" ).trigger( "click" );
    
    if(modalViewActivityType == "activity"){
      $('#mobilizeFormData').hide();
      $('#detailsPanel').show();
    }

    modalInfo(candidateId);
    $('#mobilizeActivityBtn').show();
    $('#mobilizeBtn').hide();

 
  }
 
  $('#saveBtn').on('click', function(){
    let projectId   = $('#pId').val();
    let candidateId = $('#cId').val();
    let mobilizeId  = $('#mId').val();
    var url ="";

    if(mobilizeId == 1 || mobilizeId == 1){ 
      url = '{{ route('recruit.medical-type') }}';
      }else if(mobilizeId== 4 || mobilizeId== 5 
      || mobilizeId== 6 || mobilizeId== 7 
      || mobilizeId== 10 || mobilizeId== 11 
      || mobilizeId== 12 || mobilizeId== 13 
      || mobilizeId== 14 ||mobilizeId== 15 
      || mobilizeId== 16){
      url = '{{ route('recruit.general-page') }}'; 
      }else if(mobilizeId == 8 || mobilizeId == 9){
      url = '{{ route('recruit.visa-type') }}';
    } 

  $.ajax({
    url:url,
    type:'post',
    data:new FormData($('#mobilizeFormData')[0]),
    processData:false,
    contentType:false,
    success:function(data){
      $.gritter.add({
      title: "Done !!!",
      text: data.messege,
      time: "",
      close_icon: "entypo-icon-cancel s12",
      icon: "icomoon-icon-checkmark-3",
      class_name: "success-notice"
      }); 
    },
    error:function(error){
    }
  });
});



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
  $('.topTabs-label').on('click', function(){
    $('a#callDetails').trigger('click');
    var projectId              = '{{ $projectId }}';
    var candidateId            = $('#candidateId').val();
    let url                    = '{{ route('recruit.call-activities') }}';
    //'{{ route('recruit.selected-mobilize-details') }}';
    let directCallDetailsUrl   = '{{ route('recruit.direct-contact-details') }}';
    let noteActivityDetailsUrl = '{{ route('recruit.note-activity-details') }}';

    $.ajax({
      mimeType: 'text/html; charset=utf-8',
      type: 'GET',
      url:url,
      data: {},
      processData: false,
      contentType: false,
      // dataType:'html',
      success: function(data){
      $('#call-activity').load(url,{
        projectId:projectId,
        candidateId:candidateId,
      }, function(){
        $('form input#projectId').val(projectId);
        $('form input#candidateId').val(candidateId);
        $('a#callForm').trigger('click');
      }); 
      // $('#callActivityDetails').load(callDetailsUrl); 
      // $('#direct-contact-details').load(directCallDetailsUrl);
      // $('#noteActivityDetails').load(noteActivityDetailsUrl);

    }
  });
});

  $('#direct-contact-form').on('click', function(){
    var candidateId = $('#candidateId').val();
    var projectId   = $('#projectId').val();
    var url         = '{{ route('recruit.direct-contact') }}';
      $.ajax({
      mimeType: 'text/html; charset=utf-8',
      type: 'GET',
      url:url,
      data: {},
      processData: false,
      contentType: false,
      success: function(data){
      $('#direct-call-activity-form').load(url, function(){
      $('input.candidateId').val(candidateId);
      $('input.projectId').val(projectId);
      });
      }
    });
  });

  $('#direct-contact-details').on('click', function(){
    var url = '{{ route('recruit.direct-contact-details') }}';
    $.ajax({
      mimeType: 'text/html; charset=utf-8',
      type: 'GET',
      url:url,
      data: {},
      processData: false,
      contentType: false,
      success: function(data){
      $('#direct-contact-details').load(url);
      }
    });
  }); 

  $('#noteActivities').on('click', function(){
    var candidateId = $('#candidateId').val();
    var projectId = $('#projectId').val();
    var url = '{{ route('recruit.note-activities') }}';
    $.ajax({
      mimeType: 'text/html; charset=utf-8',
      type: 'GET',
      url:url,
      data: {},
      processData: false,
      contentType: false,
      success: function(data){
      $('#note-activity-form').load(url, function(){
      $('input#candidateId').val(candidateId);
      $('input#projectId').val(projectId);
      });
      }
    });
  });

  $('#activityDetails').on('click', function(){
    var url = '{{ route('recruit.note-activities') }}';
    $.ajax({
      mimeType: 'text/html; charset=utf-8',
      type: 'GET',
      url:url,
      data: {},
      processData: false,
      contentType: false,
      success: function(data){
      $('#note-activity-form').load(url);
      }
    });
  });

$('#guidance1').show();
$('#guidance2').hide();
$('#invitation').on('click',function(){
$('#guidance1').show();
$('#guidance2').hide();
});
$('#slipReceived').on('click',function(){
$('#guidance2').show();
$('#guidance1').hide();
}); 
</script>0