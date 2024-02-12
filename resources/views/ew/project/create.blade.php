<?php $panelTitle = "Project Create"; ?>
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Project Name</label>
        <div class="col-lg-9 col-md-8">
            <input autofocus required name="project_name" placeholder="Project Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Select Trades</label>
        <div class="col-lg-9 col-md-8">
            <select required name="trade_id[]" data-fv-icon="false" add-url="tradeAdd" class="@if($tradeAddAccess){{'select2-add'}}@endif select2 form-control ml0" multiple="multiple">
                <option value=""></option>
                @foreach($trades as $trade)
                <option value="{{$trade->id}}">{{$trade->trade_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
    });
</script>