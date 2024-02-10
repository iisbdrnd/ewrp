<?php $panelTitle = "Service Category Update"; ?>
<form type="update" panelTitle="{{$panelTitle}}" class="form-load form-horizontal group-border stripped">
    {{csrf_field()}}
    <input type="hidden" name="service_category_id" value={{$serviceCategory->id}}>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label required">Service Category Name</label>
        <div class="col-lg-10 col-md-9">
            <input autofocus required name="name" placeholder="Service Category Name" class="form-control" value="{{$serviceCategory->name}}">
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Descriprion</label>
        <div class="col-lg-10 col-md-9">
            <textarea autofocus name="description" rows="5" class="form-control">{{$serviceCategory->description}}</textarea>
        </div>
    </div>
    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label">Auto</label>
        <div class="col-lg-2 col-md-1">
            <div class="toggle-custom toggle-inline">
                <label class="toggle" data-on=Yes data-off=No>
                    <input type=checkbox id="approval_status" name="approval_status" value="1" @if(@$serviceCategory->approval_status==1){{"checked"}}@endif> <span class=button-checkbox></span>
                </label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-lg-2 col-md-3 control-label" for="product_id">Action Planner</label>

        <div class="col-lg-10 col-md-9">
            <select name="employee_id[]" id="employee_id" class="select2" style="width: 550px" multiple="multiple" required>
                @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}" @if(in_array($employee->id, $serviceCategoryEmployees)) {{'selected'}} @endif>{{ $employee->name }}
                        </option> 
                @endforeach
            </select>
        </div>
    </div>

    <div class="form-group">
        <div class="col-lg-offset-2">
            <button type="submit" class="btn btn-default ml15">Update</button>
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