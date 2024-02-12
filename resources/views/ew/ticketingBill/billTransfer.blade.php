<?php $panelTitle = "Bill Transfer"; ?>
@include("panelStart")
<div class="row mt15">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <form callback="billTransferForm" type="create" id="billTransferForm" class="form-load form-horizontal" data-fv-excluded="">
            {{csrf_field()}}
            <input type="hidden" name="bill_id" value="{{$ticketingBill->id}}">
            <div class="row">
                <div class="form-group col-lg-12 col-md-12 col-xs-12" id="bill_no">
                    <label class="col-lg-3 col-md-3 control-label required">Bill No.</label>
                    <div class="col-lg-9 col-md-9">
                        <input type="text" name="bill_no" required placeholder="e.g. 01016" class="form-control">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-12 col-md-12 col-xs-12" id="cheque_date_view">
                    <label class="col-lg-3 col-md-3 control-label required">Bill Date</label>
                    <div class="col-lg-9 col-md-9" id="bill_date">
                        <input type="text" name="bill_date" placeholder="dd/mm/yyyy" class="form-control dtpicker" required>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@include("panelEnd")

<script type="text/javascript">
    $(document).ready(function() {
        $(".dtpicker").datepicker({format: 'dd/mm/yyyy'});
    })

    function billTransferForm(data) {
        bootbox.hideAll();
    }
</script>