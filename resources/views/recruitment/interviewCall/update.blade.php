<?php $panelTitle = "Interview Call"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal">
    {{csrf_field()}}
    <div class="panel panel-default chart">
        <div class="panel-body">
            <div class="form-group">
                <label class="col-lg-3 col-md-4 control-label required">Project name</label>
                <div class="col-lg-9 col-md-8">
                   {{--  <input autofocus required name="country_name" id="country_name" placeholder="Country Name" class="form-control"> --}}

                    <select  required name="ew_project_id" id="ew_project_id" class="select2 form-control">
                        <option>Choose Project Name</option>
                        @foreach($projects as $project)
                            <option {{ $project->id==$editInterviewCalls->ew_project_id?'selected=selected':'' }}  value="{{ $project->id }}">{{ $project->project_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- <div class="form-group">
                <label class="col-lg-3 col-md-4 control-label required">Start Date </label>
                <div class="col-lg-9 col-md-8">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span> 
                        <input required id="start_date" name="start_date" class="form-control keypressOff  dateTimeFormat" value="{{ date('d-m-Y', strtotime($editInterviewCalls->start_date)) }}" placeholder="Start Date">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-3 col-md-4 control-label required">End Date</label>
                <div class="col-lg-9 col-md-8">
                   <div class="input-group" ><span class="input-group-addon"><i class="fa fa-calendar"></i></span> <input required id="end_date" value="{{ date('d-m-Y', strtotime($editInterviewCalls->end_date)) }}" name="end_date" class="form-control keypressOff  dateTimeFormat" placeholder="End Date"></div>
                  
                </div>
            </div> --}}
        </div>
    </div>
   
</form>

<script type="text/javascript">

    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
    });


/*--------------------------------------
    DATE TIME FORMAT AND KEYPRESS OFF  
----------------------------------------*/
    $('.keypressOff').keypress(function(e) {
        return false
    });


    $('.dateTimeFormat').datepicker({
        format: "dd-mm-yyyy"
    });
/*---------------------------------------
  END  DATE TIME FORMAT AND KEYPRESS OFF
-----------------------------------------*/

</script>