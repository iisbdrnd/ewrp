<?php $panelTitle = "Employee Create"; ?>
@include("panelStart")
<form id="empCreate" type="create" callback="afterEmpSave" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Employee ID</label>
        <div class="col-lg-10 col-md-9">
            <input readonly id="emp_id" placeholder="Employee ID" class="form-control"value="{{$empId}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="name" placeholder="Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Surname</label>
        <div class="col-lg-10 col-md-9">
            <input name="surname" placeholder="Surname" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Department</label>
        <div class="col-lg-10 col-md-9">
            <select required name="department_id" data-fv-icon="false" class="select2   form-control ml0">
                <option value=""></option>
                @foreach($departments as $department)
                <option value="{{$department->id}}">{{$department->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Designation</label>
        <div class="col-lg-10 col-md-9">
            <select required name="designation" data-fv-icon="false" add-url="designationAdd" class="select2 @if($employeeDesignationAddAccess){{'select2-add'}}@endif  form-control ml0">
                <option value=""></option>
                @foreach($employeeDesignation as $employeeDesign)
                <option value="{{$employeeDesign->id}}">{{$employeeDesign->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Timezone</label>
        <div class="col-lg-10 col-md-9">
            <select name="timezone" data-fv-icon="false" class="select2 form-control ml0" required>
                <option value=""></option>
                @foreach($timezones as $timezone)
                    <option value="{{$timezone->id}}"> {{$timezone->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Report To</label>
        <div class="col-lg-10 col-md-9">
            <select required name="report_to" data-fv-icon="false" class="select2 form-control ml0">
                <option value="0">N/A</option>
                @foreach($reportTo as $reportTo)
                <option value="{{$reportTo->user_id}}">{{$reportTo->name}} [{{$reportTo->designation_name}}]</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">E-mail</label>
        <div class="col-lg-10 col-md-9">
            <input required name="email" type="email" placeholder="E-mail" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create</button>
        </div>
    </div>
</form>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
    });

    function afterEmpSave(data) {
        $("#emp_id").val(data.empId);
    }
</script>
