<form type="create" action="{{url('admin/area')}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
		<label class="col-lg-4 col-md-3 control-label required">Area Name</label>
		<div class="col-lg-8 col-md-9">
			<input required name="area_name" placeholder="Area Name" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-4 col-md-3 control-label required">Area Details</label>
		<div class="col-lg-8 col-md-9"><textarea autofocus required name="area_details" class="form-control" rows=3></textarea></div>
	</div>
</form>