<?php $panelTitle = "Update User"; ?>
<div class="row mt15">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <form id="empUpdate" type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" data-fv-excluded="">
            {{csrf_field()}}
            <input type="hidden" id="emp_id" class="form-control"value="{{@$employee->emp_id}}">
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label class="col-lg-3 col-md-3 control-label required">Employee ID</label>
                    <div class="col-lg-9 col-md-9">
                        <input required name="employee_id" placeholder="Employee ID" class="form-control" value="{{@$employee->employee_id}}">
                    </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label class="col-lg-3 col-md-3 control-label required">Name</label>
                    <div class="col-lg-9 col-md-9">
                        <input required name="name" placeholder="Name" class="form-control" value="{{@$employee->name}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label class="col-lg-3 col-md-3 control-label">Surname</label>
                    <div class="col-lg-9 col-md-9">
                        <input name="surname" placeholder="Surname" class="form-control" value="{{@$employee->surname}}">
                    </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label class="col-lg-3 col-md-3 control-label required">Designation</label>
                    <div class="col-lg-9 col-md-9">
                        <select required name="designation" data-fv-icon="false" add-url="designationAdd" class="select2 @if($employeeDesignationAddAccess){{'select2-add'}}@endif form-control ml0">
                            <option value=""></option>
                            @foreach($employeeDesignation as $employeeDesignation)
                            <option value="{{$employeeDesignation->id}}"  @if(@$employeeDesignation->id==@$employee->designation){{'selected'}}@endif>{{$employeeDesignation->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label class="col-lg-3 col-md-3 control-label">Job Area</label>
                    <div class="col-lg-9 col-md-9">
                        <select id="job_area" name="job_area" data-fv-icon="false" callback="jobAreaAddCallBack" add-url="areaAdd" class="select2 @if($areaAddAccess){{'select2-add'}}@endif form-control ml0">
                            <option value=""></option>
                            @foreach($jobArea as $jobArea)
                            <option value="{{$jobArea->id}}" @if(@$jobArea->id==@$employee->job_area){{'selected'}}@endif>{{$jobArea->area_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                    <label class="col-lg-3 col-md-3 control-label required">Timezone</label>
                    <div class="col-lg-9 col-md-9">
                        <select name="timezone" data-fv-icon="false" class="select2 form-control ml0">
                            @foreach($timezones as $timezone)
                                <option value="{{$timezone->id}}" @if(@$user->timezone_id==$timezone->id) {{'selected'}} @endif > {{$timezone->name}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6 col-md-6 col-xs-12" id="area_details">
                    <label class="col-lg-3 col-md-3 control-label required">Area Details</label>
                    <div class="col-lg-9 col-md-9">
                        <textarea readonly id="areaDe" class="form-control" rows=1></textarea>
                    </div>
                </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12" id="area_details" >
                    <label class="col-lg-3 col-md-3 control-label required">E-mail</label>
                    <div class="col-lg-9 col-md-9">
                        <input required name="email" type="email" placeholder="E-mail" class="form-control" value="{{@$user->email}}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="col-md-offset-3">
                        <div class="form-group mb0">
                            <button type="submit" class="btn btn-default ml10">Update User</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
    var job_areas = <?php echo json_encode($jobArea); ?>;
    var $thisPanel = $("#empUpdate").parents('.panel');
    var csrf_token = $("input[name='_token']").val();
    $(document).ready(function(){
        $(".select2").select2({
            placeholder: "Select"
        });
        
        if(job_areas[<?php echo $employee->job_area; ?>]) {
            $("#area_details").val(job_areas[<?php echo $employee->job_area; ?>].area_details);
        }

        $('#job_area').on('change', function() {
            var job_area_id = $(this).val();
            if(job_area_id && typeof job_areas[job_area_id]!==typeof undefined){
                jobAreaChange(job_area_id);
            }
        });

    });

    function jobAreaAddCallBack(data) {
        job_areas[data.value] = {area_details: data.area_details};
        jobAreaChange(data.value);
    }
    
    function jobAreaChange(job_area_id) {
        var areaDetails = job_areas[job_area_id];
        $("#area_details").val(areaDetails.area_details);
        $("#area_details").show();
    }

</script>
