@include("urlParaMeter")
<?php $tableTitle = @Helper::projects($projectId)->project_name; $loadUrl = "mobilizationRoomData?projectId=".$projectId."&"."projectCountryId=".$projectCountryId; ?>
@include("dataListFrame")
<div style="display: none;" id="panelId">
<?php $panelTitle ="Mobilization"; $refreshUrl=""; $panelId="panelId";?>
@include("panelStart")
<div id="mobilizeTemplate" class="data-list"></div>
@include("panelEnd")
</div>
<!-- This element value are cought from mobilizationRoomCandidateData blade -->
<input type="hidden" id="mobilizeId">
<input type="hidden" id="projectId" value="{{ $projectId }}">
<input type="hidden" id="mobilizeName">
<input type="hidden" id="prevMobilizeId">

<script type="text/javascript">  
$('.heading h3').text('Mobilization');
	function totalCandidates(){
		var candidateIds = $('#total_candidate').attr('candidate-data');
	}

	function mobilizeCandidateList(mobilizeId, i) {
		console.log(mobilizeId);
		
		var prevMobilizeId = $('#search-input'+Number(i - 1)).val();
		$('#prevMobilizeId').val(prevMobilizeId);

		$('#mobilizeTemplate').html("");
			$('#mobilizeTemplate').append('<center><img style="width:70px; height:70px;" src="{{asset('public/img/loading-data.gif')}}"><center>');
		// console.log(mobilizeId);
		var nextMobilizeId = $('.getMobilizeNameById2').attr('nextMobilizeId');

		$('#mobilizeId').val(mobilizeId);
		let mobilizeName = $('.mobilizeName'+mobilizeId).text();
		$('#mobilizeName').val(mobilizeName);
	
		current      = $('#currentId'+i).attr('currentAttr');

    if (mobilizeId == 'candidates') {
			$('.panel-title').text("Selection List").css({'color':'red'});
			$('.mobilize_date_field_name').text("Selection Date");
		}

    if (mobilizeId == 'finalizing') {
    $('.panel-title').text("Finalizing List").css({'color':'red'});
	$('.mobilize_date_field_name').text("Selection Date");
    }

	let url          = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData/'.$projectId) }}/{{$projectCountryId}}/'+mobilizeId+'/'+current;
	let refreshUrl   = '{{ 'mobilization/mobilizationRoomCandidateData/'.$projectId }}/{{$projectCountryId}}/'+mobilizeId+'/'+current;
		
	function filteration(){

		$('.mobilizeFiltering').on('click', function(){
			$('#mobilizeTemplate').html("");
			$('#mobilizeTemplate').append('<center><img style="width:70px; height:70px;" src="{{asset('public/img/loading-data.gif')}}"><center>');
			let filterData = $(this).attr('data');
			let url        = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData/'.$projectId) }}/{{$projectCountryId}}/'+mobilizeId+'/'+filterData;
			let refreshUrl = '{{ 'mobilization/mobilizationRoomCandidateData/'.$projectId }}/{{$projectCountryId}}/{{$projectCountryId}}/'+mobilizeId+'/'+filterData;
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
				$('#panelId').show();
			
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
			$('#panelId').show();
			}
		});
	}




    function goMobilizeCandidateList(mobilizeId) {
		console.log(mobilizeId);

		var gourl        = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData/'.$projectId) }}/{{$projectCountryId}}/'+mobilizeId+'/1';
		
		$.ajax({
			mimeType: 'text/html; charset=utf-8',
			type: 'GET',
			url:gourl,
			data: {'mobilizeId':mobilizeId},
			processData: false,
			contentType: false,
			success: function(data){
			  $('#mobilizeTemplate').load(gourl);
			}
		});
    }



 function hideMobilizeList(){
 	$('#panelId').hide();	
 }

	function lateCandidateList(mobilizeId, i) {
		var late       = $('#lateId'+i).attr('lateAttr');
		let url        = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData/'.$projectId) }}/'+mobilizeId+'/'+late;
		let refreshUrl = '{{ 'mobilization/mobilizationRoomCandidateData/'.$projectId }}/'+mobilizeId+'/'+late;
		
		function filteration(){
		$('button .mobilizeFiltering').on('change', function(){
			let filterData = $(this).val();
			alert(filterData);
			let url        = '{{ url('recruitment/mobilization/mobilizationRoomCandidateData/'.$projectId) }}/'+mobilizeId+'/'+filterData;
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
				$('#panelId').show();
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
		$('#panelId').show();
		}
	});
}
</script>