<?php $panelTitle = "Candidate Create"; ?>
<div class="row mt15">
    <form id="candidateForm" type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" data-fv-excluded="" callback="candidateFormRefresh">
        {{csrf_field()}}
        <!-- PROJECT INFORMATION -->
        <div class="col-lg-6 col-md-6 col-xs-12 col-no-pr">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart">
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Select Project</label>
                            <div class="col-lg-8 col-md-8">
                                <select required id="ew_project_id" name="ew_project_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                    <option value=""></option>
                                    @foreach($ewProjects as $ewProjects)
                                    <option value="{{$ewProjects->id}}" @if($ewCandidates->ew_project_id==$ewProjects->id){{'selected'}}@endif data="5">{{$ewProjects->project_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Select Trade</label>
                            <div class="col-lg-8 col-md-8">
                                <select required name="trade_id" id="trade_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                    <option value=""></option>
                                    @foreach($projectTrades as $projectTrade)
                                    <option value="{{$projectTrade->id}}" @if($projectTrade->id==$ewCandidates->trade_id){{'selected'}}@endif>{{$projectTrade->trade_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Reference Name</label>
                            <div class="col-lg-8 col-md-8">
                                <select required name="reference_id" id="reference_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                    <option value=""></option>
                                    @foreach($ewReferences as $ewReferences)
                                    <option value="{{$ewReferences->id}}" @if($ewReferences->id==$ewCandidates->reference_id){{'selected'}}@endif>{{$ewReferences->reference_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Collectable Amount</label>
                            <div class="col-lg-8 col-md-8">
                                <input id="collectable_amount" required autofocus name="collectable_amount"  placeholder="0" class="form-control collectable_amount" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Collectible amount can consist of more than 0 and number only." value="{{$total_receivable_amount}}">
                                <!-- <p id="collectable_amount_note"></p> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CANDIDATE INFORMATION -->
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart">
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Candidate Name</label>
                            <div class="col-lg-8 col-md-8">
                                <input autofocus required name="candidate_name" placeholder="Candidate Name" class="form-control" value="{{$ewCandidates->candidate_name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Father Name</label>
                            <div class="col-lg-8 col-md-8">
                                <input autofocus required name="father_name" placeholder="Father Name" class="form-control" value="{{$ewCandidates->father_name}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Passport Number</label>
                            <div class="col-lg-8 col-md-8">
                                <input id="passport_number" required autofocus name="passport_number"  placeholder="e.g.12A-010" class="form-control" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s\a-z\A-Z]+$"  data-fv-regexp-message="Passport number can consist of number and alphabet." value="{{$ewCandidates->passport_number}}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">National ID</label>
                            <div class="col-lg-8 col-md-8">
                                <input id="national_id" required autofocus name="national_id"  placeholder="e.g.1990-010" class="form-control" value="{{$ewCandidates->national_id}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- PROJECT TRADES WITH REMARKS -->
        <!-- PROJECT COLLECTABLE INFO -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart">
                        <div class="form-group" style="font-size: 16px;text-align: center;">
                            <span id="collectableHeading">Candidate Collectable Amount</span>
                        </div>
                        
                        @if(!empty($projectCollectableSelections))
                        <div id="job_expenses">
                            @foreach($projectCollectableSelections as $projectCollectableSelection)
                                <div class="form-group">
                                    @foreach($projectCollectableSelection as $projectCollectableSelec)
                                    <label class="col-lg-2 col-md-2 col-xs-3 control-label required">{{$projectCollectableSelec->account_head}}</label>
                                    <div class="col-lg-4 col-md-4 col-xs-6">
                                        <input type="hidden" name="collectable_head_id[]" value='{{$projectCollectableSelec->id}}'>
                                        <input required type="text" name="collectable_head_amount[]"  placeholder="0" class="form-control collectable_selectors" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Amount can consist of number only"  value='{{$projectCollectableSelec->receivable_amount}}'>
                                    </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                        @else
                        <div id="empty_collectable_selects" class="form-group" style="font-size: 16px;text-align: center;">
                            <span>Empty</span>
                        </div>
                        <div id="job_expenses" style="display: none;"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- REMARKS -->
        <div class="col-lg-12 col-md-12 col-xs-12">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart">
                        <div class="form-group">
                            <label class="col-lg-2 col-md-2 control-label required">Remarks</label>
                            <div class="col-lg-10 col-md-10">
                                <input required name="remarks" id="remarks" placeholder="Remarks" class="form-control" data-fv-trigger="keyup change" value="{{$ewCandidates->remarks}}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("select.select2").select2({ placeholder: "Select" });

        // $('#ew_project_id').change(function(){
            var project_id = $('#ew_project_id').val();
            if(project_id){
                // $.ajax({
                //     url: "{{route('ew.getTradesByProject')}}",
                //     type: "GET",
                //     data: {project_id:project_id},
                //     success: function(data){
                //         dataFilter(data);
                //         $('#trade_id').html(data);
                //         $("#trade_id").select2({ placeholder: "Select" });
                //     }
                // });

                $.ajax({
                    url: "{{route('ew.getProjectCollectableSelection')}}",
                    type: "GET",
                    data: {project_id:project_id},
                    success: function(data){
                        dataFilter(data);
                        var collectableData = '{{ $projectCollectableSelections }}';
                        // console.log(collectableData);
                        
                        if(collectableData == '[]'){
                            if (data) {
                            if($(".collectable_selectors").length>0) { $('#candidateForm').formValidation('removeField', $(".collectable_selectors")); }
                            $('#job_expenses').show();
                            $("#job_expenses").html(data);
                            $('#empty_collectable_selects').hide();
                            if($(".collectable_selectors").length>0) { $('#candidateForm').formValidation('addField', $(".collectable_selectors")); }
                        }else{
                            if($(".collectable_selectors").length>0) { $('#candidateForm').formValidation('removeField', $(".collectable_selectors")); }
                            $('#empty_collectable_selects').show();
                            $('#job_expenses').html('');
                            $('#job_expenses').hide();
                        }
                        
                        $("#collectableHeading").html("<p>Candidate Collectable Amount</p>");
                        }
                        
                    }
                });
            }
        // });

        $('#job_expenses').keyup('.collectable_selectors', function(){
            var total = 0;
            var collectable_amount = $('.collectable_amount').val();
            $('.collectable_selectors').each(function (index, element) {
                var eachHeadValue = $(element).val();
                total = total + parseFloat((eachHeadValue=='')? 0 : eachHeadValue);
            });
            if (collectable_amount > total) {
                $("#collectableHeading").html("<p style='color:red;'>Candidate Collectable Amount "+total+"</p>");
            } else if(collectable_amount < total){
                $("#collectableHeading").html("<p style='color:red;'>Candidate Collectable Amount "+total+"</p>");
            } else{
                $("#collectableHeading").html("<p style='color:green;'>Candidate Collectable Amount "+total+"</p>");
            }


        });

        $('.collectable_amount').keyup(function(){
            var total = 0;
            var collectable_amount = $('.collectable_amount').val();
            $('.collectable_selectors').each(function (index, element) {
                var eachHeadValue = $(element).val();
                total = total + parseFloat((eachHeadValue=='')? 0 : eachHeadValue);
            });

            if (collectable_amount > total) {
                // $(".createCandidateBtn").attr('disabled', 'disabled');
                $("#collectableHeading").html("<p style='color:red;'>Candidate Collectable Amount "+total+"</p>");
                $("#collectable_amount_note").show();
                $("#collectable_amount_note").html("<i style='color:red'>Your collectible amount has been more than collectible heads</i>");
            } else if(collectable_amount < total){
                $(".createCandidateBtn").attr('disabled', 'disabled');
                $("#collectableHeading").html("<p style='color:red;'>Candidate Collectable Amount "+total+"</p>");
                $("#collectable_amount_note").show();
                $("#collectable_amount_note").html("<i style='color:red'>Your collectible amount has been less than collectible heads</i>");
            } else{
                $("#collectableHeading").html("<p style='color:green;'>Candidate Collectable Amount "+total+"</p>");
                $("#collectable_amount_note").hide();
                $(".createCandidateBtn").removeAttr('disabled', 'disabled');
            }
        });
    });
        //Auto Remarks Generate
        // $(".autoRemarks").on('change', function(){
            var project = $('#ew_project_id option:selected').text();
            var trade_id = $('#trade_id option:selected').text();
            var reference_id = $('#reference_id option:selected').text();
            var remarksText = project+'-'+trade_id+'-'+reference_id;
            if (project && trade_id && reference_id) {
                $("#remarks").val(remarksText);
                $("#remarks").trigger('change');
                // $(".autoRemarks").trigger('change');
            }
        // });


    function candidateFormRefresh() {
        if($(".collectable_selectors").length>0) { $('#candidateForm').formValidation('removeField', $(".collectable_selectors")); }
        $('#empty_collectable_selects').show();
        $('#job_expenses').html('');
        $('#job_expenses').hide();
        $("#collectableHeading").html("<p>Candidate Collectable Amount</p>");
    }
</script>
