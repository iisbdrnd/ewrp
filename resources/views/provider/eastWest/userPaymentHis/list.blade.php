@include("urlParaMeter")
<?php $panelTitle = "Payment History Search"; ?>
@include("panelStart")
<div class="form-inline">
    <form id="searchForm">
        <div class="row datatables_header">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group">
                    <span class=input-group-addon><i class="fa fa-calendar"></i></span><input data-fv-row=".col-md-3" required id="from_date" name="closed_date" placeholder="From Date" class="form-control" data-fv-trigger="blur">
                </div>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="input-group">
                    <span class=input-group-addon><i class="fa fa-calendar"></i></span><input data-fv-row=".col-md-3" required id="to_date" name="closed_date" placeholder="To Date" class="form-control" data-fv-trigger="blur">
                </div>
            </div>
            <div class="col-md-1 col-sm-3 col-xs-12">
                <span class="input-group-btn"><button name="search" id="dateWiseSearch" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="submit">Search</button></span>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-12">
                <span class="input-group-btn"><button id="upToDate" name="search" event="click" valueFrom="#search-input" class="data-search btn btn-default" type="button">Up to date</button></span>
            </div>
        </div>
    </form>
</div>
@include("panelEnd")

@if(empty($inputData['takeContent']))
<div id="paymentHistory">
<?php $tableTitle = "Payment History"; $loadUrl = "paymentHistoryList"; $urlParameter = "false"; ?>
@include("dataListFrame")
</div>
@endif

@if(empty($inputData['takeContent']))
<div id="dataListViewClone" style="display: none;">
    <?php $tableTitle = "Payment History"; $loadUrl = ""; $urlParameter = "false"; ?>
    @include("dataListFrame")
</div>
@endif

<script type="text/javascript">
    $(document).ready(function(){
        $("#dataListViewClone").find(".panel").removeClass("data-table");

        $('#from_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });
        $('#to_date').datetimepicker({
            format: 'DD/MM/YYYY'
        });

        $("#from_date").on("dp.change", function (e) {
            $('#to_date').data("DateTimePicker").minDate(e.date);
        });
        $("#to_date").on("dp.change", function (e) {
            $('#from_date').data("DateTimePicker").maxDate(e.date);
        });

        $('#searchForm').formValidation().on('success.form.fv', function(e) {
            e.preventDefault();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            $("#paymentHistory").html($("#dataListViewClone").html());
            $("#paymentHistory").find(".panel").attr("load-url", "paymentHistoryList?from_date="+from_date+"&to_date="+to_date);
            urlParameterDestroy();
            loadDataTable($("#paymentHistory").find(".panel"), "paymentHistory", false);
        });

        $('#upToDate').on('click', function(e){
            e.preventDefault();
            $('#searchForm').trigger('reset').data('formValidation').resetForm();
            $("#paymentHistory").html($("#dataListViewClone").html());
            $("#paymentHistory").find(".panel").attr("load-url", "paymentHistoryList");
            urlParameterDestroy();
            loadDataTable($("#paymentHistory").find(".panel"), "paymentHistory", false);
        });
    });
</script>
