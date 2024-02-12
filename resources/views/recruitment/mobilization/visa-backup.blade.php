<div id="20" class="tab-pane active">
		<div class="col-lg-7 col-md-7">
			<div class="chart" header-load="false">
				<div class="panel-heading" style="cursor:default;">
					<h4 class="panel-title">{{ Helper::single_mobilization($mobilizeId)->name }}</h4>
					<div class="panel-controls panel-controls-right panel-controls-show">
						<a href="#" 
						id="pmId1" 
						url="mobilization/visa-type/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}" 
						projectId="{{ $projectId }}"
						candidateId="{{ $candidateId }}"
						mobilizeId="{{ $mobilizeId }}"
						visaData="{{ json_encode($visa) }}"
						masterTable="{{ json_encode($masterTableData) }}"
						view-type="modal" 
						modal-size="medium" 
						class="add-btn"
						style="margin-left: 12px;">
						<i class="fa fa-pencil-square-o s16"> Edit</i>
					</a>
					</div>
				</div>

				<div class="panel-body">
					<table class="table table-hover">
						<tbody>
							<tr id="visaOnlineDate">
								<td width="40%"><strong>Visa Online Date:</strong></td>
								<td width="55%"><span class="">{{ @Carbon\Carbon::parse($visa->visa_online_date)->format('d-m-Y') }}</span></td>
								<td width="5%"><a href=""
									id="pmId2" 
									url="mobilization/visa-type/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}" 
									projectId="{{ $projectId }}"
									candidateId="{{ $candidateId }}"
									mobilizeId="{{ $mobilizeId }}"
									visaData="{{ json_encode($visa) }}"
									view-type="modal" 
									class="add-btn" 
									modal-size="medium">
									<i class="fa fa-edit"></i>
								</a>
							</td>
							</tr>
							<tr id="visaStatusCode">
								<td width="40%"><strong>Visa Status Code</strong></td>
								<td width="55%"><span class="">{{ @$visa->visa_online_status_code }}</span></td>
								<td width="5%"><a href=""
									id="" 
									url="mobilization/visa-type/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}" 
									projectId="{{ $projectId }}"
									candidateId="{{ $candidateId }}"
									mobilizeId="{{ $mobilizeId }}"
									visaData="{{ json_encode($visa) }}"
									view-type="modal" 
									class="add-btn" 
									modal-size="medium">
									<i class="fa fa-edit"></i>
								</a>
							</td>
							</tr>
							<tr id="jobCategory">
								<td><strong>Visa Job Category:</strong></td>
								<td><span class="">{{ @Helper::singleJobCategory($visa->visa_online_job_category_id)->job_category_name }}</span></td>
								<td width="5%"><a href=""
									id="pmId3" 
									url="mobilization/visa-type/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}" 
									projectId="{{ $projectId }}"
									candidateId="{{ $candidateId }}"
									mobilizeId="{{ $mobilizeId }}"
									visaData="{{ json_encode($visa) }}"
									view-type="modal" 
									class="add-btn" 
									modal-size="medium">
									<i class="fa fa-edit"></i>
								</a>
							</td>
							</tr>
							<tr id="visaOnlineExpiryDate">
								<td><strong>Visa Online Expiry Date:</strong></td>
								<td> {{ @Carbon\Carbon::parse($visa->visa_online_expiry_date)->format('d-m-Y') }} </td>
								<td width="5%"><a href=""
									id="pmId4" 
									url="mobilization/visa-type/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}" 
									projectId="{{ $projectId }}"
									candidateId="{{ $candidateId }}"
									mobilizeId="{{ $mobilizeId }}"
									visaData="{{ json_encode($visa) }}"
									view-type="modal" 
									class="add-btn" 
									modal-size="medium">
									<i class="fa fa-edit"></i>
								</a>
							</td>
							</tr>
							<tr id="visaExpiryDate">
								<td><strong>Visa Expiry Date:</strong></td>
								<td> {{ @Carbon\Carbon::parse($masterTableVisaData->visa_expiry_date)->format('d-m-Y') }} </td>
								<td width="5%">
								<a href=""
									id="pmId5" 
									url="mobilization/visa-type/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}" 
									projectId="{{ $projectId }}"
									candidateId="{{ $candidateId }}"
									mobilizeId="{{ $mobilizeId }}"
									visaData="{{ json_encode($visa) }}"
									view-type="modal" 
									class="add-btn" 
									modal-size="medium">
									<i class="fa fa-edit"></i>
								</a>
							</td>
							</tr>
							<tr id="visaIssuedDate">
								<td><strong>Visa Issued Date:</strong></td>
								<td><span class="text-default">{{ @Carbon\Carbon::parse($masterTableVisaData->visa_issued_date)->format("d-m-Y") }}</span></td>
									<td width="5%"><a href=""
									id="pmId6" 
									url="mobilization/visa-type/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}" 
									projectId="{{ $projectId }}"
									candidateId="{{ $candidateId }}"
									mobilizeId="{{ $mobilizeId }}"
									visaData="{{ json_encode($visa) }}"
									view-type="modal" 
									class="add-btn" 
									modal-size="medium">
									<i class="fa fa-edit"></i>
								</a>
							</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-5 col-md-5 mb-25">
			<!-- Start .row -->
			<div class="row">
				<div class="col-lg-12">
					<!-- col-lg-12 start here -->
					<div header-load="false" class="panel panel-default  showControls toggle panelClose panelRefresh ">
						<!-- Start .panel -->
						<div class="panel-heading">
							<h4 class="panel-title">Remarks</h4>
						</div>
						<div class="panel-body ">
							<div class="guidance"><strong>Justification Hints at this phase:</strong>
								<br clear="none">
								<i>[Please, analyze your client carefully.]</i>
								<br clear="none">
								<br clear="none">
								<ul style="list-style: none;">
									<li><i class="fa fa-long-arrow-right"></i> Know the client’s business thoroughly.</li>
									<li><i class="fa fa-long-arrow-right"></i> Understand their needs which you want to address.</li>
									<li><i class="fa fa-long-arrow-right"></i> Assume the best way to meet their needs effectively.</li>
									<li><i class="fa fa-long-arrow-right"></i> Set an appropriate time to place the offer.</li>
									<li><i class="fa fa-long-arrow-right"></i> Know their budget level to fit your product.</li>
									<li><i class="fa fa-long-arrow-right"></i> Match your product to meet their needs comfortably.</li>
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
{{-- '#pmId1, #pmId2, #pmId3, #pmId4, #pmId5, #pmId6, #pmId7' --}}
<script type="text/javascript">
let mobilizeId = '{{ $mobilizeId }}';
if(mobilizeId == 8){
$('#visaOnlineDate').hide();
$('#visaStatusCode').hide();
$('#jobCategory').hide();
$('#visaOnlineExpiryDate').hide();
}else if(mobilizeId == 9){
	$('#visaIssuedDate').hide();
	$('#visaExpiryDate').hide();
}

	$('#pmId1, #pmId2, #pmId3, #pmId4, #pmId5, #pmId6, #pmId7').on('click', function(){	
	var projectId    = $(this).attr('projectId');
	var candidateId  = $(this).attr('candidateId');
	var mobilizeId   = $(this).attr('mobilizeId');
	var currentId    = $(this).attr('id');
	var visaData     = $(this).attr('visaData');
	var jsonvisaData = JSON.parse(visaData);
	var masterTableData = $(this).attr('masterTable');
	var jsonMasterData = JSON.parse(masterTableData);
	$.ajax({
		type:'GET',
		url:'recruitment/mobilization/visa-type/'+projectId+'/'+candidateId+'/'+mobilizeId,
		data:{
			projectId      	: projectId, 
			mobilizeId     	: mobilizeId, 
			candidateId    	: candidateId, 
			jsonvisaData 	: jsonvisaData,
			jsonMasterData 	: jsonMasterData
		},
		dataProcess:false,
		contentType:false,
		success:function(data){
		$('#projectId').val(projectId);
		$('#candidateId').val(candidateId);
		$('#mobilizeId').val(mobilizeId);

		function dateFormat(dateItem){
			var dateTime =  moment(dateItem).format("YYYY-MM-DD");
			return dateTime;
		}

		if(mobilizeId == 8){
			$('#visa_issued_date').val(dateFormat(jsonMasterData.visa_issued_date));
			$('#visa_expiry_date').val(dateFormat(jsonMasterData.visa_expiry_date));

			$('.visa_online_date').hide();
			$('.visa_status_code').hide();
			$('.job_category_id').hide();
		}else if(mobilizeId == 9){
			console.log(jsonvisaData);
			$('#visa_online_date').val(dateFormat(jsonvisaData.visa_online_date));
			$('#visa_status_code').val(jsonvisaData.visa_online_status_code);
			$('#visa_expiry_date').val(dateFormat(jsonvisaData.visa_online_expiry_date));

			$('#job_category_id').val(jsonvisaData.visa_online_job_category_id).prop("selected", true)
			//find('s2id_job_category_id').addClass('select2-container-active');

			$('.visa_issued_date').hide();
		}	
			
		// if(currentId == "pmId1"){
		// 	$('.visa_online_date').show();
		// 	$('.visa_status').show();
		// 	$('.job_category_id').show();
		// 	$('.visa_expiry_date').show();
		// }else if(currentId == "pmId2"){
		// 	$('.job_category_id').show();
		// }else if(currentId ==  "pmId4"){
		// 	$('.visa_expiry_date').show();
		// }else if(currentId ==  "pmId5"){
		// 	$('.visa_online_date').show();
		// }else if(currentId ==  "pmId8"){
		// 	$('.visa_issued_date').show();
		// }
		
			
		// 	if(jsonvisaData.visa_actual_status == 1){
		// 		$('#visa_actual_status').val(1).prop("selected", true);
		// 	}else if(jsonvisaData.visa_actual_status == 2){
		// 		$('#visa_actual_status').val(2).prop("selected", true);
		// 	}else{
		// 		$('#visa_actual_status').val(3).prop("selected", true);
		// 	}
			
		}
	});
});

</script>