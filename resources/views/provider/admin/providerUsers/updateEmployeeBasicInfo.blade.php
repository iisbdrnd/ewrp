<?php $panelTitle = "Employee Update"; ?>
<form id="empUpdate" type="update" callback="afterEmpSave" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" data-fv-excluded="">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Employee ID</label>
        <div class="col-lg-10 col-md-9">
            <input readonly id="emp_id" placeholder="Employee ID" class="form-control"value="{{@$employee->emp_id}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-10 col-md-9">
            <input required name="name" placeholder="Name" class="form-control" value="{{@$employee->name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Surname</label>
        <div class="col-lg-10 col-md-9">
            <input name="surname" placeholder="Surname" class="form-control" value="{{@$employee->surname}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Department</label>
        <div class="col-lg-10 col-md-9">
            <select required name="department_id" data-fv-icon="false" add-url="designationAdd" class="select2 form-control ml0">
                <option value=""></option>
                @foreach($departments as $department)
                <option value="{{$department->id}}"  @if(@$department->id==@$employee->department_id){{'selected'}}@endif>{{$department->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Designation</label>
        <div class="col-lg-10 col-md-9">
            <select required name="designation" data-fv-icon="false" add-url="designationAdd" class="select2 @if($employeeDesignationAddAccess){{'select2-add'}}@endif form-control ml0">
                <option value=""></option>
                @foreach($employeeDesignation as $employeeDesignation)
                <option value="{{$employeeDesignation->id}}"  @if(@$employeeDesignation->id==@$employee->designation){{'selected'}}@endif>{{$employeeDesignation->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Timezone</label>
        <div class="col-lg-10 col-md-9">
            <select name="timezone" data-fv-icon="false" class="select2 form-control ml0">
                @foreach($timezones as $timezone)
                    <option value="{{$timezone->id}}" @if(@$user->timezone_id==$timezone->id) {{'selected'}} @endif > {{$timezone->name}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Report To</label>
        <div class="col-lg-10 col-md-9">
            <select required name="report_to" data-fv-icon="false" class="select2 form-control ml0">
                <option value="0">N/A</option>
                @foreach($reportTo as $report)
                <option value="{{@$report->user_id}}" @if(@$report->user_id==@$employee->report_to){{'selected'}}@endif>{{$report->name}} [{{$report->designation_name}}]</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">E-mail</label>
        <div class="col-lg-10 col-md-9">
            <input required name="email" type="email" placeholder="E-mail" class="form-control" value="{{@$user->email}}">
        </div>
    </div>
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    var $thisPanel = $("#empUpdate").parents('.panel');
    var csrf_token = $("input[name='_token']").val();
    $(document).ready(function(){
        $(".select2").select2({
            placeholder: "Select"
        });
    });

    function afterEmpSave(data) {
        if(data.compile) {
            swal({
                title: "",
                text: "Employee has been updated, but you have need to compile organogram",
                type: "success",
                showCancelButton: false,
                confirmButtonClass: "btn-success",
                confirmButtonText: "Ok, compile now!",
                closeOnConfirm: true
            },
            function(){
                $thisPanel.find(".panel-body").html('<div id="container"><div id="topLoader"></div></div>');
                //Compile
                var $topLoader = $("#topLoader").percentageLoader({width: 256, height: 256, controllable : true, progress : 0, onProgressUpdate : function(val) {
                    $topLoader.setValue(Math.round(val * 100.0));
                }});

                var totalKb = parseInt(data.ttlUser)*50;
                if(totalKb<500) { totalKb = 500; }
                var topLoaderRunning = true;
                var kb = 0;
                $topLoader.setProgress(0);
                $topLoader.setValue('0kb');
                setTimeout(animateLoader, 25);

                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: "{{route('provider.admin.compileEmployeeOrganogram')}}",
                    data: {'_token': csrf_token},
                    cache: false,
                    error: function (xhr, ajaxOptions, thrownError) {
                        swal("Cancelled", thrownError, "error");
                    },
                    complete: function () {
                        kb = totalKb;
                        animateLoader();
                        topLoaderRunning = false;
                    },
                    success: function (data) {
                        swal({
                            title: "",
                            text: "Compile has been completed",
                            type: "success",
                            showCancelButton: false,
                            confirmButtonClass: "btn-success",
                            confirmButtonText: "Ok!",
                            closeOnConfirm: true
                        },
                        function(){
                            $thisPanel.find(".panel-refresh").trigger("click");
                        });
                    }
                });

                var animateLoader = function() {
                    if (topLoaderRunning) {
                        if((kb / totalKb)<0.98) {
                            kb += 17;
                        }
                        $topLoader.setProgress(kb / totalKb);
                        var userKb = Math.round(kb/4);
                        $topLoader.setValue(userKb.toString() + 'kb');

                        if (kb < totalKb) {
                            setTimeout(animateLoader, 25);
                        } else {
                            topLoaderRunning = false;
                        }
                    }
                }
            });
        }
    }

</script>