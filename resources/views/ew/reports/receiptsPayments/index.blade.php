<?php $panelTitle = "Receipts & Payments"; ?>
@include("panelStart")
<div class="row mt15">
    <!-- For Payment Add -->
    <form id="ledgerQueryForm" method="post" type="view" class="form-horizontal" data-fv-excluded="">
        {{csrf_field()}}
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">Project</label>
                <div class="col-lg-4 col-md-4">
                    <select name="project_id" id="project_id" data-fv-icon="false" class="select2 form-control ml0">
                        <option value=''>All Project</option>
                        <option value='0'>Head Office</option>
                        @foreach($projects as $project)
                            <option value="{{$project->id}}">{{$project->project_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-4">
                    <div class="radio-custom pull-right">
                        <input id="all_account_type" name="account_type" type="radio" checked value="0">
                        <label for="all_account_type">Both:</label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-4">
                    <div class="radio-custom pull-right">
                        <input id="cash" name="account_type" type="radio" value="1">
                        <label for="cash">Cash:</label>
                    </div>
                </div>
                <div id="cash_accounts" class="col-lg-4 col-md-4" style="display: none;">
                    <select id="cash_account_code" name="cash_account_code" data-fv-icon="" class="select2" style="width:100%;" required>
                        <option value=""></option>
                        @foreach($cash_accounts as $account)
                            <option value="{{$account->account_code}}">{{$account->account_code.' - '.$account->account_head}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-lg-4 col-md-4">
                    <div class="radio-custom pull-right">
                        <input id="bank" name="account_type" type="radio" value="2">
                        <label for="bank">Bank:</label>
                    </div>
                </div>
                <div id="bank_accounts" class="col-lg-4 col-md-4" style="display: none;">
                    <select id="bank_account_code" name="bank_account_code" data-fv-icon="" class="select2" style="width:100%;" required>
                        <option value=""></option>
                        @foreach($bank_accounts as $account)
                            <option value="{{$account->account_code}}">{{$account->account_code.' - '.$account->account_head}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 col-md-4 control-label">Date Range:</label>
                <div class="col-lg-2 col-md-2">
                    <div class="input-group">
                        <input id="from_date" name="from_date" placeholder="From: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" required data-fv-row=".col-md-2">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
                <div class="col-lg-2 col-md-2">
                    <div class="input-group">
                        <input id="to_date" name="to_date" placeholder="To: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" required data-fv-row=".col-md-2">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-offset-4 col-md-8 btn-pb15">
            <button id="preview_button" type="submit" class="btn btn-default">Preview</button>
        </div>
    </form>
</div>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        date_range = 0;
        $(".select2").select2({
            placeholder: "Select"
        });
        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});
        $('#ledgerQueryForm').formValidation('removeField', $("#cash_account_code"));
        $('#ledgerQueryForm').formValidation('removeField', $("#bank_account_code"));

        $("input[name=account_type]").on('change', function() {
            account_type = $(this).val();
            if (account_type==1) {
                if(!($("#bank_accounts").is(":hidden"))) {
                    $('#ledgerQueryForm').formValidation('removeField', $("#bank_account_code"));
                    $("#bank_accounts").hide();
                }
                $("#cash_accounts").show();
                $('#ledgerQueryForm').formValidation('addField', $("#cash_account_code"));
            } else if(account_type==2) {
                if(!($("#cash_accounts").is(":hidden"))) {
                    $('#ledgerQueryForm').formValidation('removeField', $("#cash_account_code"));
                    $("#cash_accounts").hide();
                }
                $("#bank_accounts").show();
                $('#ledgerQueryForm').formValidation('addField', $("#bank_account_code"));
            } else {
                if(!($("#bank_accounts").is(":hidden"))) {
                    $('#ledgerQueryForm').formValidation('removeField', $("#bank_account_code"));
                    $("#bank_accounts").hide();
                }
                if(!($("#cash_accounts").is(":hidden"))) {
                    $('#ledgerQueryForm').formValidation('removeField', $("#cash_account_code"));
                    $("#cash_accounts").hide();
                }
            }
        });
        $("#ledgerQueryForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var project = $('#project_id').val();
            var account_type = $('input[name="account_type"]:checked').val();
            var cash = $('#cash_account_code').val();
            var bank = $('#bank_account_code').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            //console.log(postData);
            var width = $(document).width();
            var height = $(document).height();
            var previewType = 1;
            var url = "{{route('ew.receiptsPaymentsReport')}}"+'?project='+project+'&account_type='+account_type+'&cash='+cash+'&bank='+bank+'&from_date='+from_date+'&to_date='+to_date;
            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });
    });
</script>
