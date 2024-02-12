<?php $panelTitle = "Journal Voucher"; ?>
@include("panelStart")
<div class="row mt15">
    <form id="journalEntryForm" method="post" type="create" class="form-load form-horizontal" data-fv-excluded="" callback="refresh_accounts_area">
        {{csrf_field()}}
        <div class="col-lg-6 col-md-6 sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class=simple-chart>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Project:</label>
                            <div class="col-md-9">
                                <select id="ew_project_id" name="ew_project_id" data-fv-icon="false" class="select2 form-control ml0" required>
                                    <option value=""></option>
                                    <option value="0">Head Office</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->project_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-6 col-md-6 sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class=simple-chart>
                        <div class="form-group p0">
                            <label class="col-lg-12 col-md-12 col-xs-12 control-label" style="text-align: center; font-size:18px;">
                                Debit Accounts
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Account Code:</label>
                            <div class="col-md-9">
                                <select id="debit_account" name="debit_account" data-fv-icon="" class="select2" style="width:100%;">
                                    <option value=""></option>
                                    @foreach($accountLevelOfFour as $levelThreeKey=>$accLevelOfFour)
                                      @if (isset($accountLevelOfThree[$levelThreeKey]))
                                      <optgroup label="{{$accountLevelOfThree[$levelThreeKey]->account_code.' - '.$accountLevelOfThree[$levelThreeKey]->account_head}}">
                                        @foreach($accLevelOfFour as $accLevelOfFour)
                                          <option value="{{$accLevelOfFour->account_code.'~'.$accLevelOfFour->account_head}}">{{$accLevelOfFour->account_code.' - '.$accLevelOfFour->account_head}}</option>
                                        @endforeach
                                      </optgroup>
                                      @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Amount:</label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-addon">৳</span>
                                    <input id="debit_amount" name="debit_amount" placeholder="e.g. 50000.00" class="form-control number" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s\.]+$" data-fv-regexp-message="Amount can consist of number only">
                                </div>
                                 <p class="text-center"><strong class="words  text-primary"></strong></p>
                            </div>
                            <div class="col-lg-2 col-md-2 pl0 mt10-mbl">
                                <button type="button" class="btn btn-block btn-info add_account" data="debit">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="18%">Account Code</th>
                        <th width="42%">Account Head</th>
                        <th width="30%">Amount</th>
                        <th width="5%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="debit_selected_account">
                    <tr class="nothing_here">
                        <td colspan="5" class="text-center">Nothing here</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:bold;">Total</td>
                        <td colspan="2" style="font-weight:bold;">
                            &#2547; <span id="debit_total_amount_text">00.00</span>
                            <input type="hidden" name="debit_total_amount" value="0"/>
                        </td>
                    </tr>
                 </tfoot>
            </table>
        </div>
        <div class="col-lg-6 col-md-6 col-no-pl sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class=simple-chart>
                        <div class="form-group p0">
                            <label class="col-lg-12 col-md-12 col-xs-12 control-label" style="text-align: center; font-size:18px;">
                                Credit Accounts
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Account Code:</label>
                            <div class="col-md-9">
                                <select id="credit_account" name="credit_account" data-fv-icon="" class="select2" style="width:100%;">
                                    <option value=""></option>
                                    @foreach($accountLevelOfFour as $levelThreeKey=>$accLevelOfFour)
                                      @if (isset($accountLevelOfThree[$levelThreeKey]))
                                      <optgroup label="{{$accountLevelOfThree[$levelThreeKey]->account_code.' - '.$accountLevelOfThree[$levelThreeKey]->account_head}}">
                                        @foreach($accLevelOfFour as $accLevelOfFour)
                                          <option value="{{$accLevelOfFour->account_code.'~'.$accLevelOfFour->account_head}}">{{$accLevelOfFour->account_code.' - '.$accLevelOfFour->account_head}}</option>
                                        @endforeach
                                      </optgroup>
                                      @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Amount:</label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-addon">৳</span>
                                    <input id="credit_amount" name="credit_amount" placeholder="e.g. 50000.00" class="form-control" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s\.]+$" data-fv-regexp-message="Amount can consist of number only">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 pl0 mt10-mbl">
                                <button type="button" class="btn btn-block btn-info add_account" data="credit">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table cellspacing="0" class="responsive table table-striped table-bordered">
                <thead>
                    <tr>
                        <th width="5%">No.</th>
                        <th width="18%">Account Code</th>
                        <th width="42%">Account Head</th>
                        <th width="30%">Amount</th>
                        <th width="5%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="credit_selected_account">
                    <tr class="nothing_here">
                        <td colspan="5" class="text-center">Nothing here</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:bold;">Total</td>
                        <td colspan="2" style="font-weight:bold;">
                            &#2547; <span id="credit_total_amount_text">00.00</span>
                            <input type="hidden" name="credit_total_amount" value="0"/>
                        </td>
                    </tr>
                 </tfoot>
            </table>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 col-md-12 sortable-layout mt10">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class=simple-chart>
                        <div class="form-group">
                            <label class="col-md-1 control-label">Remarks:</label>
                            <div class="col-md-11">
                                <input autofocus id="remarks" name="remarks" placeholder="Remarks" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 col-md-12 btn-pb15">
            <button type="submit" class="btn btn-default">Save Journal Voucher</button>
        </div>
    </from>
</div>
@include("panelEnd")
<script src="{{asset('public/js/number_to_words.js')}}"></script>
<script type="text/javascript">
    debit_sl=0;
    credit_sl=0;

    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
        $("#journalEntryForm").formValidation();

        $("#journalEntryForm").on('click', '.add_account', function(e) {
            var account_type = $(this).attr('data');
            add_account(account_type);
        });

        $("#journalEntryForm").on('click', '.remove_amount', function(e) {
            var account_type = $(this).attr('data');
            //alert(account_type);
            $(this).parent().parent().remove();
            if($("#"+account_type+"_selected_account > tr").length==0) {
                $("#"+account_type+"_selected_account").append('<tr class="nothing_here"><td colspan="5" class="text-center">Nothing here</td></tr>');
            } else {
                $("#"+account_type+"_selected_account > tr").each(function(index, element) {
                    $(element).find("."+account_type+"_sl_no").text(index+1);
                });
            }
            update_total_amount(account_type);
            if(account_type=='debit') {
                debit_sl--;
            } else if(account_type=='credit') {
                credit_sl--;
            }

        });
    });

    function add_account(acc_type) {
        var account_with_id = $("#account").val();
        var account = $("#"+acc_type+"_account").val();
        var amount = parseFloat($("#"+acc_type+"_amount").val());
        if(account && amount>0) {
            var account_array = account.split("~");
            var account_code = $("#"+acc_type+"_selected_account").find('input[name="'+acc_type+'_account_codes[]"][value="'+account_array[0]+'"]').length;
            if(account_code>0) {
                alert('You already select this account');
                $("#"+acc_type+"_account").val('');
                $("#"+acc_type+"_account").select2({placeholder: "Select"});
                return false;
            }
            if(acc_type=='debit') {
                debit_sl++;
                sl = debit_sl;
            } else if(acc_type=='credit') {
                credit_sl++;
                sl = credit_sl;
            }
            var html = '<tr><td class="'+acc_type+'_sl_no">'+sl+'</td><td>'+account_array[0]+'</td><td>'+account_array[1]+'</td><td>&#2547; '+amount+'</td><td class="text-center"><i class="icomoon-icon-close remove_amount hand" title="remove" data="'+acc_type+'"></i></td><input type="hidden" name="'+acc_type+'_account_codes[]" value="'+account_array[0]+'" /><input type="hidden" name="'+acc_type+'_amounts[]" value="'+amount+'" /></tr>';
            if($('#'+acc_type+'_selected_account').find('.nothing_here').is(':visible')) {
                $('#'+acc_type+'_selected_account').find('.nothing_here').remove();
            }
            $("#"+acc_type+"_selected_account").append(html);
            $("#"+acc_type+"_account").val('');
            $("#"+acc_type+"_amount").val('');
            $("#"+acc_type+"_account").select2({placeholder: "Select"});
            update_total_amount(acc_type);
        } else {
            alert('Please select account with amount');
        }
    }

    function update_total_amount(account_type) {
        var total_amount = 0;
        $("#"+account_type+"_selected_account").find('input[name="'+account_type+'_amounts[]"]').each(function(index, element) {
            total_amount += parseFloat($(element).val());
        });
        $('#'+account_type+'_total_amount_text').text(total_amount);
        $('#journalEntryForm').find('input[name="'+account_type+'_total_amount"]').val(total_amount);
    }

    function refresh_accounts_area() {
        $("#debit_selected_account").html('<tr class="nothing_here"><td colspan="5" class="text-center">Nothing here</td></tr>');
        $("#credit_selected_account").html('<tr class="nothing_here"><td colspan="5" class="text-center">Nothing here</td></tr>');
        update_total_amount('debit');
        update_total_amount('credit');
    }
</script>
