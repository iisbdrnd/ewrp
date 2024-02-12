<?php $panelTitle = "Candidate Ledger Book"; ?>
@include("panelStart")
<form id="candidateLedgerBookForm" method="post" panelTitle="{{$panelTitle}}" type="view" class="form-horizontal" data-fv-excluded="">
    {{csrf_field()}}
    <div class="row mt15">
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="col-lg-6 col-md-12 sortable-layout col-no-pr"><!--Left Side Box-->
                <div class="panel panel-default chart">
                    <div class="panel-body">
                        <div class=simple-chart>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 control-label required">Project</label>
                                <div class="col-lg-8 col-md-8">
                                    <select required id="ew_project_id" name="project_id" data-fv-icon="false" class="select2 form-control ml0">
                                        <option value=""></option>
                                        @foreach($ewProjects as $ewProjects)
                                        <option value="{{$ewProjects->id}}">{{$ewProjects->project_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 control-label required">Candidate</label>
                                <div class="col-lg-8 col-md-8">
                                  <div id="project_candidate_id">
                                    <select required name="candidate_id" id="candidate_id" data-fv-icon="false" class="select2 form-control ml0">
                                        <option value=""></option>
                                    </select>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--CANDIDATE DETAILS RISHT SIDE BOX-->
            <div class="col-lg-6 col-md-12 sortable-layout candidateInfo">
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
    <div class="col-lg-offset-2 col-lg-10 btn-pb15">
        <button id="preview_button" type="submit" class="btn btn-default ml8 btn-ml0">Preview</button>
    </div>
</form>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        date_range = 0;
        $(".select2").select2({
            placeholder: "Select"
        });

        //Candidate's Account Summary
        $("#ew_project_id").on('change', function(e) {
            var ew_project_id = $(this).val();
            if (ew_project_id) {
                //For Project wise candidates
                $.ajax({
                    mimeType: 'text/html; charset=utf-8',
                    url: '{{route("ew.projectCandidates")}}',
                    data: {ew_project_id:ew_project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        $('#candidateLedgerBookForm').formValidation('removeField', $('#candidate_id'));
                        $("#project_candidate_id").html(data);
                        $("#candidate_id").select2({ placeholder: "Select" });
                        $('#candidateLedgerBookForm').formValidation('addField', $('#candidate_id'));

                        $('.fName').text('');
                        $('.reference').text('');
                        $(".dealer").text('');
                        $('.trade').text('');
                        $('.projectName').text('');
                    }
                });
            }
        });

        //Candidate's Details
        $("#candidateLedgerBookForm").on('change', '#candidate_id', function(e) {
          var projectId = $("#ew_project_id").val();
          var candidateId = $("#candidate_id").val();
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
          candidateAccountHeadSummary();
        });


        $("#candidateLedgerBookForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var candidate = $('#candidate_id').val();
            var width = $(document).width();
            var height = $(document).height();
            var url = "{{route('ew.candidateLedgerBookData')}}"+'?candidate='+candidate;
            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });
    });
</script>