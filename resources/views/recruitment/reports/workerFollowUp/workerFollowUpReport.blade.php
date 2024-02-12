<?php $panelTitle = "Worker Follow Up Reports"; ?>
@include("panelStart")
<div class="row main_div">

    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label">By Project</label>
            <div class="col-lg-8 col-md-9">
                <select name="ew_project_id" id="ew_project_id" data-fv-icon="false" class="select2 form-control ml0">
                    <option value=0>All Project</option>
                    @foreach($ewProjects as $ewProject)
                        <option value="{{$ewProject->id}}">{{$ewProject->project_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label">By Dealer</label>
            <div class="col-lg-8 col-md-9" id="project_wise_dealer">
                <select name="by_dealer_id_default" id="by_dealer_id_default" data-fv-icon="false" class="select2 form-control ml0">
                    <option value=0>All Dealer</option>
                </select>
            </div>
        </div>
    </div>

</div>

<div class="row mt5">
    <div class="col-md-6">
        <div class="col-md-offset-3">
            <div class="form-group mb0">
                <button id="reportPreview" class="btn btn-default ml10">Preview</button>
            </div>
        </div>
    </div>
</div>

@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });

        //Dealer SEARCH
        $("#ew_project_id").on('change', function(e) {
            var ew_project_id = $(this).val();
            if (ew_project_id) {
                $.ajax({
                    mimeType: 'text/html; charset=utf-8',
                    url: '{{route("recruit.projectWiseDealer")}}',
                    data: {ew_project_id:ew_project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        $("#project_wise_dealer").html(data);
                        $("#by_dealer_id").select2({ placeholder: "Select" });
                    }
                });
            }
        });

        $("#reportPreview").on('click', function(){
            var width = $(document).width();
            var height = $(document).height();
            var byProjectId = $("#ew_project_id").val();
            var byDealerId = $("#by_dealer_id").val();
            var reportUrl = "{{route('recruit.workerFollowUpView')}}"+'?byProjectId='+byProjectId+ '&byDealerId='+byDealerId;
            var myWindow = window.open(reportUrl, "", "width="+width+",height="+height);
        
        });

    });
</script>