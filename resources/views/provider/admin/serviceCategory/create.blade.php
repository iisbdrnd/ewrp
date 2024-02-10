<?php $panelTitle = "Service Category Create"; ?>
@include("panelStart")
<form type="create" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Name</label>
        <div class="col-lg-10 col-md-9">
            <input autofocus required name="name" placeholder="Service Category Name" class="form-control">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Descriprion</label>
        <div class="col-lg-10 col-md-9">
            <textarea autofocus name="description" rows="5" class="form-control"></textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Auto</label>
        <div class="col-lg-2 col-md-1">
            <div class="toggle-custom toggle-inline">
                <label class="toggle" data-on=Yes data-off=No>
                    <input type=checkbox id="approval_status" name="approval_status" value="1"> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label" for="product_id">Action Planner</label>

        <div class="col-lg-10 col-md-9">
            <select name="employee_id[]" id="employee_id" class="select2 product" style="width: 550px" multiple="multiple" required>
                    @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}">{{  $employee->name }}</option> 
                    @endforeach
            </select>
        </div>
    </div>
    
    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Create</button>
        </div>
    </div>
</form>
@include("panelEnd")

<script type="text/javascript">
    $(document).ready(function() {
        $(".select2").select2({
            placeholder: "Select"
        });
    });
</script>