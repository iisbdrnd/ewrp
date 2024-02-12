<?php $panelTitle = "Amount Transfer"; ?>
@include("panelStart")
<form id="amountTransferForm" type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" data-fv-excluded="" callback="refresh_amount_transfer">
    {{csrf_field()}}
    <div class="row mt10">
        <div class="col-md-6 col-sm-12 col-no-pr">
            <div class="sortable-layout"><!--Left Side Box-->
                <h4 style="text-align: center; color: #353535;">Transfer From</h4>
                <div class="panel panel-default chart">
                    <div class="panel-body">
                        <div class=simple-chart>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 control-label required">Project</label>
                                <div class="col-lg-8 col-md-8">
                                    <select required id="from_ew_project_id" name="from_project_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                        <option value=""></option>
                                        @foreach($ewProjects as $ewProject)
                                        <option value="{{$ewProject->id}}">{{$ewProject->project_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 control-label required">Candidate</label>
                                <div class="col-lg-8 col-md-8">
                                  <div id="from_project_candidate_id">
                                    <select required name="from_candidate_id" id="from_candidate_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                        <option value=""></option>
                                    </select>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 control-label required">Account Head</label>
                                <div class="col-lg-8 col-md-8">
                                    <div id="from_project_collectable_accounts_heads">
                                        <select required name="from_account_head" id="from_account_head" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart" style="line-height: 20px;">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Father's Name</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_fName"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Reference</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_reference"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Dealer</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_dealer"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Trade</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_trade"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Project Name</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_projectName"></span></div>
                                </div>
                            </div>
                            <div id="from_flightDone" class="col-md-4 col-sm-4 col-xs-12 text-right" style="color:#f00; display:none;"><h3 class="mt5 mb5">Flight Done</h3>[Deployed Date: <span id="from_flightDate"></span>]</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!--<div class="col-lg-6 col-md-6 sortable-layout">
                    <div class="panel panel-default chart">
                        <div class="panel-body" style="border: 2px dashed#333; font-weight:bold;color: #333;">
                            <div class="simple-chart" style="line-height: 19.5px;">
                                <div class="row" style="text-align: center;"><span style="font-size: 14px; border-bottom: 1px solid #333;">Account Head Summary</span></div>
                                <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-5">Receivable</div>
                                  <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_acc-receivable" style="color:#333"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Received</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_acc-received" style="color:#32953D"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Less</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_acc-less" style="color:#b45a33"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Balance</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_acc-balance" style="color:#6B6824"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="col-lg-12 col-md-12 sortable-layout">
                    <div class="panel panel-default chart">
                        <div class="panel-body" style="border: 2px dashed#333; font-weight:bold;color: #333;">
                            <div class="simple-chart" style="line-height: 20px;">
                                <div class="row" style="text-align: center;"><span style="font-size: 14px; border-bottom: 1px solid #333;">Account Summary</span></div>
                                <div class="row">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <div class="row">
                                          <div class="col-md-4 col-sm-4 col-xs-5">Receivable</div>
                                          <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_can-receivable" style="color:#333"></span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-5">Received</div>
                                            <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_can-received" style="color:#32953D"></span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-5">Balance</div>
                                            <div class="col-md-8 col-sm-8 col-xs-7">: <span class="from_can-balance" style="color:#6B6824"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="sortable-layout"><!--Right Side Box-->
                <h4 style="text-align: center; color: #353535;">Transfer To</h4>
                <div class="panel panel-default chart">
                    <div class="panel-body">
                        <div class=simple-chart>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 control-label required">Project</label>
                                <div class="col-lg-8 col-md-8">
                                    <select required id="to_ew_project_id" name="to_project_id" data-fv-icon="false" class="select2 form-control ml0">
                                        <option value=""></option>
                                        @foreach($ewProjects as $ewProject)
                                        <option value="{{$ewProject->id}}">{{$ewProject->project_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 control-label required">Candidate</label>
                                <div class="col-lg-8 col-md-8">
                                  <div id="to_project_candidate_id">
                                    <select required name="to_candidate_id" id="to_candidate_id" data-fv-icon="false" class="select2 form-control ml0">
                                        <option value=""></option>
                                    </select>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-lg-4 col-md-4 control-label required">Account Head</label>
                                <div class="col-lg-8 col-md-8">
                                    <div id="to_project_collectable_accounts_heads">
                                        <select required name="to_account_head" id="to_account_head" data-fv-icon="false" class="select2 form-control ml0">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class="simple-chart" style="line-height: 20px;">
                        <div class="row">
                            <div class="col-md-8 col-sm-8 col-xs-12">
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Father's Name</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_fName"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Reference</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_reference"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Dealer</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_dealer"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Trade</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_trade"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Project Name</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_projectName"></span></div>
                                </div>
                            </div>
                            <div id="to_flightDone" class="col-md-4 col-sm-4 col-xs-12 text-right" style="color:#f00; display:none;"><h3 class="mt5 mb5">Flight Done</h3>[Deployed Date: <span id="to_flightDate"></span>]</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!--<div class="col-lg-6 col-md-6 sortable-layout">
                    <div class="panel panel-default chart">
                        <div class="panel-body" style="border: 2px dashed#333; font-weight:bold;color: #333;">
                            <div class="simple-chart" style="line-height: 19.5px;">
                                <div class="row" style="text-align: center;"><span style="font-size: 14px; border-bottom: 1px solid #333;">Account Head Summary</span></div>
                                <div class="row">
                                  <div class="col-md-4 col-sm-4 col-xs-5">Receivable</div>
                                  <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_acc-receivable" style="color:#333"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Received</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_acc-received" style="color:#32953D"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Less</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_acc-less" style="color:#b45a33"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Balance</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_acc-balance" style="color:#6B6824"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
                <div class="col-lg-12 col-md-12 sortable-layout">
                    <div class="panel panel-default chart">
                        <div class="panel-body" style="border: 2px dashed#333; font-weight:bold;color: #333;">
                            <div class="simple-chart" style="line-height: 20px;">
                                <div class="row" style="text-align: center;"><span style="font-size: 14px; border-bottom: 1px solid #333;">Account Summary</span></div>
                                <div class="row">
                                    <div class="col-md-8 col-sm-8 col-xs-12">
                                        <div class="row">
                                          <div class="col-md-4 col-sm-4 col-xs-5">Receivable</div>
                                          <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_can-receivable" style="color:#333"></span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-5">Received</div>
                                            <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_can-received" style="color:#32953D"></span></div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 col-sm-4 col-xs-5">Balance</div>
                                            <div class="col-md-8 col-sm-8 col-xs-7">: <span class="to_can-balance" style="color:#6B6824"></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="sortable-layout">
                <div class="panel panel-default chart">
                    <div class="panel-body">
                        <div class=simple-chart>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-no-pr">
                                    <div class="form-group">
                                        <label class="col-lg-4 col-md-4 control-label required">Amount</label>
                                        <div class="col-lg-8 col-md-8">
                                            <input required id="amount" name="amount"  placeholder="e.g. 50000" class="form-control number" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true"
                                               data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Amount can consist of number only">
                                        </div>
                                    </div>
                                    <p class="text-center"><strong class="words  text-primary"></strong></p>

                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="col-lg-4 col-md-4 control-label required">Remarks</label>
                                        <div class="col-lg-8 col-md-8">
                                            <textarea required style="height: 33px;" rows="1" autofocus name="remarks" id="remarks" placeholder="Remarks" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 btn-pb15">
        <div class="form-group mb0">
            <button type="submit" class="btn btn-default">Save Amount Transfer</button>
        </div>
    </div>
</form>
@include("panelEnd")
<script src="{{asset('public/js/number_to_words.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({ placeholder: "Select" });

        //CONDIDATE SEARCH
        $("#from_ew_project_id").on('change', function() { changeProjectId('from'); });
        $("#to_ew_project_id").on('change', function() { changeProjectId('to'); });

        //Candidate's Account Summary
        $("#amountTransferForm").on('change', '#from_candidate_id', function() { changeCandidateId('from'); });
        $("#amountTransferForm").on('change', '#to_candidate_id', function() { changeCandidateId('to'); });

        //$("#amountTransferForm").on('change', '#from_account_head', function(e) { candidateAccountHeadSummary('from'); });
        //$("#amountTransferForm").on('change', '#to_account_head', function(e) { candidateAccountHeadSummary('to'); });

        function changeProjectId(sidePrefix) {
            var ew_project_id = $("#"+sidePrefix+"_ew_project_id").val();
            if (ew_project_id) {
                //For Project wise candidates
                $.ajax({
                    mimeType: 'text/html; charset=utf-8',
                    url: '{{route("ew.projectCandidates")}}',
                    data: {ew_project_id:ew_project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        $('#amountTransferForm').formValidation('removeField', $('#'+sidePrefix+'_candidate_id'));
                        $("#"+sidePrefix+"_project_candidate_id").html(data);
                        $('#candidate_id').removeAttr('id').removeAttr('name').attr('id', sidePrefix+"_candidate_id").attr('name', sidePrefix+"_candidate_id");
                        $("#"+sidePrefix+"_candidate_id").select2({ placeholder: "Select" });
                        $('#amountTransferForm').formValidation('addField', $('#'+sidePrefix+'_candidate_id'));

                        //Reset
                        $('#amountTransferForm').formValidation('removeField', $('#'+sidePrefix+'_account_head'));
                        $("#"+sidePrefix+"_project_collectable_accounts_heads").html('<select required name="'+sidePrefix+'_account_head" id="'+sidePrefix+'_account_head" data-fv-icon="false" class="select2 form-control ml0 autoRemarks"><option value=""></option></select>');
                        $("#"+sidePrefix+"_account_head").select2({ placeholder: "Select" });
                        $('#amountTransferForm').formValidation('addField', $('#account_head'));

                        $('.'+sidePrefix+'_fName').text('');
                        $('.'+sidePrefix+'_reference').text('');
                        $('.'+sidePrefix+'_dealer').text('');
                        $('.'+sidePrefix+'_trade').text('');
                        $('.'+sidePrefix+'_projectName').text('');
                        $('#'+sidePrefix+'_flightDate').text('');
                        $('#'+sidePrefix+'_flightDone').hide();

                        $('.'+sidePrefix+'_can-receivable').text('');
                        $('.'+sidePrefix+'_can-received').text('');
                        $('.'+sidePrefix+'_can-balance').text('');
                    }
                });
            }
        }

        function changeCandidateId(sidePrefix) {
            var projectId = $("#"+sidePrefix+"_ew_project_id").val();
            var candidateId = $("#"+sidePrefix+"_candidate_id").val();
            $.ajax({
              mimeType: 'text/html; charset=utf-8',
              url: '{{route("ew.candidate-sort-details")}}',
              data: {projectId:projectId, candidateId:candidateId},
              type: 'GET',
              dataType: "json",
              success: function(data) {
                    $('.'+sidePrefix+'_dealer').text('');
                    $('.'+sidePrefix+'_fName').text(data.candidate_details.father_name);
                    $('.'+sidePrefix+'_reference').text(data.candidate_details.reference_name);
                    $.each(data.dealer, function( index, value ) {
                       $('.'+sidePrefix+'_dealer').append('<span>' + value + '</span>');
                       var arr = data.dealer;
                       if(index == (arr.length - 1))
                       {}
                       else
                       {
                          $('.'+sidePrefix+'_dealer').append('<span>' + "," + '</span>'); 
                       }
                    });
                    $('.'+sidePrefix+'_trade').text(data.candidate_details.trade_name);
                    $('.'+sidePrefix+'_projectName').text(data.candidate_details.project_name);
                    if(data.candidate_details.flight_status==1) {
                        $('#'+sidePrefix+'_flightDate').text(data.candidate_details.candidate_flight_date);
                        $('#'+sidePrefix+'_flightDone').show();
                    } else {
                        $('#'+sidePrefix+'_flightDate').text('');
                        $('#'+sidePrefix+'_flightDone').hide();
                    }

                    $('.'+sidePrefix+'_can-receivable').text(data.total_receivable);
                    $('.'+sidePrefix+'_can-received').text(data.total_received);
                    $('.'+sidePrefix+'_can-balance').text(data.balance);
                }
            });
            //candidateAccountHeadSummary(sidePrefix);

            //For Candidate wise account heads
            $.ajax({
                mimeType: 'text/html; charset=utf-8',
                url: '{{route("ew.candidateCollectableAccountHeads")}}',
                data: {candidateId:candidateId},
                type: 'GET',
                dataType: "html",
                success: function(data) {
                    $('#amountTransferForm').formValidation('removeField', $('#'+sidePrefix+'_account_head'));
                    $("#"+sidePrefix+"_project_collectable_accounts_heads").html(data);
                    $('#account_head').removeAttr('id').removeAttr('name').attr('id', sidePrefix+"_account_head").attr('name', sidePrefix+"_account_head");
                    $("#"+sidePrefix+"_account_head").select2({ placeholder: "Select" });
                    $('#amountTransferForm').formValidation('addField', $('#'+sidePrefix+'_account_head'));

                    //Project wise candidate information null
                    /*$('.'+sidePrefix+'_acc-receivable').text('');
                    $('.'+sidePrefix+'_acc-received').text('');
                    $('.'+sidePrefix+'_acc-less').text('');
                    $('.'+sidePrefix+'_acc-balance').text('');*/
                }
            });
        }

        /*function candidateAccountHeadSummary(sidePrefix) {
            var candidate_id = $('#'+sidePrefix+'_candidate_id').val();
            var account_head = $('#'+sidePrefix+'_account_head').val();

            if(candidate_id!='' && account_head!='') {
                $.ajax({
                  mimeType: 'text/html; charset=utf-8',
                  url: '{{route("ew.candidate-account-head-summary")}}',
                  data: {candidateId:candidate_id, account_head:account_head},
                  type: 'GET',
                  dataType: "json",
                  success: function(data) {
                        $('.'+sidePrefix+'_acc-receivable').text(data.total_receivable);
                        $('.'+sidePrefix+'_acc-received').text(data.total_received);
                        $('.'+sidePrefix+'_acc-less').text(data.total_less);
                        $('.'+sidePrefix+'_acc-balance').text(data.balance);
                    }
                });
            }
        }*/

        //For Auto Remarks Generate
        $("#amountTransferForm").on('change', '.autoRemarks', function(){
            remarksGen();
        });

        //For Auto Remarks Generate
        $("#amount").keyup(function(){
            remarksGen();
        });
    });

    //Auto Remarks Generate Function
    function remarksGen(){
        var from_ew_project_id = $('#from_ew_project_id option:selected').text();
        var from_candidate_id = $('#from_candidate_id option:selected').text();
        var from_account_head = $('#from_account_head option:selected').text();

        var to_ew_project_id = $('#to_ew_project_id option:selected').text();
        var to_candidate_id = $('#to_candidate_id option:selected').text();
        var to_account_head = $('#to_account_head option:selected').text();

        var amount = $('#amount').val();
        if (from_ew_project_id && from_candidate_id && from_account_head && to_ew_project_id && to_candidate_id && to_account_head && amount) {
            var remarksText = 'From '+from_account_head+'-'+from_ew_project_id+'-'+from_candidate_id+' Transfer Amount '+amount+' Tk.'+' to '+to_account_head+'-'+to_ew_project_id+'-'+to_candidate_id;
            $("#remarks").val(remarksText);
        }
    }

    function refresh_amount_transfer() {
        $('.from_fName').text('');
        $('.from_reference').text('');
        $('.from_trade').text('');
        $('.from_projectName').text('');
        $('#from_flightDate').text('');
        $('#from_flightDone').hide();
        $('.from_can-receivable').text('');
        $('.from_can-received').text('');
        $('.from_can-balance').text('');
        /*$('.from_acc-receivable').text('');
        $('.from_acc-received').text('');
        $('.from_acc-less').text('');
        $('.from_acc-balance').text('');*/

        $('.to_fName').text('');
        $('.to_reference').text('');
        $('.to_trade').text('');
        $('.to_projectName').text('');
        $('#to_flightDate').text('');
        $('#to_flightDone').hide();
        $('.to_can-receivable').text('');
        $('.to_can-received').text('');
        $('.to_can-balance').text('');
        /*$('.to_acc-receivable').text('');
        $('.to_acc-received').text('');
        $('.to_acc-less').text('');
        $('.to_acc-balance').text('');*/
    }
</script>
