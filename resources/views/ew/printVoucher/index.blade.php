<?php $panelTitle = "Print Voucher"; ?>
@include("panelStart")
<div class="row mt15">
    <!-- For Payment Add -->
    <form id="voucherPrintForm" method="post" type="view" class="form-horizontal" data-fv-excluded="">
        {{csrf_field()}}
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
                    <div class="radio-custom radio-inline">
                        <input name="voucher_mode" value="1" id="transaction" type="radio" checked>
                        <label for="transaction">Transaction No.</label>
                    </div>
                    <div class="radio-custom radio-inline">
                        <input name="voucher_mode" value="2" id="date_range" type="radio">
                        <label for="date_range">Date Range</label>
                    </div>
                </div>
            </div>
            <div id="transaction_div">
                <div class="form-group">
                    <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
                        <input id="transaction_no" name="transaction_no" placeholder="Enter Transaction No." class="form-control" data-fv-row=".col-md-4" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
                        <div class="input-group">
                            <input id="date" name="date" placeholder="Date: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" data-fv-row=".col-md-4" required>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="date_range_div" style="display: none;">
                <div class="form-group">
                    <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
                        <select name="voucher_type" id="voucher_type" data-fv-icon="false" class="select2 form-control ml0">
                            <option value="">All Voucher</option>
                            <option value="pv">Payment Voucher</option>
                            <option value="rv">Received Voucher</option>
                            <option value="bpv">Bank Payment Voucher</option>
                            <option value="brv">Bank Received Voucher</option>
                            <option value="jv">Journal Voucher</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
                        <div class="input-group">
                            <input id="from_date" name="from_date" placeholder="From Date: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" data-fv-row=".col-md-4" required>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-4 col-md-4 col-lg-offset-4 col-md-offset-4">
                        <div class="input-group">
                            <input id="to_date" name="to_date" placeholder="To Date: dd/mm/yyyy" class="form-control dtpicker" data-fv-trigger="blur" data-fv-row=".col-md-4" required>
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-offset-4 col-md-8 btn-pb15">
            <button id="preview_button" type="submit" class="btn btn-info">Preview</button>
        </div>
    </form>
</div>
@include("panelEnd")
<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});
        $('#voucherPrintForm').formValidation('removeField', $("#from_date"));
        $('#voucherPrintForm').formValidation('removeField', $("#to_date"));

        $("input[name=voucher_mode]").on('change', function() {
            var voucher_mode = $(this).val();
            if (voucher_mode==1) {
                $("#transaction_div").show();
                $("#date_range_div").hide();
                $('#voucherPrintForm').formValidation('addField', $("#transaction_no"));
                $('#voucherPrintForm').formValidation('addField', $("#date"));
                $('#voucherPrintForm').formValidation('removeField', $("#from_date"));
                $('#voucherPrintForm').formValidation('removeField', $("#to_date"));
            } else {
                $("#transaction_div").hide();
                $("#date_range_div").show();
                $('#voucherPrintForm').formValidation('removeField', $("#transaction_no"));
                $('#voucherPrintForm').formValidation('removeField', $("#date"));
                $('#voucherPrintForm').formValidation('addField', $("#from_date"));
                $('#voucherPrintForm').formValidation('addField', $("#to_date"));
            }
        });

        $("#voucherPrintForm").formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var voucher_mode = $('input[name="voucher_mode"]:checked').val();
            var transaction_no = $('#transaction_no').val();
            var date = $('#date').val();
            var voucher_type = $('#voucher_type').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            //console.log(postData);
            var width = $(document).width();
            var height = $(document).height();
            var url = "{{route('ew.printVoucherReport')}}"+'?voucher_mode='+voucher_mode+'&date='+date+'&transaction_no='+transaction_no+'&voucher_type='+voucher_type+'&from_date='+from_date+'&to_date='+to_date;
            var myWindow = window.open(url, "", "width="+width+",height="+height);
            $('#preview_button').removeAttr('disabled').removeClass('disabled');
        });
    });
</script>
