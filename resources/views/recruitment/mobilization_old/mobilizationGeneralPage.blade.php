<div id="18" class="tab-pane active">
	<div class="col-lg-7 col-md-7">
		<div class="chart" header-load="true">
			<div class="panel-heading" style="cursor:default;">
				<input type="hidden" id="mobilizeIds" value="{{$mobilizeId}}">
				<h4 class="panel-title">{{ Helper::single_mobilization($mobilizeId)->name }}</h4>
				<div class="panel-controls panel-controls-right panel-controls-show">
					<a href="#"
						id="create_mobilize"
						url="mobilization/general-page/{{ $projectId }}/{{ $candidateId }}/{{ $mobilizeId }}"
						projectId="{{ $projectId }}"
						candidateId="{{ $candidateId }}"
						mobilizeId="{{ $mobilizeId }}"
						jsonData="{{ json_encode($masterTableData) }}"
						view-type="modal"
						modal-size="medium"
						class="add-btn"
						style="margin-left: 12px;">
						<i class="fa fa-pencil-square-o s16"> Edit</i>
					</a>
				</div>
			</div>
			<div id="myTabContent2" class="tab-content">
				<div class="tab-pane fade active in form-inline" id=home2>
					<div class="form-inline data-table" refresh-url="mobilization/general-page/{{$projectId}}/{{$candidateId}}/{{$mobilizeId}}">
					<table cellspacing="0" class="responsive table table-striped table-bordered">
						<tbody>
							<tr id="mobiization_status_row">
								<td style="border: none;" width="40%"><strong class="mobiization_status_label">{{ Helper::single_mobilization($mobilizeId)->name }} Status:</strong></td>
								<td style="border: none;" width="60%"><span class="">
									@if($mobilizeId == 16)
									{{ $masterTableData->gamca_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 4)
									{{ $masterTableData->fit_card_received_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 5)
									{{ $masterTableData->mofa_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 6)
									{{ $masterTableData->visa_document_sent_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 7)
									{{ $masterTableData->embassy_submission_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 10)
									{{ $masterTableData->visa_print_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 11)
									{{ $masterTableData->visa_attached_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 12)
									{{ $masterTableData->pcc_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 13)
									{{ $masterTableData->gttc_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 14)
									{{ $masterTableData->fingerprint_status == 1?'Yes':'No' }}
									@elseif($mobilizeId == 15)
									{{ $masterTableData->bmet_status == 1?'Yes':'No' }}
									@else
									@endif
								</span></td>
							</tr>
							<tr id="mobiization_date_row">
								<td style="border: none;" width="40%"><strong class="mobiization_date_label">{{ Helper::single_mobilization($mobilizeId)->name }} Date:</strong></td>
								<td style="border: none;" width="60%"><span class="">
									@if($mobilizeId == 16)
									{{ $masterTableData->gamca_status == 1?date('d-m-Y', strtotime($masterTableData->gamca_gone_date)):'' }}
									@elseif($mobilizeId == 4)
									{{ $masterTableData->fit_card_received_status == 1?date('d-m-Y', strtotime($masterTableData->fit_card_received_date)):'' }}
									@elseif($mobilizeId == 5)
									{{ $masterTableData->mofa_status == 1?date('d-m-Y', strtotime($masterTableData->mofa_date)):'' }}
									@elseif($mobilizeId == 6)
									{{ $masterTableData->visa_document_sent_status == 1?date('d-m-Y', strtotime($masterTableData->visa_document_sent_date)):'' }}
									@elseif($mobilizeId == 7)
									{{ $masterTableData->embassy_submission_status == 1?date('d-m-Y', strtotime($masterTableData->embassy_submission_date)):'' }}
									@elseif($mobilizeId == 10)
									{{ $masterTableData->visa_print_status == 1?date('d-m-Y', strtotime($masterTableData->visa_print_date)):'' }}
									@elseif($mobilizeId == 11)
									{{ $masterTableData->visa_attached_status == 1?date('d-m-Y', strtotime($masterTableData->visa_attached_date)):'' }}
									@elseif($mobilizeId == 12)
									{{ $masterTableData->pcc_status == 1?date('d-m-Y', strtotime($masterTableData->pcc_date)):'' }}
									@elseif($mobilizeId == 13)
									{{ $masterTableData->gttc_status == 1?date('d-m-Y', strtotime($masterTableData->gttc_date)):'' }}
									@elseif($mobilizeId == 14)
									{{ $masterTableData->fingerprint_status == 1?date('d-m-Y', strtotime($masterTableData->fingerprint_date)):'' }}
									@elseif($mobilizeId == 15)
									{{ $masterTableData->bmet_status == 1?date('d-m-Y', strtotime($masterTableData->bmet_date)):'' }}
									@else
									@endif
								</span></td>
							</tr>
						</tbody>
					</table>
				</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-5 col-md-5 mb-25">
		<!-- Start .row -->
		<div class="row">
			<div class="col-lg-12">
				<!-- col-lg-12 start here -->
				<div header-load="true" class="panel panel-default  showControls toggle panelClose panelRefresh ">
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
<script type="text/javascript">
let mobilize_id = '{{ $mobilizeId }}';
function agreementStatus(data) {
        bootbox.hideAll();
    }
$('#create_mobilize').on('click', function(){
	let projectId       = $(this).attr('projectId');
	let candidateId     = $(this).attr('candidateId');
	let mobilizeId      = $(this).attr('mobilizeId');
	let masterTableData = $(this).attr('jsonData');
		let jsonData        =	JSON.parse(masterTableData);
	$.ajax({
		type:'GET',
		url:'recruitment/mobilization/general-page/'+projectId+'/'+candidateId+'/'+mobilizeId,
		data:{
				projectId      	: projectId,
				mobilizeId  	: mobilizeId,
				candidateId    	: candidateId,
				jsonData    	: jsonData
			
		},
		dataProcess:false,
		contentType:false,
		success:function(data){
			function dateFormat(dateItem){
				var dateTime = moment(dateItem).format("YYYY-MM-DD");
				return dateTime;
			}
		if(jsonData  !=null){
			if(mobilize_id == 16){
				$('#mobilize_date').val(dateFormat(jsonData.gamca_gone_date));
			}else if(mobilize_id == 4){
					$('#mobilize_date').val(dateFormat(jsonData.fit_card_received_date));
			}else if(mobilize_id == 5){
					$('#mobilize_date').val(dateFormat(jsonData.mofa_date));
			}else if(mobilize_id == 6){
					$('#mobilize_date').val(dateFormat(jsonData.visa_document_sent_date));
			}else if(mobilize_id == 7){
					$('#mobilize_date').val(dateFormat(jsonData.embassy_submission_date));
			}else if(mobilize_id == 10){
					$('#mobilize_date').val(dateFormat(jsonData.visa_print_date));
			}else if(mobilize_id == 11){
					$('#mobilize_date').val(dateFormat(jsonData.visa_attached_date));
			}else if(mobilize_id == 12){
					$('#mobilize_date').val(dateFormat(jsonData.pcc_date));
			}else if(mobilize_id == 13){
					$('#mobilize_date').val(dateFormat(jsonData.gttc_date));
			}else if(mobilize_id == 14){
					$('#mobilize_date').val(dateFormat(jsonData.fingerprint_date));
			}else if(mobilize_id == 15){
					$('#mobilize_date').val(dateFormat(jsonData.bmet_date));
			}
		}
		$('#mobilizeDate').text("");
		$('#mobilizeDate').text("{{ Helper::single_mobilization($mobilizeId)->name }} Date");
				
		}
	});
});
</script>
