<form type="create" action="{{url('admin/employeeDesignation')}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
		<label class="col-lg-4 col-md-3 control-label required">Designation Name</label>
		<div class="col-lg-8 col-md-9">
			<input required name="name" placeholder="Designation Name" class="form-control">
		</div>
	</div>
	<div class="form-group">
		<label class="col-lg-4 col-md-3 control-label required">Grade</label>
		<div class="col-lg-8 col-md-9">
			<input required name="grade" placeholder="Grade" class="form-control">
		</div>
	</div>
</form>