<div class="row">
	<div class="col-lg-12">
		<a href="#"><i class="s12 icomoon-icon-notebook pre-icon"></i></a>
		<h5><a href="{{ url('recruitment#activitiesSideNoteList/'.$projectId.'/'.$candidateId.'/'.$mobilizeId) }}" menu-active="activities/note" class="ajax-link hand">All Notes</a></h5>
		@foreach($callActivityDetails as $callActivityDetail)
		<p>
			Date: {{ $callActivityDetail->call_date }}
			<br>By: {{ Helper::single_candidate($candidateId)->full_name }}
		<br>Note: </p><p><span class="st">
		{{ $callActivityDetail->remarks }}
		</span><br></p><hr>
		@endforeach
	</div>
	<div class="col-md-12 text-center no-opportunities" style="display:none ">
		<div class="attachments-box no-attachments">No Notes</div>
	</div>
</div>
