<?php $panelTitle = "Trade Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Trade Name</label>
        <div class="col-lg-9 col-md-8">
            <input autofocus required name="trade_name" placeholder="Trade Name" class="form-control" value="{{$ewTrade->trade_name}}">
        </div>
    </div>
</form>