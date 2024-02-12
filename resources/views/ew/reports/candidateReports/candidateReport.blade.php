<?php $panelTitle = "Candidate Reports"; ?>
@include("panelStart")
<div class="row main_div">
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label">Preview Type</label>
            <div class="col-lg-8 col-md-9">
                <select name="preview_type" id="preview_type" data-fv-icon="false" class="select2 form-control ml0">
                    <option value=1>ID Wise Candidate List</option>
                    <option value=2>Project Wise Candidate List</option>
                    <option value=3>Reference Wise Candidate List</option>
                    <option value=4>Trade Wise Candidate List</option>
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label">By Project</label>
            <div class="col-lg-8 col-md-9">
                <select name="by_project_id" id="by_project_id" data-fv-icon="false" class="select2 form-control ml0">
                    <option value=0>All Project</option>
                    @foreach($ewProjects as $ewProject)
                        <option value="{{$ewProject->id}}">{{$ewProject->project_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
<div class="row mt5">
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label">By Reference</label>
            <div class="col-lg-8 col-md-9">
                <select name="by_reference_id" id="by_reference_id" data-fv-icon="false" class="select2 form-control ml0">
                    <option value=0>All Reference</option>
                    @foreach($ewReferences as $ewReference)
                        <option value="{{$ewReference->id}}">{{$ewReference->reference_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-3 col-md-3 control-label">By Trade</label>
            <div class="col-lg-8 col-md-9">
                <select name="by_trade_id" id="by_trade_id" data-fv-icon="false" class="select2 form-control ml0">
                    <option value=0>All Trade</option>
                    @foreach($ewTrades as $ewTrade)
                        <option value="{{$ewTrade->id}}">{{$ewTrade->trade_name}}</option>
                    @endforeach
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

        $("#reportPreview").on('click', function(){
            var width = $(document).width();
            var height = $(document).height();
            var previewType = $("#preview_type").val();
            var byProjectId = $("#by_project_id").val();
            var byReferenceId = $("#by_reference_id").val();
            var byTradeId = $("#by_trade_id").val();
            var reportUrl = "{{route('ew.candidateReportView')}}"+'?previewType='+previewType+ '&byProjectId='+byProjectId+ '&byReferenceId='+byReferenceId+ '&byTradeId='+byTradeId;
            var myWindow = window.open(reportUrl, "", "width="+width+",height="+height);
        
        });

    });
</script>