@include("urlParaMeter")
<?php $tableTitle = Helper::projects($projectId)->project_name; $loadUrl = "mobilizationRoomData?projectId=".$projectId; ?>
@include("dataListFrame")
<div style="display: none;" id="panelTemplatePart">
<?php $panelTitle ="Mobilization"; $refreshUrl=""; $panelId="panelId";?>
@include("panelStart")
<div id="mobilizeTemplate" class="data-list"></div>
@include("panelEnd")
</div>
<script type="text/javascript">

	function mobilizeCandidateList(mobilizeId, i) {
		var current = $('#currentId'+i).attr('currentAttr');
		let url = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData/'.$projectId) }}/'+mobilizeId+'/'+current;
		let refreshUrl = '{{ 'mobilization/mobilizationRoomCandidateData/'.$projectId }}/'+mobilizeId+'/'+current;

	function filteration(){
		$('#mobilizeFiltering').on('change', function(){
			console.log($(this).val());
			let filterData = $(this).val();
			let url = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData/'.$projectId) }}/'+mobilizeId+'/'+filterData;
			let refreshUrl = '{{ 'mobilization/mobilizationRoomCandidateData/'.$projectId }}/'+mobilizeId+'/'+filterData;
			console.log(url); 	    
		    $.ajax({
				mimeType: 'text/html; charset=utf-8',
				type: 'GET',
				url:url,
				data: {'mobilizeId':mobilizeId},
				processData: false,
				contentType: false,
				success: function(data){
				$('#mobilizeTemplate').load(url, filteration);
				$('#panelId').attr('refresh-url', refreshUrl);
				$('#panelTemplatePart').show();
					if(filterData == 1){
						$('#mobilizeFiltering').prop('selected', true);
					}else{
						$('#mobilizeFiltering').prop('selected', true);	
					}
				}
			});
		});
	}
		$.ajax({
			mimeType: 'text/html; charset=utf-8',
			type: 'GET',
			url:url,
			data: {'mobilizeId':mobilizeId},
			processData: false,
			contentType: false,
			success: function(data){
			$('#mobilizeTemplate').load(url, filteration);
			$('#panelId').attr('refresh-url', refreshUrl);
			$('#panelTemplatePart').show();
			console.log(mobilizeId);
			}
		});
	}

 function hideMobilizeList(){
 	// $('#panelTemplatePart').hide();	
 }

	function lateCandidateList(mobilizeId, i) {
		var late = $('#lateId'+i).attr('lateAttr');
		let url = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData/'.$projectId) }}/'+mobilizeId+'/'+late;
		let refreshUrl = '{{ 'mobilization/mobilizationRoomCandidateData/'.$projectId }}/'+mobilizeId+'/'+late;
		
		function filteration(){
		$('#mobilizeFiltering').on('change', function(){
			console.log($(this).val());
			let filterData = $(this).val();
			let url = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData/'.$projectId) }}/'+mobilizeId+'/'+filterData;
			let refreshUrl = '{{ 'mobilization/mobilizationRoomCandidateData/'.$projectId }}/'+mobilizeId+'/'+filterData;

		    $.ajax({
				mimeType: 'text/html; charset=utf-8',
				type: 'GET',
				url:url,
				data: {'mobilizeId':mobilizeId},
				processData: false,
				contentType: false,
				success: function(data){
				$('#mobilizeTemplate').load(url, filteration);
				$('#panelId').attr('refresh-url', refreshUrl);
				$('#panelTemplatePart').show();
				
				console.log(mobilizeId);
				}
			});
		});
	}

		$.ajax({
			mimeType: 'text/html; charset=utf-8',
			type: 'GET',
			url:url,
			data: {'mobilizeId':mobilizeId},
			processData: false,
			contentType: false,
			success: function(data){
			$('#mobilizeTemplate').load(url, filteration);
			$('#panelId').attr('refresh-url', refreshUrl);
			$('#panelTemplatePart').show();
			}
		});
	}


</script>