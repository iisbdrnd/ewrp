<?php $panelTitle = "Amount Status Report"; $panelBodyClass = 'pl0 pr0 pb0'; $refreshCallBack = 'amountStatusRefresh'; ?>
@include("panelStart")
<form id="amountStatusReportForm" method="post" panelTitle="{{$panelTitle}}" type="view" class="form-horizontal" data-fv-excluded="">
    {{csrf_field()}}
    <div class="row mt15">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-7 col-md-12 sortable-layout col-no-pr"><!--Left Side Box-->
                <div class="panel panel-default chart">
                    <div class="panel-body">
                        <div class=simple-chart>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-4 control-label">Project</label>
                                <div class="col-lg-10 col-md-8">
                                    <select id="ew_project_id" name="project_id" data-fv-icon="false" class="select2 form-control ml0">
                                        <option value="">All Project</option>
                                        @foreach($ewProjects as $ewProjects)
                                        <option value="{{$ewProjects->id}}">{{$ewProjects->project_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-2 col-md-4 control-label">Candidate</label>
                                <div class="col-lg-7 col-md-8 col-no-pr">
                                  <div id="project_candidate_id">
                                    <select name="candidate_id" id="candidate_id" data-fv-icon="false" class="select2 form-control ml0">
                                        <option value="">All Candidates</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-lg-3 col-md-4">
                                    <button id="candidate_add_button" type="button" class="btn btn-default ml8 btn-ml0">Add</button>
                                    <button id="preview_button" type="submit" class="btn btn-default ml8 btn-ml0">Preview</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--CANDIDATE DETAILS RISHT SIDE BOX-->
            <div class="col-lg-5 col-md-12 sortable-layout candidateInfo">
                <div class="panel panel-default chart">
                    <div class="panel-body">
                        <div class="simple-chart" style="line-height: 25px;">
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-5">Father's Name</div>
                                <div class="col-md-9 col-sm-9 col-xs-7">: <span class="fName"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-5">Reference</div>
                                <div class="col-md-9 col-sm-9 col-xs-7">: <span class="reference"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-5">Dealer</div>
                                <div class="col-md-9 col-sm-9 col-xs-7">: <span class="dealer"></span></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 col-sm-3 col-xs-5">Trade</div>
                                <div class="col-md-9 col-sm-9 col-xs-7">: <span class="trade"></span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@include("panelEnd")

@if(empty($inputData['takeContent']))
<div class=row>
    <div class="col-lg-12">
        <!-- col-lg-12 start here -->
        <div class="panel panel-default">
            <!-- Start .panel -->
            <div class="panel-heading">
                <h4 class="panel-title">Candidate List</h4>
            </div>
            <div class="panel-body data-list">
                <div id=myTabContent2 class=tab-content>
                    <div class="tab-pane fade active in" id=home2>
                        <div class="form-inline">
                            <form id="candidateBoxForm">
                                <table class="responsive table table-striped table-bordered" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="30%">Project</th>
                                            <th>Candidate</th>
                                            <th width="5%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Project</th>
                                            <th>Candidate</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </tfoot>
                                    <tbody id="canListBody">
                                        <tr><td colspan="3" class="emptyMessage">Empty</td></tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End .panel -->
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $("#candidateBoxForm").on('click', '.remove', function(){
            var rm_projectId = $(this).attr('data');
            var $firstProjectTR = $("#canListBody").find('.'+'project-'+rm_projectId).first();
            var $firstProjectTD = $firstProjectTR.find('td').first();
            var projctHtml = $firstProjectTD.html();
            var projctRowspan = parseInt($firstProjectTD.attr('rowspan'))-1;

            $(this).parents('tr').first().remove();

            var $projectTR = $("#canListBody").find('tr');
            if($projectTR.length>0) {
                var $firstProjectTR = $("#canListBody").find('.'+'project-'+rm_projectId).first();
                if($firstProjectTR.length>0) {
                    var $firstProjectTD = $firstProjectTR.find('td').first();
                    if(typeof $firstProjectTD.attr('rowspan') === 'undefined') {
                        $firstProjectTR.prepend('<td>'+projctHtml+'</td>');
                        $firstProjectTR.find('td').first().attr('rowspan', projctRowspan);
                    } else {
                        $firstProjectTD.attr('rowspan', projctRowspan);
                    }
                }
            } else {
                amountStatusRefresh();
            }
        });
    });
</script>
@endif

<script type="text/javascript">
    $(document).ready(function() {
        //Candidate Box
        scolling($(".data-list"));
        var candidates = JSON.parse('<?php echo $candidates; ?>');

        $("#candidate_add_button").click(function(){
            var candidateId = $("#candidate_id").val();
            if(candidateId) {
                var emptyMessage = $("#canListBody").find('.emptyMessage');
                if(emptyMessage.length>0) { $("#canListBody").html(''); }
                $.each(candidateId, function(index, canId){
                    if(typeof candidates[canId] !== 'undefined') {
                        var canInfo = candidates[canId];
                        var canTR = $("#canListBody").find('.'+'can-'+canId);
                        if(canTR.length<=0) {
                            var projectTR = $("#canListBody").find('.'+'project-'+canInfo.ew_project_id);
                            if(projectTR.length>0) {
                                var $firstProjectTR = $("#canListBody").find('.'+'project-'+canInfo.ew_project_id).first();
                                var $firstProjectTD = $firstProjectTR.find('td').first();
                                $firstProjectTD.attr('rowspan', parseInt($firstProjectTD.attr('rowspan'))+1);
                                var $lastProjectTR = $("#canListBody").find('.'+'project-'+canInfo.ew_project_id).last();
                                $lastProjectTR.after('<tr class="can-'+canId+' project-'+canInfo.ew_project_id+'"><td><input type="hidden" name="candidateBox[]" value="'+canId+'">'+canInfo.candidate_id_name+'</td><td class="text-center"><i data="'+canInfo.ew_project_id+'" class="remove hand fa fa-times"></i></td></tr>');
                            } else {
                                $("#canListBody").append('<tr class="can-'+canId+' project-'+canInfo.ew_project_id+'"><td rowspan="1">'+canInfo.project_name+'</td><td><input type="hidden" name="candidateBox[]" value="'+canId+'">'+canInfo.candidate_id_name+'</td><td class="text-center"><i data="'+canInfo.ew_project_id+'" class="remove hand fa fa-times"></i></td></tr>');
                            }
                        }
                    }
                });
                $("#candidate_id").val('0').trigger("change");
            }
        });
        //...........

        date_range = 0;
        $(".select2").select2({ placeholder: "Select" });

        //Candidate's Account Summary
        $("#ew_project_id").on('change', function(e) {
            var ew_project_id = $(this).val();
            if (ew_project_id) {
                //For Project wise candidates
                $.ajax({
                    mimeType: 'text/html; charset=utf-8',
                    url: '{{route("ew.projectCandidatesMultipleSelect")}}',
                    data: {ew_project_id:ew_project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        $("#project_candidate_id").html(data);
                        $("#candidate_id").select2({ placeholder: "Select" });
                        $('#amountStatusReportForm').formValidation('addField', $('#candidate_id'));

                        $('.fName').text('');
                        $('.reference').text('');
                        $(".dealer").text('');
                        $('.trade').text('');
                        $('.projectName').text('');
                    }
                });
            } else {
                $("#project_candidate_id").html('<select name="candidate_id" id="candidate_id" data-fv-icon="false" class="select2 form-control ml0"><option value="">All Candidates</option></select>');
                $("#candidate_id").select2({ placeholder: "Select" });
            }
        });

        //Candidate's Details
        $("#amountStatusReportForm").on('change', '#candidate_id', function(e) {
            var projectId = $("#ew_project_id").val();
            var candidateId = $("#candidate_id").val();
            if(candidateId) {
                var candidateId = candidateId[candidateId.length-1];
                $.ajax({
                    mimeType: 'text/html; charset=utf-8',
                    url: '{{route("ew.candidate-sort-details")}}',
                    data: {projectId:projectId, candidateId:candidateId},
                    type: 'GET',
                    dataType: "json",
                    success: function(data) {
                        $(".dealer").text('');
                        $('.fName').text(data.candidate_details.father_name);
                        $('.reference').text(data.candidate_details.reference_name);
                        $.each(data.dealer, function( index, value ) {
                           $(".dealer").append('<span>' + value + '</span>');
                           var arr = data.dealer;
                           if(index == (arr.length - 1))
                           {}
                           else
                            {
                              $(".dealer").append('<span>' + "," + '</span>'); 
                            }
                        });
                        $('.trade').text(data.candidate_details.trade_name);
                        $('.projectName').text(data.candidate_details.project_name);
                    }
                });
            } else {
                $('.fName').text('');
                $('.reference').text('');
                $(".dealer").text('');
                $('.trade').text('');
                $('.projectName').text('');
            }
        });

        $("#amountStatusReportForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var candidateBox = $('#candidateBoxForm').serializeArray();

            var candidate = $('#candidate_id').val();
            var candidate = (candidate) ? candidate : '';
            var projectId = $('#ew_project_id').val();
            var width = $(document).width();
            var height = $(document).height();
            var url = "{{route('ew.amountStatusReportData')}}"+'?candidate='+candidate+'&projectId='+projectId;
            if(candidateBox.length>0) {
                url += "&candidateBox=";
                $.each(candidateBox, function(index, canData){
                    if(index>0) { url += ","+canData.value; }
                    else { url += canData.value; }
                });
            }
            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });
    });

    function amountStatusRefresh() {
        $("#canListBody").html('<tr><td colspan="3" class="emptyMessage">Empty</td></tr>');
    }
</script>
