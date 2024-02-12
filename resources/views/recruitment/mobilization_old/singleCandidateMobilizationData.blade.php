<div class="row">
	<div class="col-lg-12 col-md-12 sortable-layout ui-sortable">
		<div class="chart">
			<div class="panel-body pt0 pb0">
				<div class="simple-chart">
					<div class="row mt10">
						<div class="col-sm-12"> 
							 @if($access->create)
				            <a href="http://server/eastWestHR_V2/eastWest#candidate-info" class="btn btn-default pull-right btn-sm" type="button" style="margin-left: 12px;"><i class="glyphicon glyphicon-arrow-left mr5"></i>Back to Candidate Info</a>
				            @endif
							<div class="lead-details pb0 mb10">
								<ul>
									<li><strong>Candidate Name:</strong> <a class="ajax-popover ajax-link hand" href="#" menu-active="accounts" data-title="Universal" data-original-title="" title="">
										<a href="" url="reports/view-report/{{ $projectId }}/{{ Helper::single_candidate($candidateId)->id }}" view-type="modal" modal-size="medium" class="add-btn">{{ Helper::single_candidate($candidateId)->full_name }}
										</a>
									</li>
									<li><strong>Close Date:</strong> <span class="opportunity-closed-date">25/12/2017</span></li>
									<li class="pull-right"><a href="{{ url('recruitment#mobilization/mobilization-room/'.$projectId) }}"  class="ajax-link btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> Back To Room</a></li>

									<li class="pull-right"><a href="{{ url('recruitment#reports/candidate-report/'.$projectId) }}" menu-active="reports" class="ajax-link btn btn-sm btn-default"><i class="fa fa-arrow-left"></i> Back To Report</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-lg-12 col-md-12">
		<div refresh-url="" header-load="true" class="panel panel-default panelMove showControls toggle panelClose panelRefresh" id="loadMobilize">
			<div class="panel-body pt0 pb0">
				<div class="bwizard" id="wizard1">
					{{ csrf_field() }}
					<!-- Start .bwizard -->
					<ul class="bwizard-steps">
						<!-- All stages -->
						<?php $i = 0; ?>
						@foreach($mobilizationsLists as $mobilizationsList) 
							@foreach(json_decode($mobilizationsList->mobilization_id) as $mobilization)
								<?php $i++;?>
								<li 
								id="triggerClick{{ $i }}"
								serialId="{{ $i }}" 
								tabItem="{{  $mobilization }}"  
								onclick="
								tabContent('{{ $mobilization }}','{{ json_encode($masterData) }}', '{{ $i }}', '{{ $projectId }}', '{{ $candidateId }}', '{{ count(json_decode($mobilizationsList->mobilization_id)) }}'); 
								activitisContent('{{ $mobilization }}');
								sideNote('{{ $mobilization }}');
								sideAttachment('{{ $mobilization }}');" 
								class="mobilization{{ $mobilization }}
								{{ $masterData->total_completed+1==$i?'activeItem active':'' }}
								mobilization-step{{ $mobilization }}
								{{  $i <= $masterData->total_completed?'completed':'' }}
								"
								 data="{{ $i }}">
								<a  data-toggle="tab" href="#{{ $mobilization }}" aria-expanded="false">
									<span class="step-number">{{ $i }}</span> 
									<span class="step-text" id="mobilizeName{{ $mobilization }}">{{ Helper::single_mobilization($mobilization)->name }} 
									</span>
								</a>
								</li>
								
							@endforeach
							<input type="hidden" id="countTotalMobilization" value="{{ count(json_decode($mobilizationsList->mobilization_id)) }}">
							@if(count(json_decode($mobilizationsList->mobilization_id)) == $masterData->total_completed)
									<li id="closeMobilization" onclick="closedTab();" class="mobilization" data="6"><a data-toggle="tab" href="#6" aria-expanded="true"><span class="step-number">6</span> <span class="step-text">Closed</span></a>
									</li>
								@endif
						@endforeach
						
					</ul>
					<div class="mt10 mb10">
						<div id="loadHtml" class="progress progress-striped" id="bar" style="display:inline-block;width:80%;">
							<div id="stepProgressBar" class="progress-bar progress-bar-info active" style="width:{{($masterData->total_completed*100) / sizeof(json_decode($mobilizationsList->mobilization_id)) }}%;"><span id="percentage"> {{ round(($masterData->total_completed*100) / sizeof(json_decode($mobilizationsList->mobilization_id))) }}%</span>
							</div>
						</div>
						<!-- Confirm btn -->
						<div class="" style="display:inline-block;float:right;">
							<button type="button" class="btn btn-success form-control" id="btn_completed" completed-status project-id candidate-id mobilization-id><span id="mobilizeStepName"></span> Complete </button>
						</div>
					</div>
				</div>
			<!-- End .bwizard -->
			</div>
		</div>
	</div>
</div>
<div class="row tab-content "  id="tab-content" ></div>

<input type="hidden" id="valcheck" value="{{ $masterData->total_completed }}">
<script type="text/javascript">
/*-----------------------------------------------------------------------------
* tabContent() Check function below (first javascript function in this page) &
* this function load tab contain form 
* activitisContent() Check this function define in singleCandidateMobilization
* blade
* sideNote() defined in  singleCandidateMobilization
* sideAttachment() defined in  singleCandidateMobilization		
------------------------------------------------------------------------------*/
/*---------------------------------tabContent()---------------------------------
* Parametter: mobilizeId, data, comfigureSerialId, projectId, candidateId
* mobilizeId catch mobilization actual id
* data return data from master table
* comfigureSerialId serial no. from for loop
* projectId running project id
* candidateId running candidate id.
-------------------------------------------------------------------------------*/
	function tabContent(mobilizeId, data, comfigureSerialId, projectId, candidateId, totalCountMobilization) 
	{	
		$('.mobilizeTemplate').show();
		$('.attachmentTemplate').show();
		$('.noteTemplate').show();
		$('#panelId').attr('refresh-url',"");
		$('#attachmentId').attr('refresh-url',"");
		$('#noteId').attr('refresh-url',"");
		let mobilizationActivitiesUrl = 'mobilization/mobilization-activities/'+projectId+'/'+candidateId+'/'+mobilizeId;
		let activitiesSideNoteUrl = 'mobilization/activitiesSideNote/'+projectId+'/'+candidateId+'/'+mobilizeId;
		let activitiesSideAttachmentUrl = 'mobilization/activitiesSideAttachment/'+projectId+'/'+candidateId+'/'+mobilizeId;

		$('#panelId').attr('refresh-url',mobilizationActivitiesUrl);
		$('#attachmentId').attr('refresh-url',activitiesSideNoteUrl);
		$('#noteId').attr('refresh-url',activitiesSideAttachmentUrl);

		let totalCompleted = '{{ $masterData->total_completed }}';
	console.log(totalCountMobilization+" = "+totalCompleted);	
		console.log(typeof(totalCompleted));
		console.log(typeof($('#valcheck').val()));
		if(Number(comfigureSerialId) <= Number(totalCompleted) ){
		$('#btn_completed').prop('disabled', true);
		$('#btn_completed').removeClass('btn-success');
		$('#btn_completed').addClass('btn-default');

		}else if(eval(Number(totalCompleted)) + 1 == Number(comfigureSerialId)){
		$('#btn_completed').prop('disabled', false);
		$('#btn_completed').removeClass('btn-default');	
		$('#btn_completed').addClass('btn-success');
	}else if(Number(totalCompleted) < Number(comfigureSerialId)){
		$('#btn_completed').prop('disabled', true);
		$('#btn_completed').removeClass('btn-success');
		$('#btn_completed').addClass('btn-default');
	}
	else{
		$('#btn_completed').prop('disabled', false);
		$('#btn_completed').removeClass('btn-default');
		$('#btn_completed').addClass('btn-success');
	}		
		var jsonData = JSON.parse(data);
		var url = 'recruitment/mobilization/single-candidate/{{ $projectId }}/{{ $candidateId }}/'+mobilizeId;
		var refreshUrl = 'mobilization/single-candidate/{{ $projectId }}/{{ $candidateId }}/'+mobilizeId;
		// var serialId = $(this)
		$('#tab-content').load(url);
		$.ajax({
			mimeType: 'text/html; charset=utf-8',
		  	url:url,
		  	data: {mobilizeId:mobilizeId}, 
		  	processData: false,
		  	contentType: false,
		  	type: 'GET',
		  	success: function(data){

		    $('#tabId').attr('refresh-url', refreshUrl);
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		$('#btn_completed').attr('completed-status', comfigureSerialId);	
		  		$('#btn_completed').attr('project-id', projectId);	
		  		$('#btn_completed').attr('candidate-id', candidateId);	
		  		$('#btn_completed').attr('mobilization-id', mobilizeId);	

		  	if(mobilizeId == jsonData.gamca_id && jsonData.gamca_completed){
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.medical_id && jsonData.medical_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.fit_card_id && jsonData.fit_card_received_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.mofa_id && jsonData.mofa_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.visa_document_sent_id && jsonData.visa_document_sent_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.embassy_submission_id && jsonData.embassy_submission_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.visa_id && jsonData.visa_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.visa_online_id && jsonData.visa_online_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.visa_print_id && jsonData.visa_print_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.visa_attached_id && jsonData.visa_attached_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.pcc_id && jsonData.pcc_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.gttc_id && jsonData.gttc_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.fingerprint_status_id && jsonData.fingerprint_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}else if(mobilizeId == jsonData.bmet_id && jsonData.bmet_completed == 1){
		  		$('#mobilizeStepName').text($('#mobilizeName'+mobilizeId).text());
		  		console.log($('#mobilizeName'+mobilizeId).text());
		  		$('.mobilization-step'+mobilizeId).removeClass('active');
		  		$('.mobilization-step'+mobilizeId).addClass('completed');
		  		$('.mobilization-step'+mobilizeId).addClass('active');
		  	}

		   
		  }
		});
	}
/*--------------------------------------------------------------------
* This trigger auto click on mobilization Communication first tab after 
* loading that page.

---------------------------------------------------------------------*/	
// $("#triggerClick1" ).trigger( "click" );
$(document).ready(function(){
let total = '{{ $masterData->total_completed }}';
if(total == 0){
$("#triggerClick1" ).trigger("click");	
}else{
	$('.activeItem').trigger("click");
}
});

function closedTab(){
	$('.mobilizeTemplate').hide();
    $('.attachmentTemplate').hide();
    $('.noteTemplate').hide();
	$('#mobilizeStepName').text("");
	$('#btn_completed').prop('disabled', false);
	$('#btn_completed').removeClass('btn-default');	
	$('#btn_completed').addClass('btn-success');
	$('#btn_completed').attr('url','create-candidate?projectId={{ $projectId }}&candidateId={{ Helper::single_candidate($candidateId)->id }}');
	$('#btn_completed').addClass('add-btn ajax-link');
	// $('#closeMobilization').addClass('active');



}

/*--------------------------------- ---------------------------------
* Action button "Complete" append with mobilization name
* url:(method) mobilizationSigleCompletedStatus check 
* mobilization controller
* This action insert value to mobilization_master_tables 
* That values comes from mobilization serial number(from for loop)
* Value catch by completed-status (check tabContent() above)
* Value insert to completed-status from tabContent() ajax success:
--------------------------------------------------------------------*/
$('#btn_completed').click('click', function(){
let projectId       = $(this).attr('project-id');
let candidateId     = $(this).attr('candidate-id');
let mobilizeId      = $(this).attr('mobilization-id');
let completedStatus = $(this).attr('completed-status');
$.post('recruitment/mobilizationSigleCompletedStatus', {
	'projectId':projectId,
	'candidateId':candidateId,
	'mobilizeId':mobilizeId,
	'completedStatus':completedStatus,
	'_token':$('input[name=_token]').val()
	},function(response){	
	$('.panel-refresh').trigger('click');
	// $('#loadMobilize').load(location.href+' #loadMobilize');
	// let url = 'singleCandidateMobilizationData?projectId=43&candidateId=1';
	// $('#singleMod').attr('load-url', url);
	// $('.activeItem').trigger("click");
	});
	// $('.mobilization'+).removeClass("active");
});

$('.mobilizeTemplate').show();
$('.attachmentTemplate').show();
$('.noteTemplate').show();

let totalCompletedMobilize = '{{ $masterData->total_completed }}';
let totalCountMobilization = $('#countTotalMobilization').val();

if(totalCompletedMobilize == totalCountMobilization){
	$('#closeMobilization').trigger('click');
	$('#closeMobilization').addClass('active');

	// $('.mobilizeTemplate').hide();
	// $('.attachmentTemplate').hide();
	// $('.noteTemplate').hide();	
}
// console.log("Count Total Mobilization "+ $('#countTotalMobilization').val()+"="+tc);


</script>





