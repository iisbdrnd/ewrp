<?php $panelTitle = "Select planner"; ?>
<style type="text/css">
	.form-check{
		padding: 5px;
		margin:5px;
	}
</style>
@include("panelStart")
	<form id="plannerForm" type="create" class="form-load form-horizontal" data-fv-excluded="" action="{{route('provider.admin.provider.admin.slectActionplannerAction')}}" callback="modalRefresh">
	    {{csrf_field()}}
	    @if(count($planners)>0)  
	    @foreach($planners as $planner)
    		<div class="form-check">
			  <input class="form-check-input" type="radio" name="planner_id" id="planner_id" value="{{$planner->id}}" @if($planner->contact_person_status==1) checked @endif>
			  <label class="form-check-label" for="planner_id">
			    {{$planner->name}}
			  </label>
			</div>
	    @endforeach
	    @else    
	        <p class="emptyMessage">No Data Found</p>
	    @endif
    </form>
<script type="text/javascript">

function modalRefresh(){
	$('.modal').modal('hide');
}
</script>
@include("panelEnd")
