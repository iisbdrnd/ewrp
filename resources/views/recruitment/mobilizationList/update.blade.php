<?php $panelTitle = "Mobilization Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group" style="{{ Auth::user()->get()->id == 48?'':'display:none' }}">
        <label class="col-lg-3 col-md-4 control-label required">Mobilization Action</label>
        <div class="col-lg-9 col-md-8">
        <select name="mobilize_action" id="mobilize_action" class="form-control select2">
            <option>Choose Action</option>
            <option value="1" {{ $ewMobilization->mobilize_action == 1?'selected=selected':'' }}>Follow Up</option>
            <option value="2" {{ $ewMobilization->mobilize_action == 2?'selected=selected':'' }}>Operation</option>
        </select>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-3 col-md-4 control-label required">Mobilization Name</label>
        <div class="col-lg-9 col-md-8">
            <input autofocus required name="name" placeholder="e.g. Medical" class="form-control" value="{{$ewMobilization->name}}">
        </div>
    </div>
</form>