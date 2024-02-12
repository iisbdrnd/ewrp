<?php $panelTitle = "Mobilization Create"; ?>
@include("panelStart")
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Mobilization Name</label>
        <div class="col-lg-9 col-md-8">
            <input required name="name" placeholder="e.g.Medical GMCA" class="form-control">
        </div>
    </div>
</form>
@include("panelEnd")
