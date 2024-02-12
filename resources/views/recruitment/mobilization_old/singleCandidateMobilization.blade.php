@include("urlParaMeter")
<?php $tableTitle =Helper::projects(Helper::single_candidate($candidateId)->ew_project_id)->project_name ;
$dataTableId="singleMod"; $refreshUrl=""; $loadUrl = "singleCandidateMobilizationData?projectId=".$projectId."&"."candidateId=".$candidateId; ?>
@include("dataListFrame")
<div class="row">
	<h1></h1>
	<div class="col-lg-8 col-md-8 mobilizeTemplate">
		<?php $panelTitle ="Mobilization Activities";  $refreshUrl=""; $panelId="panelId";?>
		@include("panelStart")
		<div id="mobilizeTemplate"></div> 
		@include("panelEnd")
	</div>
	<div class="col-lg-4 sortable-layout ui-sortable attachmentTemplate">
		<?php $panelTitle ="Files"; $refreshUrl=""; $panelId="attachmentId";?>
		@include("panelStart")
		<div id="attachmentTemplate"></div>
		@include("panelEnd")
	</div>
	<div class="col-lg-4 sortable-layout ui-sortable pull-right noteTemplate">
		<?php $panelTitle ="Note"; $refreshUrl=""; $panelId="noteId";?>
		@include("panelStart")
		<div id="noteTemplate"></div>
		@include("panelEnd")
	</div>
</div>
<script type="text/javascript">
	
	function activitisContent(i) {
			let url = '{{ url('recruitment/mobilization/mobilization-activities/'.$projectId.'/'.$candidateId) }}/'+i;
			let refreshUrl = '{{ 'mobilization/mobilization-activities/'.$projectId.'/'.$candidateId }}/'+i;
		$.ajax({
			mimeType: 'text/html; charset=utf-8',
			type: 'GET',
			url:url,
			data: {mobilizeId:i},
			processData: false,
			contentType: false,
			success: function(data){
			$('#mobilizeTemplate').load(url);
			$('#panelId').attr('refresh-url', refreshUrl);
			}
		});
	}

	function sideNote(i){
		let url = '{{ url('recruitment/mobilization/activitiesSideNote/'.$projectId.'/'.$candidateId) }}/'+i;
		let refreshUrl = '{{ 'mobilization/activitiesSideNote/'.$projectId.'/'.$candidateId }}/'+i;
			$.ajax({
				mimeType: 'text/html; charset=utf-8',
				type: 'GET',
				url:url,
				data: {mobilizeId:i},
				processData: false,
				contentType: false,
				success: function(data){
				$('#noteTemplate').load(url);
				$('#noteId').attr('refresh-url', refreshUrl);
				}
		});
	}

	function sideAttachment(i){
		let url = '{{ url('recruitment/mobilization/activitiesSideAttachment/'.$projectId.'/'.$candidateId) }}/'+i;
		let refreshUrl = '{{ 'mobilization/activitiesSideAttachment/'.$projectId.'/'.$candidateId }}/'+i;
			$.ajax({
				mimeType: 'text/html; charset=utf-8',
				type: 'GET',
				url:url,
				data: {mobilizeId:i},
				processData: false,
				contentType: false,
				success: function(data){
				$('#attachmentTemplate').load(url);
				$('#attachmentId').attr('refresh-url', refreshUrl);
				}
		});
	}

	function agreementStatus(data) {
	        bootbox.hideAll();
	    }	
</script>