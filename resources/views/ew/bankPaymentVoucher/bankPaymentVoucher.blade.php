<?php $panelTitle = "Bank Payment Voucher"; ?>
@include("panelStart")
<div class="row mt15">
    <!-- For Payment Add -->
    <form id="paymentVoucherAddForm" method="post" type="create" class="form-load form-horizontal" data-fv-excluded="" callback="refresh_accounts_area">
        {{csrf_field()}}
        <div class="col-lg-6 col-md-6 sortable-layout">
            <div class="panel panel-default chart">
                <div class="panel-body">
                    <div class=simple-chart>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Project:</label>
                            <div class="col-md-9">
                                <select name="ew_project_id" id="ew_project_id" data-fv-icon="false" class="select2 form-control ml0" required>
                                    <option value=""></option>
                                    <option value="0">Head Office</option>
                                    @foreach($projects as $project)
                                        <option value="{{$project->id}}">{{$project->project_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Account Code:</label>
                            <div class="col-md-9">
                                <div id="accountCodeView">
                                    <select id="account" name="account" data-fv-icon="" class="select2" style="width:100%;">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-3 control-label required">Payment by:</label>
                            <div class="col-md-9">
                                <select id="transaction_by" name="transaction_by" data-fv-icon="" class="select2" style="width:100%;" required>
                                    <option value=""></option>
                                    @foreach($bank_accounts as $account)
                                        <option value="{{$account->account_code}}">{{$account->account_code.' - '.$account->account_head}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Cheque No:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="cheque_no" id="cheque_no" placeholder="Cheque No" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Cheque Date:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="cheque_date" id="cheque_date" placeholder="dd/mm/yyyy" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label required">Amount:</label>
                            <div class="col-md-7">
                                <div class="input-group">
                                    <span class="input-group-addon">৳</span>
                                    <input id="amount" name="amount" placeholder="e.g. 50000.00" class="form-control number" kl_virtual_keyboard_secure_input="on" data-fv-regexp="true" data-fv-regexp-regexp="^[0-9+\s\.]+$" data-fv-regexp-message="Amount can consist of number only">
                                </div>
                                 <p class="text-center"><strong class="words  text-primary"></strong></p>
                            </div>
                            <div class="col-lg-2 col-md-2 pl0 mt10-mbl">
                                <button id="add_amount" type="button" class="btn btn-block btn-info">Add</button>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-3 control-label required">Remarks:</label>
                            <div class="col-md-9">
                                <textarea required autofocus id="remarks" name="remarks" placeholder="Remarks" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group" id="total_amount_area">
                            <strong>
                                <label class="col-lg-12 col-md-12 col-xs-12 control-label" style="padding-left:40px; font-size:18px;">
                                    Total Amount:
                                    <span>&#2547;</span>
                                    <span id="total_amount_text">00</span>
                                    <input type="hidden" name="total_amount" value="0"/>
                                </label>
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Summary -->
        <div class="col-lg-6 col-md-6 sortable-layout col-no-pl">
            <div class="panel panel-default chart">
                <div class="panel-body">
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
                        <tbody id="selected_account">
                            <tr class="nothing_here">
                                <td colspan="5" class="text-center">Nothing here</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 btn-pb15">
            <button type="submit" class="btn btn-default">Save Bank Payment Voucher</button>
        </div>
    </from>
</div>
@include("panelEnd")
<script src="{{asset('public/js/number_to_words.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        sl=0;
        $(".select2").select2({
            placeholder: "Select"
        });
        $("#cheque_date").datepicker({format: 'dd/mm/yyyy'});

        var cur_project_id = "";
        $("#ew_project_id").on('change', function(e) {
            var ew_project_id = $("#ew_project_id").val();
            if(cur_project_id==0 && ew_project_id>0) {
                refresh_accounts_area();
            }
            if((cur_project_id=='') || (cur_project_id==0 && ew_project_id>0) || (cur_project_id>0 && ew_project_id==0)) {
                $.ajax({
                    url: '{{route("ew.projectAccountCode")}}',
                    data: {ew_project_id:ew_project_id},
                    type: 'GET',
                    dataType: "html",
                    success: function(data) {
                        $("#accountCodeView").html(data);
                        $("#account").select2({ placeholder: "Select" });
                    }
                });
            }
            cur_project_id = ew_project_id;
        });

        $("#paymentVoucherAddForm").formValidation();
        $("#add_amount").on('click', function(e) {
            var account = $("#account").val();
            var amount = parseFloat($("#amount").val());
            if(account && amount>0) {
                var account_array = account.split("~");
                var account_code = $("#selected_account").find('input[name="account_codes[]"][value="'+account_array[0]+'"]').length;
                if(account_code>0) {
                    alert('You already select this account');
                    $("#account").val('');
                    $("#account").select2({placeholder: "Select"});
                    return false;
                }
                sl++;
                var html = '<tr><td class="sl_no">'+sl+'</td><td>'+account_array[0]+'</td><td>'+account_array[1]+'</td><td>&#2547; '+amount+'</td><td class="text-center"><i class="icomoon-icon-close remove_amount hand" title="remove"></i></td><input type="hidden" name="account_codes[]" value="'+account_array[0]+'" /><input type="hidden" name="amounts[]" value="'+amount+'" /><input type="hidden" name="account_head[]" value="'+account_array[1]+'" /></tr>';
                if($('#selected_account').find('.nothing_here').is(':visible')) {
                    $('#selected_account').find('.nothing_here').remove();
                }
                $("#selected_account").append(html);
                $("#account").val('');
                $("#amount").val('');
                $("#account").select2({placeholder: "Select"});
                update_total_amount();
                //For Auto Remarks Generate
                genRemarks();
            } else {
                alert('Please select account with amount');
            }
        });

        $("#selected_account").on('click', '.remove_amount', function(e) {
            $(this).parent().parent().remove();
            if($("#selected_account > tr").length==0) {
                $("#selected_account").append('<tr class="nothing_here"><td colspan="5" class="text-center">Nothing here</td></tr>');
            } else {
                $("#selected_account > tr").each(function(index, element) {
                    $(element).find(".sl_no").text(index+1);
                });
            }
            update_total_amount();
            sl--;
        });
    });

    //Reset Remarks
    $("#ew_project_id").on('change', function(e) {
        $("#paymentVoucherAddForm").find("#remarks").val('');
    });

    //Auto Remarks Generate
    //Auto Remarks Generate
    function genRemarks(){
        var projectName = $("#ew_project_id option:selected").text();
        var accountHeads = '';
        $("#selected_account").find("input[name='account_head[]']").each(function() {
                accountHeads=accountHeads+$(this).val().join( ", " );
        });
        if (projectName && accountHeads) {
            var remarks = 'Project: '+projectName+'-Accounts: '+accountHeads;
            $("#remarks").val(remarks);
        }
    }

    function update_total_amount() {
          var total_amount = 0;
          $("#selected_account").find('input[name="amounts[]"]').each(function(index, element) {
              total_amount += parseFloat($(element).val());
          });
          $('#total_amount_text').text(total_amount);
          $('#total_amount_area').find('input[name="total_amount"]').val(total_amount);
    }

    function refresh_accounts_area() {
        $("#selected_account").html('<tr class="nothing_here"><td colspan="5" class="text-center">Nothing here</td></tr>');
        update_total_amount();
    }
</script>
