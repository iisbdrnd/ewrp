@if(!empty($voucher))
<?php $panelTitle = "Edit Transact"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped" callback="transactFormClose">
    {{csrf_field()}}
    <input type="hidden" name="id" value="{{$voucher->id}}">
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Transaction No</label>
        <div class="col-lg-9 col-md-8">
            <input class="form-control" value="{{$transaction_no}}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Date</label>
        <div class="col-lg-9 col-md-8">
            <input class="form-control" value="{{$date}}" readonly>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Remarks</label>
        <div class="col-lg-9 col-md-8">
            <textarea required autofocus id="remarks" name="remarks" placeholder="Remarks" class="form-control">{{$voucher->remarks}}</textarea>
        </div>
    </div>
</form>

<script type="text/javascript">
    function transactFormClose() {
        bootbox.hideAll();
    }
</script>
@else
<div class="voucher">
    <p class="text-center">Data not found..</p>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $(".modal").find("button[data-bb-handler='success']").remove();
    });
</script>
@endif
