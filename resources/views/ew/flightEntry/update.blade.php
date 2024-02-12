<!-- FOR PANEL TITLE -->
<?php $panelTitle = "Flight Entry Update"; ?>
<form type="update" id="flightEntryForm" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" data-fv-excluded="">
    {{csrf_field()}}
    <div class="row mt15">
        <div class="col-md-6 col-sm-12 sortable-layout col-no-pr"><!--Left Side Box-->
            <div class="panel panel-default chart">
            <div class="panel-body">
                <div class=simple-chart>
                    <div class="form-group">
                        <label class="col-lg-4 col-md-4 control-label required">Select Project</label>
                        <div class="col-lg-8 col-md-8">
                            <select disabled required id="ew_project_id" name="ew_project_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                <option value=""></option>
                                @foreach($ewProjects as $ewProjects)
                                <option value="{{$ewProjects->id}}" @if($ewProjects->id==$ewFlights->ew_project_id){{'selected'}}@endif>{{$ewProjects->project_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 col-md-4 control-label required">Candidate</label>
                        <div class="col-lg-8 col-md-8">
                          <div id="project_candidate_id">
                            <select disabled required id="candidate_id" name="candidate_id" data-fv-icon="false" class="select2 form-control autoRemarks">
                                <option value=""></option>
                                @foreach($ewCandidates as $ewCandidates)
                                <option value="{{$ewCandidates->id}}" @if($ewCandidates->id==$ewFlights->candidate_id) {{'selected'}} @endif>{{$ewCandidates->candidate_name}}</option>
                                @endforeach
                            </select>
                          </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 col-md-4 control-label required">Flight No</label>
                        <div class="col-lg-8 col-md-8">
                            <input autofocus required id="flight_no" name="flight_no" placeholder="FD20180206" class="form-control autoRemarks" value="{{$ewFlights->flight_no}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 col-md-4 control-label required">Flight Date</label>
                        <div class="col-lg-8 col-md-8">
                            <?php
                                $flightDate = DateTime::createFromFormat('Y-m-d', $ewFlights->flight_date);
                                $flight_date = $flightDate->format('d/m/Y');
                            ?>
                            <input required type="text" name="flight_date" class="form-control autoRemarks" id="flight_date" data-fv-trigger="change" value="{{$flight_date}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 col-md-4 control-label required">Remarks</label>
                        <div class="col-lg-8 col-md-8">
                            <input required id="remarks" name="remarks" class="form-control" value="{{$ewFlights->remarks}}">
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <!-- CANDIDATE SORT DETAILS -->
        <div class="col-md-6 col-sm-12 sortable-layout candidateInfo"><!--Right Side Details Box-->
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart" style="line-height: 25px;">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Father's Name</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="fName">{{$candidateInfo->father_name}}</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Passport No</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="reference">{{$candidateInfo->passport_number}}</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Reference</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="reference">{{$candidateReference->reference_name}}</span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Trade</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="trade">{{$candidateTrade->trade_name}}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- UPDATE BUTTON -->
    <div class="col-md-12 btn-pb15">
        <div class="form-group mb0">
            <button type="submit" class="btn btn-default">Update Flight</button>
        </div>
    </div>
</form>

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
        if (ew_project_id && candidate_id && flightNo && flightDate) {
            var remarksText = ew_project_id+'-'+candidate_id+'- Flight No: '+flightNo+' at '+flightDate;
            $("#remarks").val(remarksText);
        }
    }
</script>
