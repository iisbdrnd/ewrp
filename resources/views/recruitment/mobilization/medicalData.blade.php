<div id="21" class="tab-pane active">
		<div class="col-lg-7 col-md-7">
			<div class="panel panel-default chart" header-load="false">
				<div class="panel-heading" style="cursor:default;">
					<h4 class="panel-title">{{ Helper::single_mobilization($mobilizeId)->name }}</h4>
					<div class="panel-controls panel-controls-right panel-controls-show">
						<a href="#" 
						id="pmId" 
						url="mobilization/medical-type/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}" 
						projectId="{{ $projectId }}"
						candidateId="{{ $candidateId }}"
						mobilizeId="{{ $mobilizeId }}"
						medicalData="{{ json_encode($medical) }}"
						view-type="modal" 
						modal-size="medium" 
						class="add-btn"

						style="margin-left: 12px;">
						<i class="fa fa-pencil-square-o s16"> Edit</i>
					</a>
					</div>
				</div>

				<div class="in" id="home2">
					<div class="panel-body">
						<table class="table table-hover">
							<tbody>
								<tr>
									<td width="40%">Medical Gone Date:</td>
									<td width="60%">{{ @$medical->medical_gone_date }}</td>
								</tr>
								<tr>
									<td width="40%">Medical Name:</td>
									<td width="60%">{{ @$medical->medical_name }}</td>
								</tr>
								<tr>
									<td width="40%">Medical Code:</td>
									<td width="60%">{{ @$medical->medical_code }}</td>
								</tr>
								<tr>
									<td width="40%">Medical Report Date:</td>
									<td width="60%">{{  @$medical->medical_report_date }}</td>
								</tr>
								<tr>
									<td width="40%">Medical Flip Card Received:</td>
									<td width="60%">{{  @$medical->flip_card_received==1?'Yes':(@$medical->flip_card_received==2?'No':'') }}</td>
								</tr>
								<tr>
									<td width="40%">Actual Medical Status:</td>
									<td width="60%">{{  @$medical->medical_actual_status==1?'Fit':(@$medical->medical_actual_status==2?'Agent Fit':(@$medical->medical_actual_status==3?'Unfit':'')) }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-5 col-md-5 mb-25">
			<!-- Start .row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div header-load="false" class="panel panel-default  showControls toggle panelClose panelRefresh " >
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">Remarks</h4>
						</div>
						<div class="panel-body ">
							<div class="guidance"><strong>Justification Hints at this phase:</strong>
								<br clear="none">
								<i>[Please, understand your client carefully.]</i>
								<br clear="none">
								<br clear="none">
								<ul style="list-style: none;">
									<li><i class="fa fa-long-arrow-right"></i> Client’s needs are not verified yet.</li>
									<li><i class="fa fa-long-arrow-right"></i> Business type, that is, existing or new, has not yet ascertained. </li>
									<li><i class="fa fa-long-arrow-right"></i> Source of the opportunity is not known yet.</li>
								</ul>
							</div>
						</div>
					</div>
					<!-- End .panel -->
				</div>
			</div>
			<!-- End .row -->
		</div>
	</div>

   <div class="col-lg-7 col-md-7">
      <!-- Start .row -->
      @include('recruitment.mobilization.medicalActivities')
      <!-- End .row -->
    
   </div>
<script type="text/javascript">
	$('#pmId').on('click', function(){
	var projectId       = $(this).attr('projectId');
	var candidateId     = $(this).attr('candidateId');
	var mobilizeId      = $(this).attr('mobilizeId');
	var medicalData     = $(this).attr('medicalData');
	var jsonMedicalData = JSON.parse(medicalData);
	$.ajax({
		type:'GET',
		url:'recruitment/mobilization/medical-type/'+projectId+'/'+candidateId+'/'+mobilizeId,
		data:{
			projectId      	: projectId, 
			mobilizeId     	: mobilizeId, 
			candidateId    	: candidateId, 
			jsonMedicalData : jsonMedicalData
		},
		dataProcess:false,
		contentType:false,
		success:function(data){
			$('#projectId').val(projectId);
			$('#candidateId').val(candidateId);
			$('#mobilizeId').val(mobilizeId);

			if(jsonMedicalData != null){
			$('#medical_name').val(jsonMedicalData.medical_name);	
			$('#medical_code').val(jsonMedicalData.medical_code);
			$('#medical_gone_date').val(jsonMedicalData.medical_gone_date);
			$('#medical_report_date').val(jsonMedicalData.medical_report_date);
				if(jsonMedicalData.medical_actual_status == 1){
					$('#medical_actual_status').val(1).prop("selected", true);
				}else if(jsonMedicalData.medical_actual_status == 2){
					$('#medical_actual_status').val(2).prop("selected", true);
				}else{
					$('#medical_actual_status').val(3).prop("selected", true);
				}
				if(jsonMedicalData.flip_card_received==1){
					$('#flip_card_received').val(1).prop("selected", true);
				}else{
					$('#flip_card_received').val(2).prop("selected", true);
				}
			}
		}
	});
});

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
        return false;
    });


    $('.dateTimeFormat').datepicker({
        format: "yyyy-mm-dd"
    });
/*---------------------------------------
  END  DATE TIME FORMAT AND KEYPRESS OFF
-----------------------------------------*/

</script>