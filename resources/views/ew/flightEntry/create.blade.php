<?php $panelTitle = "Flight Entry Create"; ?>
@include("panelStart")
<form id="flightEntryForm" type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" data-fv-excluded="" callback="refresh_flightEntry">
    {{csrf_field()}}
    <div class="row mt15">
        <!-- CANDIDATE'S FLIGHT ENTRY FIELDS (LEFT SIDE ON THE BOX) -->
        <div class="col-md-6 col-sm-12 sortable-layout col-no-pr">
            <div class="panel panel-default chart">
            <div class="panel-body">
                <div class=simple-chart>
                    <div class="form-group">
                        <label class="col-lg-4 col-md-4 control-label required">Select Project</label>
                        <div class="col-lg-8 col-md-8">
                            <select required id="ew_project_id" name="ew_project_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
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
                            <select required name="candidate_id" id="candidate_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                <option value=""></option>
                            </select>
                          </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 col-md-4 control-label required">Flight No</label>
                        <div class="col-lg-8 col-md-8">
                            <input autofocus required name="flight_no" id="flight_no" placeholder="FD180001" class="form-control autoRemarks">
                        </div>
                    </div>
                    <!-- For Candidate sort details -->
                    <div class="candidate_sort_details"></div>
                    <!-- End Candidate sort details div -->
                  <div class="form-group">
                      <label class="col-lg-4 col-md-4 control-label required">Flight Date</label>
                        <div class="col-lg-8 col-md-8">
                            <input required type="text" placeholder="e.g.02/02/2018" name="flight_date" id="flight_date" class="form-control autoRemarks" data-fv-trigger="change">
                        </div>
                  </div>
                  <div class="form-group">
                      <label class="col-lg-4 col-md-4 control-label required">Remarks</label>
                        <div class="col-lg-8 col-md-8">
                            <textarea required name="remarks" id="remarks" class="form-control"></textarea>
                        </div>
                  </div>
                </div>
            </div>
            </div>
        </div>
        <!-- CANDIDATE SORT DETAILS (RIGHT SIDE ON THE BOX) -->
        <div class="col-md-6 col-sm-12 sortable-layout candidateInfo">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart" style="line-height: 25px;">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Father's Name</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="fName"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Passport No</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="passNo"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Reference</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="reference"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Trade</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="trade"></span></div>
                                </div>
                            </div>
                            <div id="flightDone" class="col-md-4 col-sm-4 col-xs-12 text-right" style="color:#f00; display:none;"><h3 class="mt5 mb5">Flight Done</h3>[Deployed Date: <span id="flightDate">22/07/2017</span>]</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-sm-12 sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body" style="border: 2px dashed#333; font-weight:bold;color: #333;">
                    <div class="simple-chart" style="line-height: 25px;">
                        <div class="row" style="text-align: center;"><span style="font-size: 14px; border-bottom: 1px solid #333;">Account Summary</span></div>
                            <div class="row">
                                <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-5">Receivable</div>
                                  <div class="col-md-8 col-sm-8 col-xs-7">: <span class="can-receivable" style="color:#333"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Received</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="can-received" style="color:#32953D"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Balance</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="can-balance" style="color:#6B6824"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FOR CREATE BUTTON -->
    <div class="col-md-12 btn-pb15">
        <div class="form-group mb0">
            <button type="submit" class="btn btn-default">Create Flight</button>
        </div>
    </div>
</form>
@include("panelEnd")

<script type="text/javascript">
    $(document).ready(function() {
        //FOR SELECT BOX
        $(".select2").select2({
            placeholder: "Select"
        });
        var $selector = $("#flightEntryForm");
        //FOR FLIGHT DATE (datepicker)
        $selector.find('#flight_date').datepicker({
            format: 'dd/mm/yyyy'
        });

        //CONDIDATE SEARCH
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
                        $('#flightEntryForm').formValidation('removeField', $('#candidate_id'));
                        $("#project_candidate_id").html(data);
                        $("#candidate_id").select2({ placeholder: "Select" });
                        $('#flightEntryForm').formValidation('addField', $('#candidate_id'));

                        $("#remarks").val('');
                        $('.fName').text('');
                        $('.reference').text('');
                        $('.trade').text('');
                        $('.passNo').text('');
                        $('#flightDate').text('');
                        $('#flightDone').hide();

                        $('.can-receivable').text('');
                        $('.can-received').text('');
                        $('.can-balance').text('');
                    }
                });
            }
        });

        //CANDIDATE GENERAL INFORMATION SHOW
        $("#flightEntryForm").on('change', '#candidate_id', function(e) {
            var projectId = $("#ew_project_id").val();
            var candidateId = $("#candidate_id").val();
            $.ajax({
                mimeType: 'text/html; charset=utf-8',
                url: '{{route("ew.candidate-sort-details")}}',
                data: {projectId:projectId, candidateId:candidateId},
                type: 'GET',
                dataType: "json",
                success: function(data) {
                    $('.fName').text(data.candidate_details.father_name);
                    $('.reference').text(data.candidate_details.reference_name);
                    $('.trade').text(data.candidate_details.trade_name);
                    $('.passNo').text(data.candidate_details.passport_number);
                    if(data.candidate_details.flight_status==1) {
                        $('#flightDate').text(data.candidate_details.candidate_flight_date);
                        $('#flightDone').show();
                    } else {
                        $('#flightDate').text('');
                        $('#flightDone').hide();
                    }

                    $('.can-receivable').text(data.total_receivable_with_less);
                    $('.can-received').text(data.total_received);
                    $('.can-balance').text(data.balance);
                }
            });
        });

        //FOR AUTO REMARKS GENERATE BY ON CHAGE
        $("#flightEntryForm").on('change', '.autoRemarks', function(){
            remarksGen();
        });

        //FOR AUTO REMARKS GENERATE BY KEY UP
        $("#flightEntryForm").keyup('.autoRemarks', function(){
            remarksGen();
        });

    });

    //AUTO REMARKS GENERATE FUNCTION
    function remarksGen(){
        var ew_project_id = $('#ew_project_id option:selected').text();
        var candidate_id = $('#candidate_id option:selected').text();
        var flightNo = $('#flight_no').val();
        var flightDate = $("#flight_date" ).val();
        var remarksText = ew_project_id+'-'+candidate_id+'- Flight No: '+flightNo+' at '+flightDate;

        if (ew_project_id && candidate_id && flightNo && flightDate) {
            $("#remarks").val(remarksText);
        }
    }

    function refresh_flightEntry() {
        $('.fName').text('');
        $('.reference').text('');
        $('.trade').text('');
        $('.passNo').text('');
        $('#flightDate').text('');
        $('#flightDone').hide();
        $('.can-receivable').text('');
        $('.can-received').text('');
        $('.can-balance').text('');
    }
</script>
