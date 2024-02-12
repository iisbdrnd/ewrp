<?php $panelTitle = "Amount Refund"; ?>
@include("panelStart")
<form id="amountRefundForm" type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal" data-fv-excluded="" callback="refresh_amount_refund">
    {{csrf_field()}}
    <div class="row mt15">
        <div class="col-md-6 col-sm-12 sortable-layout col-no-pr"><!--Left Side Box-->
            <div class="panel panel-default chart">
            <div class="panel-body">
                <div class=simple-chart>
                    <div class="form-group">
                        <label class="col-lg-4 col-md-4 control-label required">Project</label>
                        <div class="col-lg-8 col-md-8">
                            <select required id="ew_project_id" name="project_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
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
                        <label class="col-lg-4 col-md-4 control-label required">Account Head</label>
                        <div class="col-lg-8 col-md-8">
                          <div id="project_collectable_accounts_heads">
                            <select required name="account_head" id="account_head" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                <option value=""></option>
                            </select>
                          </div>
                        </div>
                    </div>
                    <!-- For Candidate sort details -->
                    <div class="candidate_sort_details"></div>
                    <!-- End Candidate sort details div -->
                  <div class="form-group">
                      <label class="col-lg-4 col-md-4 control-label required">Amount</label>
                      <div class="col-lg-8 col-md-8">
                          <input required id="amount" name="amount"  placeholder="e.g. 50000.00" class="form-control number" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true"
                               data-fv-regexp-regexp="^[0-9+\s\.]+$"  data-fv-regexp-message="Amount can consist of number only">
                      </div>
                       <p class="text-center"><strong class="words  text-primary"></strong></p>
                  </div>
                  <div class="form-group">
                      <label class="col-lg-4 col-md-4 control-label required">Refund By</label>
                      <div class="col-lg-8 col-md-8">
                          <select required id="transaction_by" name="transaction_by" data-fv-icon="" class="select2" style="width:100%;">
                              <option value=""></option>
                              @foreach($accountLevelOfFour as $levelThreeKey=>$accLevelOfFour)
                                @if (isset($accountLevelOfThree[$levelThreeKey]))
                                <optgroup label="{{$accountLevelOfThree[$levelThreeKey]->account_code.' - '.$accountLevelOfThree[$levelThreeKey]->account_head}}">
                                  @foreach($accLevelOfFour as $accLevelOfFour)
                                    <option value="{{$accLevelOfFour->account_code}}">{{$accLevelOfFour->account_code.' - '.$accLevelOfFour->account_head}}</option>
                                  @endforeach
                                </optgroup>
                                @endif
                              @endforeach
                          </select>
                      </div>
                  </div>
                  <div class="form-group" id="cheque_no_view" style="display:none">
                      <label class="col-lg-4 col-md-4 control-label required">Cheque No:</label>
                      <div class="col-lg-8 col-md-8">
                          <input type="text" class="form-control" name="cheque_no" id="cheque_no" placeholder="Cheque No" required />
                      </div>
                  </div>
                  <div class="form-group" id="cheque_date_view" style="display:none">
                      <label class="col-lg-4 col-md-4 control-label required">Cheque Date:</label>
                      <div class="col-lg-8 col-md-8">
                          <input type="text" class="form-control" name="cheque_date" id="cheque_date" placeholder="dd/mm/yyyy" required />
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="col-lg-4 col-md-4 control-label required">Remarks</label>
                      <div class="col-lg-8 col-md-8">
                          <textarea required autofocus name="remarks" id="remarks" placeholder="Remarks" class="form-control"></textarea>
                      </div>
                  </div>
                </div>
            </div>
            </div>
        </div>
        <!-- Details -->
        <div class="col-md-6 col-sm-12 sortable-layout candidateInfo"><!--Right Side Details Box-->
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
                                    <div class="col-md-4 col-sm-4 col-xs-5">Reference</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="reference"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Dealer</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="dealer"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Trade</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="trade"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Project Name</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="projectName"></span></div>
                                </div>
                            </div>
                            <div id="flightDone" class="col-md-4 col-sm-4 col-xs-12 text-right" style="color:#f00; display:none;"><h3 class="mt5 mb5">Flight Done</h3>[Deployed Date: <span id="flightDate">22/07/2017</span>]</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="col-lg-3 col-md-3 sortable-layout col-no-pr">
            <div class="panel panel-default chart">
                <div class="panel-body" style="border: 2px dashed#333; font-weight:bold;color: #333;">
                    <div class="simple-chart" style="line-height: 24.5px;">
                        <div class="row" style="text-align: center;"><span style="font-size: 14px; border-bottom: 1px solid #333;">Account Head Summary</span></div>
                        <div class="row">
                          <div class="col-md-4 col-sm-4 col-xs-5">Receivable</div>
                          <div class="col-md-8 col-sm-8 col-xs-7">: <span class="acc-receivable" style="color:#333"></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-5">Received</div>
                            <div class="col-md-8 col-sm-8 col-xs-7">: <span class="acc-received" style="color:#32953D"></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-5">Less</div>
                            <div class="col-md-8 col-sm-8 col-xs-7">: <span class="acc-less" style="color:#b45a33"></span></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-5">Balance</div>
                            <div class="col-md-8 col-sm-8 col-xs-7">: <span class="acc-balance" style="color:#6B6824"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>-->
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
    <div class="col-md-12 btn-pb15">
        <div class="form-group mb0">
            <button type="submit" class="btn btn-default">Save Amount Refund</button>
        </div>
    </div>
</form>
@include("panelEnd")
<script src="{{asset('public/js/number_to_words.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
        $("#cheque_date").datepicker();
        $('#amountRefundForm').formValidation('removeField', $('#cheque_no'));
        $('#amountRefundForm').formValidation('removeField', $('#cheque_date'));
        var bankList = [<?php echo implode(',', $bankList); ?>];

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
                        $('#amountRefundForm').formValidation('removeField', $('#candidate_id'));
                        $("#project_candidate_id").html(data);
                        $("#candidate_id").select2({ placeholder: "Select" });
                        $('#amountRefundForm').formValidation('addField', $('#candidate_id'));

                        //Reset
                        $('#amountRefundForm').formValidation('removeField', $('#account_head'));
                        $("#project_collectable_accounts_heads").html('<select required name="account_head" id="account_head" data-fv-icon="false" class="select2 form-control ml0 autoRemarks"><option value=""></option></select>');
                        $("#account_head").select2({ placeholder: "Select" });
                        $('#amountRefundForm').formValidation('addField', $('#account_head'));

                        $('.fName').text('');
                        $('.reference').text('');
                        $('.dealer').text('');
                        $('.trade').text('');
                        $('.projectName').text('');
                        $('#flightDate').text('');
                        $('#flightDone').hide();

                        $('.can-receivable').text('');
                        $('.can-received').text('');
                        $('.can-balance').text('');
                    }
                });
            }
        });

        //Candidate's Account Summary
        $("#amountRefundForm").on('change', '#candidate_id', function(e) {
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
                  if(data.candidate_details.flight_status==1) {
                      $('#flightDate').text(data.candidate_details.candidate_flight_date);
                      $('#flightDone').show();
                  } else {
                      $('#flightDate').text('');
                      $('#flightDone').hide();
                  }

                  $('.can-receivable').text(data.total_receivable);
                  $('.can-received').text(data.total_received);
                  $('.can-balance').text(data.balance);
              }
          });
          //candidateAccountHeadSummary();

          //For Candidate wise account heads
          $.ajax({
            mimeType: 'text/html; charset=utf-8',
            url: '{{route("ew.candidateCollectableAccountHeads")}}',
            data: {candidateId:candidateId},
            type: 'GET',
            dataType: "html",
            success: function(data) {
                  $('#amountRefundForm').formValidation('removeField', $('#account_head'));
                  $("#project_collectable_accounts_heads").html(data);
                  $("#account_head").select2({ placeholder: "Select" });
                  $('#amountRefundForm').formValidation('addField', $('#account_head'));

                  //Project wise candidate information null
                  /*$('.acc-receivable').text('');
                  $('.acc-received').text('');
                  $('.acc-less').text('');
                  $('.acc-balance').text('');*/
              }
          });
        });

        /*$("#amountReceiveForm").on('change', '#account_head', function(e) {
            candidateAccountHeadSummary();
        });*/

        $("#amountRefundForm").on('change', '#transaction_by', function(e) {
            var transaction_by=parseInt($(this).val());
            if(bankList.indexOf(transaction_by)>=0) {
                if($("#cheque_no_view").is(":hidden")) {
                    $('#amountRefundForm').formValidation('addField', $('#cheque_no'));
                    $('#amountRefundForm').formValidation('addField', $('#cheque_date'));
                    $("#cheque_no_view").show();
                    $("#cheque_date_view").show();
                }
            } else {
                if(!($("#cheque_no_view").is(":hidden"))) {
                    $('#amountRefundForm').formValidation('removeField', $('#cheque_no'));
                    $('#amountRefundForm').formValidation('removeField', $('#cheque_date'));
                    $("#cheque_no_view").hide();
                    $("#cheque_date_view").hide();
                }
            }
        });

        //For Auto Remarks Generate
        $("#amountRefundForm").on('change', '.autoRemarks', function(){
            remarksGen();
        });


        //For Auto Remarks Generate
        $("#amount").keyup(function(){
            remarksGen();
        });
    });


    //Auto Remarks Generate Function
    function remarksGen(){
        var project = $('#ew_project_id option:selected').text();
        var candidate_id = $('#candidate_id option:selected').text();
        var account_head = $('#account_head option:selected').text();
        var amount = $('#amount').val();
        if (project && candidate_id && account_head && amount) {
            var remarksText = account_head+'-'+project+'-'+candidate_id+' Refund amount '+amount+' Tk.';
            $("#remarks").val(remarksText);
        }
    }


    /*function candidateAccountHeadSummary() {
        var candidate_id = $('#candidate_id').val();
        var account_head = $('#account_head').val();

        if(candidate_id!='' && account_head!='') {
            $.ajax({
              mimeType: 'text/html; charset=utf-8',
              url: '{{route("ew.candidate-account-head-summary")}}',
              data: {candidateId:candidate_id, account_head:account_head},
              type: 'GET',
              dataType: "json",
              success: function(data) {
                    $('.acc-receivable').text(data.total_receivable);
                    $('.acc-received').text(data.total_received);
                    $('.acc-less').text(data.total_less);
                    $('.acc-balance').text(data.balance);
                }
            });
        }
    }*/

    function refresh_amount_refund() {
        if(!($("#cheque_no_view").is(":hidden"))) {
            $('#amountRefundForm').formValidation('removeField', $('#cheque_no'));
            $('#amountRefundForm').formValidation('removeField', $('#cheque_date'));
            $("#cheque_no_view").hide();
            $("#cheque_date_view").hide();
        }
        $('.fName').text('');
        $('.reference').text('');
        $('.trade').text('');
        $('.projectName').text('');
        $('#flightDate').text('');
        $('#flightDone').hide();
        $('.can-receivable').text('');
        $('.can-received').text('');
        $('.can-balance').text('');
        /*$('.acc-receivable').text('');
        $('.acc-received').text('');
        $('.acc-less').text('');
        $('.acc-balance').text('');*/
    }
</script>
