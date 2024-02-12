<?php $panelTitle = "Aviation Bill Payment"; ?>
@include("panelStart")
<form id="paymentReceiveForm" type="create" class="form-load form-horizontal" data-fv-excluded="" callback="refresh_amount_received">
    {{csrf_field()}}
    <div class="row mt15">
        <div class="col-md-6 col-sm-12 sortable-layout col-no-pr"><!--Left Side Box-->
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class=simple-chart>
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Project</label>
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
                            <label class="col-lg-4 col-md-4 control-label required">Aviation</label>
                            <div class="col-lg-8 col-md-8">
                                <select required name="aviation_id" id="aviation_id" data-fv-icon="false" class="select2 form-control ml0 autoRemarks">
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <!-- For Candidate sort details -->
                        <div class="candidate_sort_details"></div>
                        <!-- End Candidate sort details div -->

                         <!--Start Transaction By-->
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Transaction By</label>
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
                                <input type="text" class="form-control dtpicker" name="cheque_date" id="cheque_date" placeholder="dd/mm/yyyy" required />
                            </div>
                        </div>
                        <!--End Transaction By-->

                        
                        <div class="form-group">
                            <label class="col-lg-4 col-md-4 control-label required">Amount</label>
                            <div class="col-lg-8 col-md-8">
                                <div class="input-group">
                                    <span class="input-group-addon">৳</span>
                                    <input id="amount" name="amount" placeholder="e.g. 50000.00" class="form-control number" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s\.]+$" data-fv-regexp-message="Amount can consist of number only">
                                </div>
                                <p class="text-center"><strong class="words  text-primary"></strong></p>
                            </div>
                        </div>
                      <div class="form-group">
                          <label class="col-lg-4 col-md-4 control-label required">Remarks</label>
                          <div class="col-lg-8 col-md-8">
                              <textarea required autofocus id="remarks" name="remarks" placeholder="Remarks" class="form-control"></textarea>
                          </div>
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
                                  <div class="col-md-4 col-sm-4 col-xs-5">Payable</div>
                                  <div class="col-md-8 col-sm-8 col-xs-7">: <span class="pro-receivable" style="color:#333"></span>
                                    <!-- <input type="hidden" name="Receivable" value=""> -->
                                  </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Paid</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="pro-received" style="color:#32953D"></span></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-4 col-xs-5">Balance</div>
                                    <div class="col-md-8 col-sm-8 col-xs-7">: <span class="pro-balance" style="color:#6B6824"></span></div>
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
            <button type="submit" class="btn btn-default">Save Amount Received</button>
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
        //Transaction By
        // $("#cheque_date").datepicker();
        $("#cheque_date").datepicker({format: 'dd/mm/yyyy'});
        $('#paymentReceiveForm').formValidation('removeField', $('#cheque_no'));
        $('#paymentReceiveForm').formValidation('removeField', $('#cheque_date'));
        var bankList = [<?php echo implode(',', $bankList); ?>];
        

        //CONDIDATE SEARCH
        $("#ew_project_id").on('change', function(e) {
            var ew_project_id = $(this).val();
            if (ew_project_id) {
                //For Project wise Aviation
                $.ajax({
                    mimeType: 'text/html; charset=utf-8',
                    url: '{{route("ew.projectAviations")}}',
                    data: {ew_project_id:ew_project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        $("#aviation_id").html(data);
                        $("#aviation_id").select2({ placeholder: "Select" });
                    }
                });
            }
        });

        //Bank list shorted
        $("#paymentReceiveForm").on('change', '#transaction_by', function(e) {
            var transaction_by=parseInt($(this).val());
            if(bankList.indexOf(transaction_by)>=0) {
                if($("#cheque_no_view").is(":hidden")) {
                    $('#paymentReceiveForm').formValidation('addField', $('#cheque_no'));
                    $('#paymentReceiveForm').formValidation('addField', $('#cheque_date'));
                    $("#cheque_no_view").show();
                    $("#cheque_date_view").show();
                }
            } else {
                if(!($("#cheque_no_view").is(":hidden"))) {
                    $('#paymentReceiveForm').formValidation('removeField', $('#cheque_no'));
                    $('#paymentReceiveForm').formValidation('removeField', $('#cheque_date'));
                    $("#cheque_no_view").hide();
                    $("#cheque_date_view").hide();
                }
            }
        });

        // Candidate's Account Summary
        $("#paymentReceiveForm").on('change', '#aviation_id', function(e) {
            var projectId = $("#ew_project_id").val();
            var aviationId = $("#aviation_id").val();
            // alert(aviationId);
            $.ajax({
                mimeType: 'text/html; charset=utf-8',
                url: '{{route("ew.aviation-sort-details")}}',
                data: {projectId:projectId, aviationId:aviationId},
                type: 'GET',
                dataType: "json",
                success: function(data) {
                    $('.pro-receivable').text(data.payment_amount);
                    $('.pro-received').text(data.received);
                    $('.pro-balance').text(data.balance);
                }
            });
            // candidateAccountHeadSummary();

        });


        // For Auto Remarks Generate
        $("#paymentReceiveForm").on('change', '.autoRemarks', function(){
            remarksGen();
        });

        //For Auto Remarks Generate
        $("#amount").keyup(function(){
            remarksGen();
        });
    });

    // Auto Remarks Generate Function
    function remarksGen(){
        var projectName = $('#ew_project_id option:selected').text();
        var aviationName = $('#aviation_id option:selected').text();
        // var account_head = $('#account_head option:selected').text();
        var amount = $('#amount').val();
        if (projectName && aviationName && amount) {
            var remarksText = projectName+'-'+aviationName+'- '+'Payment Amount '+amount+' Tk.';
            $("#remarks").val(remarksText);
        }
    }

    function refresh_amount_received() {
        $('.pro-receivable').text('');
        $('.pro-received').text('');
        $('.pro-balance').text('');
    }
</script>
